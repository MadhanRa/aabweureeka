<?php

namespace App\Services;

use App\Models\transaksi\penjualan\ModelPenjualan;
use App\Models\transaksi\penjualan\ModelPenjualanDetail;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\transaksi\ModelRiwayatPiutang;
use App\Models\transaksi\ModelMutasiStock;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\setup\ModelAntarmuka;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelSetupsalesman;
use App\Models\setup\ModelSetuppelanggan;
use App\Models\setup\ModelHutangPiutang;
use App\ValueObjects\DetailItem;

use CodeIgniter\Database\ConnectionInterface;

class PenjualanService
{
    protected $riwayatTransaksi;
    protected $riwayatPiutang;
    protected $mutasiStock;
    protected $penjualan;
    protected $penjualanDetail;
    protected $stockGudang;
    protected $bukuBesar;
    protected $riwayatHP;
    protected $interface;
    protected $pelanggan;
    protected $salesman;
    /**
     * @var \CodeIgniter\Database\BaseConnection $db
     */
    protected $db;

    public function __construct(
        ModelPenjualan $penjualan,
        ModelPenjualanDetail $detail,
        ModelStockGudang $stock,
        ModelSetupBuku $buku,
        ModelRiwayatTransaksi $riwayat,
        ModelRiwayatPiutang $riwayatPiutang,
        ModelHutangPiutang $hutangPiutang,
        ModelMutasiStock $mutasiStock,
        ModelAntarmuka $interface,
        ModelSetuppelanggan $pelanggan,
        ModelSetupsalesman $salesman,
        /**
         * @var \CodeIgniter\Database\BaseConnection
         */
        ConnectionInterface $db
    ) {
        $this->penjualan = $penjualan;
        $this->penjualanDetail = $detail;
        $this->stockGudang = $stock;
        $this->bukuBesar = $buku;
        $this->riwayatTransaksi = $riwayat;
        $this->riwayatPiutang = $riwayatPiutang;
        $this->riwayatHP = $hutangPiutang;
        $this->mutasiStock = $mutasiStock;
        $this->interface = $interface;
        $this->pelanggan = $pelanggan;
        $this->salesman = $salesman;
        $this->db = $db;
    }

