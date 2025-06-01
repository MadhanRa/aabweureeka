<?php

namespace App\Controllers\transaksi\pembelian;

use App\Models\transaksi\pembelian\ModelPembelian;
use App\Models\transaksi\pembelian\ModelReturPembelian;
use App\Models\transaksi\pembelian\ModelPembelianDetail;
use App\Models\transaksi\pembelian\ModelReturPembelianDetail;
use App\Models\transaksi\ModelRiwayatTransaksi;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup\ModelAntarmuka;
use App\Models\setup\ModelSetupbank;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelSetupsupplier;

use App\ValueObjects\DetailItem;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class ReturPembelian extends ResourceController
{
    protected $objLokasi, $objSatuan, $objSetupbank, $objPembelian, $objSetupsupplier, $objStock, $db, $objAntarmuka, $objSetupBuku, $objPembelianDetail, $objStockGudang, $objRiwayatTransaksi, $objReturPembelian, $objReturPembelianDetail;

    protected $detailItemPembelian;

    function __construct()
    {
        // Setup
        $this->objAntarmuka = new ModelAntarmuka();
        $this->objSetupBuku = new ModelSetupBuku();
        $this->objSetupsupplier = new ModelSetupsupplier();
        $this->objSetupbank = new ModelSetupbank();

        // Setup Persediaan
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objStock = new ModelStock();
        $this->objStockGudang = new ModelStockGudang();

        // Transaksi Pembelian
        $this->objPembelian = new ModelPembelian();
        $this->objReturPembelian = new ModelReturPembelian();
        $this->objPembelianDetail = new ModelPembelianDetail();
        $this->objReturPembelianDetail = new ModelReturPembelianDetail();
        $this->db = \Config\Database::connect();

        $this->objRiwayatTransaksi = new ModelRiwayatTransaksi();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $month = date('m');
        $year = date('Y');

        if (!in_groups('admin')) {
            // Periksa apakah tutup buku periode bulan ini ada
            $cek = $this->db->table('closed_periods')->where('month', $month)->where('year', $year)->where('is_closed', 1)->get();
            $closeBookCheck = $cek->getResult();
            if ($closeBookCheck == TRUE) {
                $data['is_closed'] = 'TRUE';
            } else {
                $data['is_closed'] = 'FALSE';
            }
        } else {
            $data['is_closed'] = 'FALSE';
        }
        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtreturpembelian'] = $this->objReturPembelian->getAll();

        return view('transaksi/pembelian_v/returpembelian/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtreturpembelian'] = $this->objReturPembelian->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtreturpembelian'] = $this->objReturPembelian->getById($id);
            if (empty($data['dtreturpembelian'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }

        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtpembelian'] = $this->objPembelian->getAll();
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('transaksi/pembelian_v/returpembelian/printPDF', $data);
        // echo $html;
        // exit; // Jika perlu debugging

        // Buat PDF baru
        $pdf = new TCPDF('landscape', PDF_UNIT, 'A4', true, 'UTF-8', false);

        // Hapus header/footer default
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Tambah halaman baru
        $pdf->AddPage();

        // Cetak konten menggunakan WriteHTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Set tipe respons menjadi PDF
        $this->response->setContentType('application/pdf');
        $pdf->Output('retur_pembelian.pdf', 'D');
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {


        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtpembelian'] = $this->objPembelian->getAll();

        return view('transaksi/pembelian_v/returpembelian/new', $data);
    }

    protected function getHeaderDataFromRequest(): array
    {
        return [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'id_pembelian' => $this->request->getVar('id_pembelian'),
            'opsi_return' => $this->request->getVar('opsi_return'),
            'disc_cash' => $this->request->getVar('disc_cash') ?? 0,
            'disc_cash_rp' => $this->request->getVar('disc_cash_rp_raw') ?? 0,
            'dpp' => $this->request->getVar('dpp_raw') ?? 0,
            'ppn' => $this->request->getVar('ppn') ?? 0,
            'ppn_option' => $this->request->getVar('ppn_option'),
            'sub_total' => $this->request->getVar('sub_total_raw') ?? 0,
            'grand_total' => $this->request->getVar('grand_total_raw') ?? 0,
        ];
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
        log_message('debug', 'Old Detail Values: ' . json_encode($oldDetailValues));

        // 2. Ekstrak nilai baru dari detail
        $newValues = $this->extractDetailValues($detail);
        log_message('debug', 'New Detail Values: ' . json_encode($newValues));

        // 3. Hitung perubahan dalam kuantitas normal dan harga
        $changes = $this->calculateChanges($oldDetailValues, $newValues);
        log_message('debug', 'Changes: ' . json_encode($changes));

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
            $existingDetail = $this->objReturPembelianDetail->find($detail['id_detail']);
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
        $existingStock = $this->objStockGudang->where([
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
            $new_normal_qty = $normal_old_qty + $changes['qty_diff'];

            $new_qty1 = floor($new_normal_qty / $newValues['conv_factor']);
            $new_qty2 = $new_normal_qty % $newValues['conv_factor'];
            $new_jmlHarga = $old_jmlHarga + $changes['price_diff'];

            $this->objStockGudang->update($existingStock->id, [
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

            $this->objStockGudang->insert($stockData);
        }
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $headerData = $this->getHeaderDataFromRequest();

        // Memulai transaksi
        $this->db->transBegin();

        try {
            // Menyimpan data header ke tabel returpembelian1
            $idReturPembelian = $this->objReturPembelian->insert($headerData);

            // Proses data detail (array)
            $detailData = $this->request->getVar('detail');

            if (!empty($detailData) && is_array($detailData)) {
                foreach ($detailData as $key => $item) {
                    // Skip empty rows (where there's no stock ID)
                    if (empty($item['id_stock'])) {
                        continue;
                    }

                    $detailRetur = new DetailItem($item);
                    $detailRecord = $detailRetur->getRecords();
                    $detailRecord = array_merge($detailRecord, [
                        'id_returpembelian' => $idReturPembelian,
                    ]);

                    // Sinkronisasi stok gudang
                    $this->syncStockGudang($headerData, $item);

                    // Insert detail
                    $this->objReturPembelianDetail->insert($detailRecord);
                }

                if ($headerData['opsi_return'] == 'tunai') {
                    // Ambil data rekening dan update saldo
                    $dt_rekening = $this->objSetupBuku->find($headerData['id_setupbuku']);
                    $old_saldo = floatval($dt_rekening->saldo_berjalan);

                    $returValue = $headerData['grand_total'];
                    $new_saldo = $old_saldo + $returValue;

                    // Update saldo rekening
                    $this->objSetupBuku->update($headerData['id_setupbuku'], [
                        'saldo_berjalan' => $new_saldo,
                    ]);
                    // Simpan riwayat transaksi
                    $this->objRiwayatTransaksi->insert([
                        'tanggal' => $headerData['tanggal'],
                        'jenis_transaksi' => 'retur pembelian',
                        'id_transaksi' => $idReturPembelian,
                        'nota' => $headerData['nota'],
                        'id_rekening' => $headerData['id_setupbuku'],
                        'deskripsi' => 'Retur Pembelian Tunai',
                        'debit' => $returValue,
                        'kredit' => 0,
                        'saldo_setelah' => $new_saldo,
                    ]);
                }
            }
            // Commit transaction if all went well
            $this->db->transCommit();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil disimpan!',
                'redirect_url' => site_url('transaksi/pembelian/returpembelian')
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setJSON([
                'status' => 'false',
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        // Cek apakah pengguna memiliki peran admin
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }

        // Ambil data berdasarkan ID
        $dtreturpembelian = $this->objReturPembelian->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtreturpembelian) {
            return redirect()->to(site_url('returpembelian'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtreturpembelian'] = $dtreturpembelian;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtpembelian'] = $this->objPembelian->getAll();
        return view('transaksi/pembelian_v/returpembelian/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        // Cek apakah pengguna memiliki peran admin
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }

        // Cek apakah data dengan ID yang diberikan ada di database
        $existingData = $this->objReturPembelian->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('returpembelian'))->with('error', 'Data tidak ditemukan');
        }

        // Ambil nilai dari form dan pastikan menjadi angka
        $qty_1 = floatval($this->request->getVar('qty_1'));
        $qty_2 = floatval($this->request->getVar('qty_2'));  // Ambil qty_2
        $harga_satuan = floatval($this->request->getVar('harga_satuan'));
        $disc_1 = floatval($this->request->getVar('disc_1'));
        $disc_2 = floatval($this->request->getVar('disc_2'));
        $disc_cash = floatval($this->request->getVar('disc_cash'));
        $ppn = floatval($this->request->getVar('ppn'));

        // Hitung jml_harga
        $jml_harga = (($qty_1 + $qty_2) * $harga_satuan);  // Menghitung harga total berdasarkan qty_1, qty_2, dan harga_satuan

        // Hitung diskon bertingkat
        $totalAfterDisc1 = $jml_harga - (($jml_harga * $disc_1) / 100);  // Diskon pertama
        $totalAfterDisc2 = $totalAfterDisc1 - (($totalAfterDisc1 * $disc_2) / 100);  // Diskon kedua

        // Menghitung sub_total setelah diskon cash
        $sub_total = $totalAfterDisc2 - (($totalAfterDisc2 * $disc_cash) / 100);

        // Menghitung grand total setelah PPN
        $grand_total = $sub_total + (($sub_total * $ppn) / 100);

        // Menyusun data untuk disimpan
        $data = [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'nama_stock' => $this->request->getVar('nama_stock'),
            'id_satuan' => $this->request->getVar('id_satuan'),
            'qty_1' => $qty_1,
            'qty_2' => $qty_2,
            'harga_satuan' => $harga_satuan,
            'jml_harga' => $jml_harga,
            'disc_1' => $disc_1,
            'disc_2' => $disc_2,
            'total' => $totalAfterDisc2,
            'id_pembelian_tgl' => $this->request->getVar('id_pembelian_tgl'),
            'id_pembelian_nota' => $this->request->getVar('id_pembelian_nota'),
            'pembayaran' => $this->request->getVar('pembayaran'),
            'tipe' => $this->request->getVar('tipe'),
            'sub_total' => $sub_total,
            'disc_cash' => $disc_cash,
            'ppn' => $ppn,
            'grand_total' => $grand_total,
            'npwp' => $this->request->getVar('npwp'),
            'terbilang' => $this->request->getVar('terbilang'),
        ];

        // Update data berdasarkan ID
        $this->objReturPembelian->update($id, $data);

        return redirect()->to(site_url('returpembelian'))->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $this->db->table('returpembelian1')->where(['id_returpembelian' => $id])->delete();
        return redirect()->to(site_url('returpembelian'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
