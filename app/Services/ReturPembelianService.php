<?php

namespace App\Services;

use App\Models\transaksi\pembelian\ModelPembelian;
use App\Models\transaksi\pembelian\ModelReturPembelian;
use App\Models\transaksi\pembelian\ModelReturPembelianDetail;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\transaksi\ModelRiwayatHutang;
use App\Models\transaksi\ModelMutasiStock;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelSetupsupplier;
use App\Models\setup\ModelHutangPiutang;
use App\ValueObjects\DetailItem;

use CodeIgniter\Database\ConnectionInterface;

class ReturPembelianService
{
    protected $riwayatTransaksi;
    protected $riwayatHutang;
    protected $mutasiStock;
    protected $returPembelian;
    protected $returPembelianDetail;
    protected $stockGudang;
    protected $bukuBesar;
    protected $supplier;
    protected $riwayatHP;
    protected $pembelian;
    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    protected $db;

    public function __construct(
        ModelPembelian $pembelian,
        ModelReturPembelian $returPembelian,
        ModelReturPembelianDetail $detail,
        ModelStockGudang $stock,
        ModelSetupBuku $buku,
        ModelRiwayatTransaksi $riwayat,
        ModelRiwayatHutang $hutang,
        ModelMutasiStock $mutasiStock,
        ModelSetupsupplier $supplier,
        ModelHutangPiutang $hutangPiutang,
        /**
         * @var \CodeIgniter\Database\BaseConnection
         */
        ConnectionInterface $db
    ) {
        $this->pembelian = $pembelian;
        $this->returPembelian = $returPembelian;
        $this->returPembelianDetail = $detail;
        $this->stockGudang = $stock;
        $this->mutasiStock = $mutasiStock;
        $this->bukuBesar = $buku;
        $this->riwayatTransaksi = $riwayat;
        $this->riwayatHutang = $hutang;
        $this->supplier = $supplier;
        $this->riwayatHP = $hutangPiutang;
        $this->db = $db;
    }