    public function save(array $headerData, array $detailData, $id = null): int
    {
        $this->db->transBegin();

        try {

            $idPenjualan = $id
                ? $this->updateHeader($id, $headerData)
                : $this->createHeader($headerData);


            $this->saveDetails($idPenjualan, $detailData, $headerData);

            $this->db->transCommit();
            return $idPenjualan;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error saving penjualan: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function createHeader(array $data): int
    {
        return $this->penjualan->insert($data);
    }

    protected function updateHeader($id, array $data): int
    {
        $this->penjualan->update($id, $data);
        return $id;
    }

    /**
     * Handle penghapusan detail penjualan dengan mengembalikan stok
     */
    protected function restoreStock(int $idDetail, int $locationId): void
    {
        $detail = $this->penjualanDetail->find($idDetail);

        if (!$detail || empty($detail->id_stock)) {
            return;
        }

        $stock = $this->stockGudang->where([
            'id_lokasi' => $locationId,
            'id_stock' => $detail->id_stock
        ])->first();

        if ($stock) {
            $normal_qty = $detail->qty1 * floatval($detail->conv_factor) + $detail->qty2;
            $normal_stock = $stock->qty1 * floatval($detail->conv_factor) + $stock->qty2;
            $new_normal_qty = $normal_stock + $normal_qty;

            $new_qty1 = floor($new_normal_qty / floatval($detail->conv_factor));
            $new_qty2 = $new_normal_qty % floatval($detail->conv_factor);

            // Kembalikan juga nilai jml_harga ke stok
            $new_jmlHarga = floatval($stock->jml_harga) + floatval($detail->jml_harga);

            $this->stockGudang->update($stock->id, [
                'qty1' => $new_qty1,
                'qty2' => $new_qty2,
                'jml_harga' => $new_jmlHarga
            ]);
        }
    }

    /**
     * Method untuk memverifikasi stok sebelum melakukan transaksi penjualan
     * @throws \Exception jika stok tidak mencukupi
     */
    protected function verifyStockAvailability(array $details, int $locationId): void
    {
        foreach ($details as $detail) {
            if (empty($detail['id_stock'])) continue;

            $stock = $this->stockGudang->where([
                'id_lokasi' => $locationId,
                'id_stock' => $detail['id_stock']
            ])->first();

            if (!$stock) {
                throw new \Exception("Stok dengan ID {$detail['id_stock']} tidak ditemukan di lokasi {$locationId}");
            }

            $new = $this->extractDetailValues($detail);
            $old = [];

            if (isset($detail['id_detail']) && !empty($detail['id_detail'])) {
                $old = $this->getExistingDetailValues($detail);
            }

            $stock_normal_qty = $stock->qty1 * $new['conv_factor'] + $stock->qty2;
            $old_normal_qty = isset($old['qty1']) ? $old['qty1'] * $new['conv_factor'] + $old['qty2'] : 0;
            $new_normal_qty = $new['normal_qty'];

            // Jika edit transaksi, kurangi yang lama kemudian tambahkan yang baru
            $needed_qty = $new_normal_qty - $old_normal_qty;

            if ($stock_normal_qty < $needed_qty) {
                $produk = $this->getProductName($detail['id_stock']);
                throw new \Exception("Stok {$produk} tidak mencukupi. Tersedia: {$stock_normal_qty}, dibutuhkan: {$needed_qty}");
            }
        }
    }

    /**
     * Helper untuk mendapatkan nama produk
     */
    private function getProductName(int $stockId): string
    {
        // Sesuaikan implementasi dengan model produk Anda
        $produk = $this->db->table('stock')->where('id_stock', $stockId)->get()->getRow();
        return $produk ? $produk->nama_stock : "Produk #{$stockId}";
    }

    protected function saveDetails(int $idPenjualan, array $newDetails, array $headerData)
    {
        // Verifikasi ketersediaan stok sebelum menyimpan detail
        $this->verifyStockAvailability($newDetails, $headerData['id_lokasi']);

        $existing = $this->penjualanDetail->where('id_penjualan', $idPenjualan)->findAll();
        $existingIds = array_column($existing, 'id');
        $incomingIds = array_column($newDetails, 'id_detail');

        // Hapus detail yang tidak ada dan kembalikan stok (Edit)
        foreach ($existing as $row) {
            if (!in_array($row->id, $incomingIds)) {
                $this->restoreStock($row->id, $headerData['id_lokasi']);
                $this->penjualanDetail->delete($row->id);

                // Hapus mutasi stok terkait
                $this->mutasiStock->where([
                    'id_stock' => $row->id_stock,
                    'id_lokasi' => $headerData['id_lokasi'],
                    'jenis' => 'keluar',
                    'sumber_transaksi' => 'penjualan',
                    'id_transaksi' => $idPenjualan
                ])->delete();
            }
        }

        foreach ($newDetails as $detail) {
            // Skip empty rows (where there's no stock ID)
            if (empty($detail['id_stock'])) continue;

            $detailPenjualan = new DetailItem($detail);
            // Create detail record
            $detailRecord = $detailPenjualan->getRecords();
            $detailRecord = array_merge($detailRecord, [
                'id_penjualan' => $idPenjualan,
            ]);

            if (isset($detail['id_detail']) && in_array($detail['id_detail'], $existingIds)) {
                $this->insertOrUpdatteMutasiStock($headerData, $detail, $idPenjualan);
                // Sync stock in stock1_gudang table
                $this->syncStockGudang($headerData, $detail);
                $this->penjualanDetail->update($detail['id_detail'], $detailRecord);
            } else {
                $this->insertOrUpdatteMutasiStock($headerData, $detail, $idPenjualan);
                // Sync stock in stock1_gudang table
                $this->syncStockGudang($headerData, $detail);
                $this->penjualanDetail->insert($detailRecord);
            }
        }

        if (isset($headerData['opsi_pembayaran'])) {
            if ($headerData['opsi_pembayaran'] === 'kredit') {
                $this->setPiutang($headerData, $idPenjualan);
            } elseif ($headerData['opsi_pembayaran'] === 'tunai') {
                $this->setPerubahanBukuBesar($headerData, $idPenjualan);
            }
        }
    }

    /**
     * Insert atau update mutasi stok berdasarkan detail pembelian
     * 
     * @param array $headerData Data header pembelian
     * @param array $detail Data detail pembelian
     * @return void
     */
    protected function insertOrUpdatteMutasiStock(array $headerData, array $detail, $idPenjualan): void
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
                'sumber_transaksi' => 'penjualan',
                'id_transaksi' => $idPenjualan
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
            'sumber_transaksi' => 'penjualan',
            'id_transaksi' => $idPenjualan
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
            $existingDetail = $this->penjualanDetail->find($detail['id_detail']);
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

        // Untuk penjualan, qty_diff dikalikan -1 karena mengurangi stok
        return [
            'qty_diff' => -1 * ($new['normal_qty'] - $old_normal_qty), // Negatif karena kita mengurangi stok
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

        if (!$existingStock) {
            throw new \Exception("Stok dengan ID {$stockId} tidak ditemukan di lokasi {$locationId}");
        }

        // Update stok yang sudah ada
        $old_qty1 = floatval($existingStock->qty1);
        $old_qty2 = floatval($existingStock->qty2);
        $old_jmlHarga = floatval($existingStock->jml_harga);

        // Konversi ke kuantitas normal, tambahkan perbedaan, lalu konversi kembali
        $normal_old_qty = $old_qty1 * $newValues['conv_factor'] + $old_qty2;
        $new_normal_qty = $normal_old_qty + $changes['qty_diff'];

        // Validasi stok cukup
        if ($new_normal_qty < 0) {
            $produk = $this->getProductName($stockId);
            throw new \Exception("Stok {$produk} tidak mencukupi untuk. Tersedia: {$normal_old_qty}");
        }

        $new_qty1 = floor($new_normal_qty / $newValues['conv_factor']);
        $new_qty2 = $new_normal_qty % $newValues['conv_factor'];

        $new_jmlHarga = $old_jmlHarga - $changes['price_diff'];

        $this->stockGudang->update($existingStock->id, [
            'qty1' => $new_qty1,
            'qty2' => $new_qty2,
            'jml_harga' => $new_jmlHarga,
        ]);
    }

    protected function getRekeningId(): Int
    {
        // Ambil rekening penjualan dari interface
        $kode_rekening = $this->interface->getKodeRekening('penjualan');
        if (!$kode_rekening) {
            throw new \Exception("Kode rekening untuk penjualan tidak ditemukan di antarmuka.");
        }
        $rekeningId = $this->bukuBesar->where('kode_setupbuku', $kode_rekening)->first();
        if (!$rekeningId) {
            throw new \Exception("Rekening dengan kode {$kode_rekening} tidak ditemukan.");
        }
        return $rekeningId->id_setupbuku;
    }

    /**
     * Set atau update perubahan buku besar untuk transaksi tunai
     * 
     * @param array $headerData Data header pembelian
     * @param int $idPenjualan ID pembelian
     * @param float $tunai Jumlah tunai
     * @return void
     */
    protected function setPerubahanBukuBesar(array $headerData, int $idPenjualan): void
    {
        // Cari riwayat transaksi kas masuk yang sudah ada (jika ada/ Edit)
        $kas_masuk = $this->riwayatTransaksi
            ->where('id_transaksi', $idPenjualan)
            ->like('jenis_transaksi', 'penjualan')
            ->first();

        $kredit_kas_masuk_lama = 0;
        $id_riwayat = null;

        // untuk edit transaksi
        if ($kas_masuk) {
            // Jika riwayat sudah ada, ambil nilai kredit lama dan ID riwayat
            $id_riwayat = $kas_masuk->id;
            $kredit_kas_masuk_lama = floatval($kas_masuk->kredit);
        }

        // Ambil data rekening dan update saldo
        $rekeningPenjualan = $this->getRekeningId();
        $dt_rekening = $this->bukuBesar->find($rekeningPenjualan);
        $old_saldo = floatval($dt_rekening->saldo_berjalan);

        // Untuk update: kembalikan dulu saldo lama (jumlah kredit lama)
        $current_saldo = $old_saldo - $kredit_kas_masuk_lama;

        // Lalu tambah dengan nilai tunai baru
        $new_saldo = $current_saldo + $headerData['grand_total'];

        // Update saldo rekening
        $this->bukuBesar->update($rekeningPenjualan, [
            'saldo_berjalan' => $new_saldo
        ]);

        $transaksiData = [
            'tanggal' => $headerData['tanggal'],
            'jenis_transaksi' => 'penjualan',
            'id_transaksi' => $idPenjualan,
            'nota' => $headerData['nota'],
            'id_rekening' => $rekeningPenjualan,
            'deskripsi' => 'Penjualan Tunai',
            'debit' => '0',
            'kredit' => $headerData['grand_total'],
            'saldo_setelah' => $new_saldo
        ];

        if ($id_riwayat) {
            // Update riwayat transaksi yang sudah ada
            $this->riwayatTransaksi->update($id_riwayat, $transaksiData);
        } else {
            // Buat riwayat transaksi baru
            $this->riwayatTransaksi->insert($transaksiData);
        }
    }

    /**
     * Set atau update hutang untuk transaksi
     * 
     * @param array $headerData Data header pembelian
     * @param int $idPenjualan ID pembelian
     * @param float $hutang Jumlah hutang
     * @return void
     */
    protected function setPiutang(array $headerData, int $idPenjualan): void
    {
        $this->handlePiutangRelasi(
            $idPenjualan,
            $headerData,
            $headerData['id_pelanggan'],
            'pelanggan'
        );

        $this->handlePiutangRelasi(
            $idPenjualan,
            $headerData,
            $headerData['id_salesman'],
            'salesman'
        );
    }

    private function handlePiutangRelasi(int $idPenjualan, array $headerData, int $relasiId, string $relasiType): void
    {
        // Cari data piutang yang sudah ada
        $dt_piutang_lama = $this->riwayatHP
            ->where([
                'id_transaksi' => $idPenjualan,
                'relasi_id' => $relasiId,
                'relasi_tipe' => $relasiType,
                'jenis' => 'piutang'
            ])
            ->first();

        $data_piutang = [
            'tanggal' => $headerData['tanggal'],
            'id_transaksi' => $idPenjualan,
            'nota' => $headerData['nota'],
            'tanggal_jt' => $headerData['tgl_jatuhtempo'],
            'saldo' => $headerData['grand_total'],
            'relasi_id' => $relasiId,
            'relasi_tipe' => $relasiType,
            'jenis' => 'piutang'
        ];

        $rekening_piutang_usaha = $this->bukuBesar->where('kode_setupbuku', $this->interface->getKodeRekening('piutang_dagang'))->first();

        // Model reference based on relation type
        $model = $relasiType === 'pelanggan' ? $this->pelanggan : $this->salesman;

        if ($dt_piutang_lama) {
            // Update data piutang yang sudah ada
            $piutang_masuk_lama = floatval($dt_piutang_lama->saldo);
            $this->riwayatHP->update($dt_piutang_lama->id_hutang_piutang, $data_piutang);

            // Update saldo relasi
            $dt_relasi = $model->find($relasiId);
            $old_saldo = floatval($dt_relasi->saldo);
            $current_saldo = $old_saldo - $piutang_masuk_lama; // Kembalikan dulu saldo lama
            $new_saldo = $current_saldo + $headerData['grand_total']; // Tambahkan dengan piutang baru

            $model->update($relasiId, [
                'saldo' => $new_saldo
            ]);

            $riwayat_lama = $this->riwayatPiutang->where([
                'id_transaksi' => $idPenjualan,
                'jenis_transaksi' => 'penjualan',
                'id_pelaku' => $relasiId,
                'pelaku' => $relasiType
            ])->first();

            if ($riwayat_lama) {
                // Update riwayat piutang yang sudah ada
                $this->riwayatPiutang->update($riwayat_lama->id, [
                    'debit' => $headerData['grand_total'],
                    'saldo_setelah' => $new_saldo,
                ]);
            }
        } else {
            // Insert data piutang baru
            $this->riwayatHP->insert($data_piutang);

            $dt_relasi = $model->find($relasiId);
            $old_saldo = floatval($dt_relasi->saldo);
            $new_saldo = $old_saldo + $headerData['grand_total'];

            $model->update($relasiId, [
                'saldo' => $new_saldo
            ]);

            // Insert riwayat piutang baru
            $this->riwayatPiutang->insert([
                'tanggal' => $headerData['tanggal'],
                'pelaku' => $relasiType,
                'id_transaksi' => $idPenjualan,
                'jenis_transaksi' => 'penjualan',
                'nota' => $headerData['nota'],
                'id_pelaku' => $relasiId,
                'debit' => $headerData['grand_total'],
                'kredit' => 0,
                'saldo_setelah' => $new_saldo,
                'deskripsi' => 'Piutang penjualan',
            ]);
        }
    }
}
