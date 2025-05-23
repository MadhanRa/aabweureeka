<?php

namespace App\Services;

use App\Models\transaksi\pembelian\ModelPembelian;
use App\Models\transaksi\pembelian\ModelPembelianDetail;
use App\Models\setup_persediaan\ModelStockGudang;
use CodeIgniter\Database\ConnectionInterface;

class PembelianService
{
    protected $pembelian;
    protected $pembelianDetail;
    protected $stockGudang;
    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    protected $db;

    public function __construct(
        ModelPembelian $pembelian,
        ModelPembelianDetail $detail,
        ModelStockGudang $stockGudang,
        /**
         * @var \CodeIgniter\Database\BaseConnection
         */
        ConnectionInterface $db
    ) {
        $this->pembelian = $pembelian;
        $this->pembelianDetail = $detail;
        $this->stockGudang = $stockGudang;
        $this->db = $db;
    }

    public function save(array $headerData, array $detailData, ?int $id = null): int
    {
        $this->db->transBegin();

        try {
            $idPembelian = $id
                ? $this->updateHeader($id, $headerData)
                : $this->createHeader($headerData);

            $this->syncDetails($idPembelian, $detailData, $headerData['id_lokasi']);

            $this->db->transCommit();
            return $idPembelian;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    protected function createHeader(array $data): int
    {
        return $this->pembelian->insert($data);
    }

    protected function updateHeader(int $id, array $data): int
    {
        $this->pembelian->update($id, $data);
        return $id;
    }

    protected function syncDetails(int $idPembelian, array $newDetails, string $lokasi)
    {
        $existing = $this->pembelianDetail->where('id_pembelian', $idPembelian)->findAll();
        $existingIds = array_column($existing, 'id');

        $incomingIds = array_column($newDetails, 'id_detail');

        // Delete removed rows
        foreach ($existing as $row) {
            if (!in_array($row->id, $incomingIds)) {
                $this->pembelianDetail->delete($row->id);
                $this->stockGudang->where(['id_lokasi' => $lokasi, 'id_stock' => $row->id_stock])->decrement('qty1', $row->qty1);
            }
        }

        // Upsert new or existing
        foreach ($newDetails as $detail) {
            if (empty($detail['id_stock'])) continue;

            $detail['id_pembelian'] = $idPembelian;

            if (isset($detail['id_detail']) && in_array($detail['id_detail'], $existingIds)) {
                $this->pembelianDetail->update($detail['id_detail'], $detail);
            } else {
                $this->pembelianDetail->insert($detail);
            }

            // Update stock logic can be improved by abstracting further
        }
    }
}
