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
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class ReturPembelian extends ResourceController
{
    protected $objLokasi, $objSatuan, $objSetupbank, $objPembelian, $objSetupsupplier, $objStock, $db, $objAntarmuka, $objSetupBuku, $objPembelianDetail, $objStockGudang, $objRiwayatTransaksi, $objReturPembelian, $objReturPembelianDetail;

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

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $id_lokasi = $this->request->getVar('id_lokasi');

        // Mengambil data header
        $headerData = [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'id_lokasi' => $id_lokasi,
            'id_pembelian' => $this->request->getVar('id_pembelian'),
            'opsi_return' => $this->request->getVar('opsi_return'),
            'disc_cash' => $this->request->getVar('disc_cash'),
            'dpp' => $this->request->getVar('dpp_raw') ?? 0,
            'ppn' => $this->request->getVar('ppn') ?? 0,
            'ppn_option' => $this->request->getVar('ppn_option'),
            'sub_total' => $this->request->getVar('sub_total_raw') ?? 0,
            'grand_total' => $this->request->getVar('grand_total_raw') ?? 0,
        ];

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

                    $id_stock = $item['id_stock'];

                    // Convert formatted values to raw numbers if needed
                    $qty1 = floatval($item['qty1']);
                    $qty2 = floatval($item['qty2']);
                    $hargaSatuan = isset($item['harga_satuan_raw']) ?
                        floatval($item['harga_satuan_raw']) :
                        floatval(preg_replace('/[^\d]/', '', $item['harga_satuan']));
                    $disc1Perc = isset($item['disc_1_perc']) ?
                        floatval($item['disc_1_perc']) : 0;
                    $disc1Rp = floatval($item['disc_1_rp_raw']);
                    $disc2Perc = isset($item['disc_2_perc']) ? floatval($item['disc_2_perc']) : 0;
                    $disc2Rp = floatval($item['disc_2_rp_raw']);

                    // Get raw values if available, otherwise parse the formatted values
                    $jmlHarga = isset($item['jml_harga_raw']) ?
                        floatval($item['jml_harga_raw']) :
                        floatval(preg_replace('/[^\d]/', '', $item['jml_harga']));

                    $total = isset($item['total_raw']) ?
                        floatval($item['total_raw']) :
                        floatval(preg_replace('/[^\d]/', '', $item['total']));

                    // Create detail record
                    $detailRecord = [
                        'id_returpembelian' => $idReturPembelian,
                        'id_stock' => $id_stock,
                        'kode' => $item['kode'],
                        'nama_barang' => $item['nama_barang'],
                        'satuan' => $item['satuan'],
                        'qty1' => $qty1,
                        'qty2' => $qty2,
                        'harga_satuan' => $hargaSatuan,
                        'jml_harga' => $jmlHarga,
                        'disc_1_perc' => $disc1Perc,
                        'disc_1_rp' => $disc1Rp,
                        'disc_2_perc' => $disc2Perc,
                        'disc_2_rp' => $disc2Rp,
                        'total' => $total
                    ];

                    // Insert detail
                    $this->objReturPembelianDetail->insert($detailRecord);

                    // Update stock in stock1_gudang table
                    $stockData = [
                        'id_lokasi' => $id_lokasi,
                        'id_stock' => $id_stock,
                        'qty1' => $qty1,
                        'qty2' => $qty2,
                        'jml_harga' => $jmlHarga,
                    ];

                    // Check if stock already exists in stock1_gudang
                    $existingStock = $this->objStockGudang->where(['id_lokasi' => $id_lokasi, 'id_stock' => $id_stock])->first();

                    if ($existingStock) {
                        // Update existing stock
                        $old_qty1 = floatval($existingStock->qty1);
                        $old_qty2 = floatval($existingStock->qty2);
                        $conv_factor = floatval($item['conv_factor']);

                        $normal_old_qty = $old_qty1 * $conv_factor + $old_qty2; // Normalized old quantity
                        $normal_new_qty = $qty1 * $conv_factor + $qty2; // Normalized new quantity

                        $new_qty = $normal_old_qty - $normal_new_qty; // Calculate new quantity
                        $new_qty1 = floor($new_qty / $conv_factor); // Calculate new qty1
                        $new_qty2 = $new_qty % $conv_factor; // Calculate new qty2

                        $old_jmlHarga = floatval($existingStock->jml_harga);
                        $new_jmlHarga = $old_jmlHarga - $jmlHarga; // Update total harga

                        $this->objStockGudang->update($existingStock->id, [
                            'qty1' => $new_qty1,
                            'qty2' => $new_qty2,
                            'jml_harga' => $new_jmlHarga,
                        ]);
                    } else {
                        // Insert new stock record
                        $this->objStockGudang->insert($stockData);
                    }
                }
            }
            // Commit transaction if all went well
            $this->db->transCommit();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil disimpan!'
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