    public function save(array $headerData, array $detailData, $id = null): int
    {
        $this->db->transBegin();

        try {

            $idReturPembelian = $id
                ? $this->updateHeader($id, $headerData)
                : $this->createHeader($headerData);


            $this->saveDetails($idReturPembelian, $detailData, $headerData);

            $this->db->transCommit();
            return $idReturPembelian;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    protected function createHeader(array $data): int
    {
        return $this->returPembelian->insert($data);
    }

    protected function updateHeader($id, array $data): int
    {
        $this->returPembelian->update($id, $data);
        return $id;
    }

    protected function saveDetails(int $idReturPembelian, array $newDetails, array $headerData)
    {
        $existing = $this->returPembelianDetail->where('id_returpembelian', $idReturPembelian)->findAll();
        $existingIds = array_column($existing, 'id');
        $incomingIds = array_column($newDetails, 'id_detail');

        // Hapus detail lama yang tidak ada di detail baru (ganti stock)
        foreach ($existing as $row) {
            if (!in_array($row->id, $incomingIds)) {
                // Hapus detail pembelian yang tidak ada di detail baru
                $this->returPembelianDetail->delete($row->id);

                // Kembalikan stok ke gudang
                $stock = $this->stockGudang->where([
                    'id_lokasi' => $headerData['id_lokasi'],
                    'id_stock' => $row->id_stock
                ])->first();

                if ($stock) {
                    $this->stockGudang->update($stock->id, [
                        'qty1' => $stock->qty1 - $row->qty1,
                        'qty2' => $stock->qty2 - $row->qty2
                    ]);
                }

                // Hapus mutasi stok terkait
                $this->mutasiStock->where([
                    'id_stock' => $row->id_stock,
                    'id_lokasi' => $headerData['id_lokasi'],
                    'jenis' => 'masuk',
                    'sumber_transaksi' => 'retur_pembelian',
                    'id_transaksi' => $idReturPembelian
                ])->delete();
            }
        }

        foreach ($newDetails as $detail) {
            // Skip empty rows (where there's no stock ID)
            if (empty($detail['id_stock'])) continue;

            $detailPembelian = new DetailItem($detail);

            // Create detail record
            $detailRecord = $detailPembelian->getRecords();
            $detailRecord = array_merge($detailRecord, [
                'id_returpembelian' => $idReturPembelian,
            ]);

            if (isset($detail['id_detail']) && in_array($detail['id_detail'], $existingIds)) {
                $this->insertOrUpdatteMutasiStock($headerData, $detail, $idReturPembelian);
                // Sync stock in stock1_gudang table
                $this->syncStockGudang($headerData, $detail);
                $this->returPembelianDetail->update($detail['id_detail'], $detailRecord);
            } else {
                $this->insertOrUpdatteMutasiStock($headerData, $detail, $idReturPembelian);
                // Sync stock in stock1_gudang table
                $this->syncStockGudang($headerData, $detail);
                $this->returPembelianDetail->insert($detailRecord);
            }
        }

        // Jika opsi retur pembelian tunai, maka update buku besar, jika tidak update hutang
        $opsi_return = $headerData['opsi_return'];

        if ($opsi_return === 'tunai') {
            $this->setPerubahanBukuBesar($headerData, $idReturPembelian);
        } else {
            $this->setHutang($headerData, $idReturPembelian);
        }
    }

    /**
     * Insert atau update mutasi stok berdasarkan detail pembelian
     * 
     * @param array $headerData Data header pembelian
     * @param array $detail Data detail pembelian
     * @return void
     */
    protected function insertOrUpdatteMutasiStock(array $headerData, array $detail, $idReturPembelian): void
    {
        // Skip jika tidak ada id stock
        if (empty($detail['id_stock'])) {
            return;
        }

        // Cek apakah mutasi stok sudah ada
        $existingMutasi = $this->mutasiStock
            ->where([
                'id_stock' => $detail['id_stock'],
                'id_lokasi' => $headerData['id_lokasi'],
                'jenis' => 'keluar',
                'sumber_transaksi' => 'retur_pembelian',
                'id_transaksi' => $idReturPembelian
            ])
            ->first();

        if ($existingMutasi) {
            // Update mutasi stok yang sudah ada
            $this->mutasiStock->update($existingMutasi->id_mutasi, [
                'qty1' => floatval($detail['qty1']),
                'qty2' => floatval($detail['qty2']),
                'nilai' => floatval($detail['total_raw']),
                'tanggal' => $headerData['tanggal']
            ]);
            return;
        }

        $mutasiData = [
            'id_stock' => $detail['id_stock'],
            'id_lokasi' => $headerData['id_lokasi'],
            'tanggal' => $headerData['tanggal'],
            'jenis' => 'keluar',
            'qty1' => floatval($detail['qty1']),
            'qty2' => floatval($detail['qty2']),
            'nilai' => floatval($detail['total_raw']),
            'sumber_transaksi' => 'retur_pembelian',
            'id_transaksi' => $idReturPembelian
        ];

        // Insert mutasi stok baru
        $this->mutasiStock->insert($mutasiData);
    }

    /**
     * Sinkronisasi data stok dengan perubahan pada detail pembelian
     * 
     * @param array $headerData Data header pembelian
     * @param array $detail Data detail pembelian
     * @return void
     */
    protected function syncStockGudang(array $headerData, array $detail): void
    {
        // Skip jika tidak ada id stock
        if (empty($detail['id_stock'])) {
            return;
        }

        // 1. Ambil nilai lama dari detail pembelian jika ada
        $oldDetailValues = $this->getExistingDetailValues($detail);

        // 2. Ekstrak nilai baru dari detail
        $newValues = $this->extractDetailValues($detail);

        // 3. Hitung perubahan dalam kuantitas normal dan harga
        $changes = $this->calculateChanges($oldDetailValues, $newValues);

        // 4. Dapatkan atau buat record stok
        $this->updateOrCreateStock($headerData['id_lokasi'], $detail['id_stock'], $changes, $newValues);
    }

    /**
     * Ambil nilai lama dari detail pembelian yang sudah ada
     */
    private function getExistingDetailValues(array $detail): array
    {
        $result = [
            'qty1' => 0,
            'qty2' => 0,
            'jml_harga' => 0
        ];

        if (isset($detail['id_detail']) && !empty($detail['id_detail'])) {
            $existingDetail = $this->returPembelianDetail->find($detail['id_detail']);
            if ($existingDetail) {
                $result = [
                    'qty1' => floatval($existingDetail->qty1),
                    'qty2' => floatval($existingDetail->qty2),
                    'jml_harga' => floatval($existingDetail->jml_harga)
                ];
            }
        }

        return $result;
    }

    /**
     * Ekstrak dan konversi nilai dari detail pembelian baru
     */
    private function extractDetailValues(array $detail): array
    {
        $conv_factor = floatval($detail['conv_factor']);
        $qty1 = floatval($detail['qty1']);
        $qty2 = floatval($detail['qty2']);

        // Tangani berbagai format input jml_harga
        $jmlHarga = isset($detail['jml_harga_raw'])
            ? floatval($detail['jml_harga_raw'])
            : floatval(preg_replace('/[^\d]/', '', $detail['jml_harga']));

        return [
            'conv_factor' => $conv_factor,
            'qty1' => $qty1,
            'qty2' => $qty2,
            'jml_harga' => $jmlHarga,
            'normal_qty' => $qty1 * $conv_factor + $qty2
        ];
    }

    /**
     * Hitung perubahan antara nilai lama dan baru
     */
    private function calculateChanges(array $old, array $new): array
    {
        $old_normal_qty = isset($old['qty1']) && isset($new['conv_factor'])
            ? $old['qty1'] * $new['conv_factor'] + $old['qty2']
            : 0;

        return [
            'qty_diff' => $new['normal_qty'] - $old_normal_qty,
            'price_diff' => $new['jml_harga'] - $old['jml_harga']
        ];
    }

    /**
     * Update stok yang ada atau buat record baru
     */
    private function updateOrCreateStock(int $locationId, int $stockId, array $changes, array $newValues): void
    {
        // Cari stok yang sudah ada
        $existingStock = $this->stockGudang->where([
            'id_lokasi' => $locationId,
            'id_stock' => $stockId
        ])->first();

        if ($existingStock) {
            // Update stok yang sudah ada
            $old_qty1 = floatval($existingStock->qty1);
            $old_qty2 = floatval($existingStock->qty2);
            $old_jmlHarga = floatval($existingStock->jml_harga);

            // Konversi ke kuantitas normal, tambahkan perbedaan, lalu konversi kembali
            $normal_old_qty = $old_qty1 * $newValues['conv_factor'] + $old_qty2;
            $new_normal_qty = $normal_old_qty - $changes['qty_diff'];

            $new_qty1 = floor($new_normal_qty / $newValues['conv_factor']);
            $new_qty2 = $new_normal_qty % $newValues['conv_factor'];
            $new_jmlHarga = $old_jmlHarga - $changes['price_diff'];

            $this->stockGudang->update($existingStock->id, [
                'qty1' => $new_qty1,
                'qty2' => $new_qty2,
                'jml_harga' => $new_jmlHarga,
            ]);
        } else {
            // Buat record stok baru
            $stockData = [
                'id_lokasi' => $locationId,
                'id_stock' => $stockId,
                'qty1' => $newValues['qty1'],
                'qty2' => $newValues['qty2'],
                'jml_harga' => $newValues['jml_harga'],
            ];

            $this->stockGudang->insert($stockData);
        }
    }

    /**
     * Set atau update perubahan buku besar untuk transaksi tunai
     * 
     * @param array $headerData Data header pembelian
     * @param int $idReturPembelian ID pembelian
     * @param float $tunai Jumlah tunai
     * @return void
     */
    protected function setPerubahanBukuBesar(array $headerData, int $idReturPembelian): void
    {
        // Validasi data rekening
        $id_rekening = $this->pembelian->find($headerData['id_pembelian'])->id_setupbuku;
        $dt_rekening = $this->bukuBesar->find($id_rekening);
        if (!$dt_rekening) {
            throw new \Exception('Data rekening tidak ditemukan');
        }

        // Cari riwayat transaksi kas keluar yang sudah ada (jika ada)
        $kas_keluar = $this->riwayatTransaksi
            ->where([
                'id_transaksi' => $idReturPembelian,
                'jenis_transaksi' => 'retur pembelian',
                'id_setupbuku' => $id_rekening
            ])
            ->first();

        $kredit_kas_keluar_lama = 0;

        if ($kas_keluar) {
            // Jika riwayat sudah ada, ambil nilai kredit lama dan ID riwayat
            $kredit_kas_keluar_lama = floatval($kas_keluar->kredit);
        }

        // Ambil data rekening dan update saldo
        $old_saldo = floatval($dt_rekening->saldo_berjalan);

        // Untuk update: kembalikan dulu saldo lama (jumlah kredit lama)
        $current_saldo = $old_saldo + $kredit_kas_keluar_lama;

        // Lalu kurangi dengan nilai tunai baru
        $new_saldo = $current_saldo + $headerData['grand_total'];

        // Update saldo rekening
        $this->bukuBesar->update($id_rekening, [
            'saldo_berjalan' => $new_saldo
        ]);

        $transaksiData = [
            'tanggal' => $headerData['tanggal'],
            'jenis_transaksi' => 'retur pembelian',
            'id_transaksi' => $idReturPembelian,
            'nota' => $headerData['nota'],
            'id_setupbuku' => $id_rekening,
            'debit' => 0,
            'kredit' => $headerData['grand_total'],
            'saldo_setelah' => $new_saldo,
            'deskripsi' => 'Kas Masuk dari Retur'
        ];

        if ($kas_keluar) {
            // Update riwayat transaksi yang sudah ada
            $this->riwayatTransaksi->update($kas_keluar->id, $transaksiData);
        } else {
            // Buat riwayat transaksi baru
            $this->riwayatTransaksi->insert($transaksiData);
        }
    }

    /**
     * Set atau update hutang untuk transaksi
     * 
     * @param array $headerData Data header pembelian
     * @param int $idReturPembelian ID pembelian
     * @param float $hutang Jumlah hutang
     * @return void
     */
    protected function setHutang(array $headerData, int $idReturPembelian): void
    {
        // Validasi data supplier
        $dt_supplier = $this->supplier->find($headerData['id_setupsupplier']);
        if (!$dt_supplier) {
            throw new \Exception('Data supplier tidak ditemukan');
        }

        // Cari data hutang piutang yang sudah ada (jika ada)
        $dt_hutang_lama = $this->riwayatHP
            ->where([
                'id_transaksi' => $idReturPembelian,
                'relasi_id' => $headerData['id_setupsupplier'],
                'relasi_tipe' => 'supplier',
                'jenis' => 'pengurang_hutang'
            ])
            ->first();

        $data = [
            'tanggal' => $headerData['tanggal'],
            'id_transaksi' => $idReturPembelian,
            'nota' => $headerData['nota'],
            'tanggal_jt' => $headerData['tgl_jatuhtempo'],
            'saldo' => $headerData['grand_total'],
            'relasi_id' => $headerData['id_setupsupplier'],
            'relasi_tipe' => 'supplier',
            'jenis' => 'pengurang_hutang'
        ];

        // Update Saldo Supplier
        $old_saldo = floatval($dt_supplier->saldo);
        $current_saldo = $old_saldo;

        if ($dt_hutang_lama) {
            // Update data hutang piutang yang sudah ada
            $hutang_masuk_lama = floatval($dt_hutang_lama->saldo);
            $current_saldo = $old_saldo - $hutang_masuk_lama; // Kembalikan dulu saldo lama

            $this->riwayatHP->update($dt_hutang_lama->id_hutang_piutang, $data);
        } else {
            // Insert data hutang piutang baru
            $this->riwayatHP->insert($data);
        }

        // tambahkan dengan hutang baru
        $new_saldo = $current_saldo - $headerData['grand_total'];

        // Update saldo supplier
        $this->supplier->update($headerData['id_setupsupplier'], [
            'saldo' => $new_saldo
        ]);

        // Cari riwayat transaksi hutang yang sudah ada
        $riwayatHutangLama = $this->riwayatHutang
            ->where([
                'id_transaksi' => $idReturPembelian,
                'jenis_transaksi' => 'retur pembelian',
            ])
            ->first();

        $riwayatHutangData = [
            'tanggal' => $headerData['tanggal'],
            'nota' => $headerData['nota'],
            'id_transaksi' => $idReturPembelian,
            'jenis_transaksi' => 'retur pembelian',
            'id_setupsupplier' => $headerData['id_setupsupplier'],
            'debit' => 0,
            'kredit' => $headerData['grand_total'],
            'saldo_setelah' => $new_saldo,
            'deskripsi' => 'Pengurangan Hutang dari Retur Pembelian'
        ];

        if ($riwayatHutangLama) {
            $this->riwayatHutang->update($riwayatHutangLama->id, []);
        } else {
            $this->riwayatHutang->insert($riwayatHutangData);
        }
    }
}
