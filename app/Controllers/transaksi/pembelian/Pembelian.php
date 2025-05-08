<?php

namespace App\Controllers\transaksi\pembelian;

use App\Models\transaksi\pembelian\ModelPembelian;
use App\Models\transaksi\pembelian\ModelPembelianDetail;
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
use PhpParser\Node\Stmt\TryCatch;
use TCPDF;

class Pembelian extends ResourceController
{
    protected $objLokasi, $objSatuan, $objSetupbank, $objPembelian, $objSetupsupplier, $objStock, $db, $objAntarmuka, $objSetupBuku, $objPembelianDetail, $objStockGudang, $objRiwayatTransaksi;

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
        $this->objPembelianDetail = new ModelPembelianDetail();
        $this->db = \Config\Database::connect();

        $this->objRiwayatTransaksi = new ModelRiwayatTransaksi();
    }

    /**
     * Return an array of resource, themselves in array format.
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
        $data['dtpembelian'] = $this->objPembelian->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();

        return view('transaksi/pembelian_v/pembelian/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtpembelian'] = $this->objPembelian->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtpembelian'] = $this->objPembelian->getById($id);
            if (empty($data['dtpembelian'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }

        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('transaksi/pembelian_v/pembelian/printPDF', $data);
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
        $pdf->Output('nota_pembelian.pdf', 'D');
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
        // Ambil kode kas dan setara di interface
        $kodeKas = $this->objAntarmuka->getKodeKas();

        if ($kodeKas) {
            // pisah kodekas dengan koma
            $kodeKas = explode(',', $kodeKas);
        }

        // Ambil rekening dari buku besar berdasarkan kode kas
        $data['dtrekening'] = $this->objSetupBuku->getRekeningKas($kodeKas);

        // Buat Nota Otomatis

        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();

        return view('transaksi/pembelian_v/pembelian/new', $data);
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
            'TOP' => $this->request->getVar('TOP'),
            'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
            'tgl_invoice' => $this->request->getVar('tgl_invoice'),
            'no_invoice' => $this->request->getVar('no_invoice'),
            'id_lokasi' => $id_lokasi,
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
            'disc_cash' => $this->request->getVar('disc_cash'),
            'dpp' => $this->request->getVar('dpp_raw') ?? 0,
            'ppn' => $this->request->getVar('ppn') ?? 0,
            'ppn_option' => $this->request->getVar('ppn_option'),
            'tunai' => $this->request->getVar('tunai_raw') ?? 0,
            'sub_total' => $this->request->getVar('sub_total_raw') ?? 0,
            'grand_total' => $this->request->getVar('grand_total_raw') ?? 0,
            'hutang' => $this->request->getVar('hutang_raw') ?? 0,
        ];

        // Memulai transaksi
        $this->db->transBegin();

        try {
            // Menyimpan data header ke tabel pembelian1
            $idPembelian = $this->objPembelian->insert($headerData);

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
                        'id_pembelian' => $idPembelian,
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
                    $this->objPembelianDetail->insert($detailRecord);

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

                        $new_qty1 = $qty1 + $old_qty1;
                        $new_qty2 = $qty2 + $old_qty2;
                        if ($new_qty2 > $conv_factor) {
                            $new_qty1 += floor($new_qty2 / $conv_factor); // Add to qty1 if qty2 exceeds conversion factor
                            $new_qty2 = $new_qty2 % $conv_factor; // Remainder for qty2
                        }
                        $old_jmlHarga = floatval($existingStock->jml_harga);
                        $new_jmlHarga = $jmlHarga + $old_jmlHarga; // Update total harga

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
        $dtpembelian = $this->objPembelian->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtpembelian) {
            return redirect()->to(site_url('transaksi/pembelian/pembelian'))->with('error', 'Data tidak ditemukan');
        }


        // Ambil kode kas dan setara di interface
        $kodeKas = $this->objAntarmuka->getKodeKas();

        if ($kodeKas) {
            // pisah kodekas dengan koma
            $kodeKas = explode(',', $kodeKas);
        }

        // Ambil rekening dari buku besar berdasarkan kode kas
        $data['dtrekening'] = $this->objSetupBuku->getRekeningKas($kodeKas);

        // Ambil data detail berdasarkan ID
        $data['dtdetail'] = $this->objPembelianDetail->select('pembelian1_detail.*, stock1.conv_factor')
            ->join('stock1', 'pembelian1_detail.id_stock = stock1.id_stock', 'left')
            ->where('pembelian1_detail.id_pembelian', $id)
            ->findAll();


        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtheader'] = $dtpembelian;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        return view('transaksi/pembelian_v/pembelian/edit', $data);
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
        $existingData = $this->objPembelian->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('transaksi/pembelian/pembelian'))->with('error', 'Data tidak ditemukan');
        }


        $id_lokasi = $this->request->getVar('id_lokasi');

        // Mengambil data header
        $headerData = [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'TOP' => $this->request->getVar('TOP'),
            'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
            'tgl_invoice' => $this->request->getVar('tgl_invoice'),
            'no_invoice' => $this->request->getVar('no_invoice'),
            'id_lokasi' => $id_lokasi,
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
            'disc_cash' => $this->request->getVar('disc_cash'),
            'dpp' => $this->request->getVar('dpp_raw') ?? 0,
            'ppn' => $this->request->getVar('ppn') ?? 0,
            'ppn_option' => $this->request->getVar('ppn_option'),
            'tunai' => $this->request->getVar('tunai_raw') ?? 0,
            'sub_total' => $this->request->getVar('sub_total_raw') ?? 0,
            'grand_total' => $this->request->getVar('grand_total_raw') ?? 0,
            'hutang' => $this->request->getVar('hutang_raw') ?? 0,
        ];

        // Memulai transaksi
        $this->db->transBegin();

        try {
            // Menyimpan data header ke tabel pembelian1
            $this->objPembelian->update($id, $headerData);

            // Proses data detail (array)
            $detailData = $this->request->getVar('detail');

            // Ambil data detail yang ada di database untuk ID ini
            $existingDetailData = $this->objPembelianDetail->where('id_pembelian', $id)->findAll();
            // Hapus detail yang tidak ada di data baru
            foreach ($existingDetailData as $existingDetail) {
                $found = in_array($existingDetail->id, array_column($detailData, 'id_detail'));

                if (!$found) {
                    // Hapus detail yang ada di detabase yang tidak ada di data baru
                    $this->objPembelianDetail->delete($existingDetail->id);
                    // kurangi stock gudang dari detail yang dihapus
                    $this->objStockGudang->where(['id_lokasi' => $id_lokasi, 'id_stock' => $existingDetail->id_stock])
                        ->set([
                            'qty1' => 'qty1 - ' . $existingDetail->qty1,
                            'qty2' => 'qty2 - ' . $existingDetail->qty2,
                            'jml_harga' => 'jml_harga - ' . $existingDetail->jml_harga
                        ])
                        ->update();
                }
            }

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

                    // Get raw values if available, otherwise parse the formatted values
                    $jmlHarga = isset($item['jml_harga_raw']) ?
                        floatval($item['jml_harga_raw']) :
                        floatval(preg_replace('/[^\d]/', '', $item['jml_harga']));

                    $disc1Perc = isset($item['disc_1_perc']) ?
                        floatval($item['disc_1_perc']) : 0;
                    $disc1Rp = floatval($item['disc_1_rp_raw']);
                    $disc2Perc = isset($item['disc_2_perc']) ? floatval($item['disc_2_perc']) : 0;
                    $disc2Rp = floatval($item['disc_2_rp_raw']);


                    $total = isset($item['total_raw']) ?
                        floatval($item['total_raw']) :
                        floatval(preg_replace('/[^\d]/', '', $item['total']));

                    // Create detail record
                    $detailRecord = [
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

                    // cek kalau jumlah yang baru lebih kecil atau lebih besar dari yang lama, untuk update stock gudang
                    $old_qty1_detail = 0;
                    $old_qty2_detail = 0;
                    $old_jmlHarga_detail = 0;
                    if (isset($item['id_detail']) && !empty($item['id_detail'])) {
                        $existingDetail = $this->objPembelianDetail->find($item['id_detail']);
                        if ($existingDetail) {
                            $old_qty1_detail = floatval($existingDetail->qty1);
                            $old_qty2_detail = floatval($existingDetail->qty2);
                            $old_jmlHarga_detail = floatval($existingDetail->jml_harga);
                        }
                    }

                    // convert qty 1 dan qty 2 ke dalam satuan yang sama
                    $conv_factor = floatval($item['conv_factor']);
                    $normal_qty = $qty1 * $conv_factor + $qty2;
                    $old_normal_qty = $old_qty1_detail * $conv_factor + $old_qty2_detail;
                    $selisih_qty = $normal_qty - $old_normal_qty;
                    $selisih_jmlHarga = $jmlHarga - $old_jmlHarga_detail;

                    // Check if the detail record already exists
                    if (isset($item['id_detail']) && !empty($item['id_detail'])) {
                        // Update existing detail record
                        $this->objPembelianDetail->update($item['id_detail'], $detailRecord);
                    } else {
                        // Insert new detail record
                        $detailRecord['id_pembelian'] = $id; // Add id_pembelian for new records
                        $this->objPembelianDetail->insert($detailRecord);
                    }


                    // Check if stock already exists in stock1_gudang
                    $existingStock = $this->objStockGudang->where(['id_lokasi' => $id_lokasi, 'id_stock' => $id_stock])->first();

                    if ($existingStock) {
                        // Update existing stock
                        $old_qty1 = floatval($existingStock->qty1);
                        $old_qty2 = floatval($existingStock->qty2);
                        $conv_factor = floatval($item['conv_factor']);

                        $normal_old_qty = $old_qty1 * $conv_factor + $old_qty2;
                        $new_normal_qty = $normal_old_qty + $selisih_qty;

                        log_message('debug', 'Updating stock: ID=' . $existingStock->id .
                            ', old_normal_qty=' . $old_normal_qty .
                            ', new_normal_qty=' . $new_normal_qty);

                        $new_qty1 = floor($new_normal_qty / $conv_factor); // Update qty1
                        $new_qty2 = $new_normal_qty % $conv_factor; // Remainder for qty2

                        $old_jmlHarga = floatval($existingStock->jml_harga);
                        $new_jmlHarga = $old_jmlHarga + $selisih_jmlHarga; // Update total harga

                        log_message('debug', 'Updating stock: ID=' . $existingStock->id .
                            ', qty1=' . $new_qty1 .
                            ', qty2=' . $new_qty2 .
                            ', jml_harga=' . $new_jmlHarga);

                        $result = $this->objStockGudang->update($existingStock->id, [
                            'qty1' => $new_qty1,
                            'qty2' => $new_qty2,
                            'jml_harga' => $new_jmlHarga,
                        ]);

                        log_message('debug', 'Update result: ' . ($result ? 'success' : 'failed'));
                    } else {
                        // Insert new stock record

                        $stockData = [
                            'id_lokasi' => $id_lokasi,
                            'id_stock' => $id_stock,
                            'qty1' => $qty1,
                            'qty2' => $qty2,
                            'jml_harga' => $jmlHarga,
                        ];
                        $this->objStockGudang->insert($stockData);
                    }
                }
            }
            // Commit transaction if all went well
            $this->db->transCommit();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil diupdate!'
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
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $this->db->table('pembelian1')->where(['id_pembelian' => $id])->delete();
        return redirect()->to(site_url('transaksi/pembelian'))->with('Sukses', 'Data Berhasil Dihapus');
    }

    public function lookupStock()
    {
        $term = $this->request->getGet('term');

        $results = $this->objStock
            ->select('stock1.id_stock, stock1.kode, stock1.nama_barang, stock1.id_satuan, stock1.id_satuan2, stock1.conv_factor,
            sat1.kode_satuan as satuan_1,
            sat2.kode_satuan as satuan_2,
            harga1.harga_beli')
            ->join('satuan1 sat1', 'stock1.id_satuan = sat1.id_satuan', 'left')
            ->join('satuan1 sat2', 'stock1.id_satuan2 = sat2.id_satuan', 'left')
            ->join('harga1', 'stock1.id_stock = harga1.id_stock', 'right')
            ->groupStart()
            ->like('stock1.nama_barang', $term)
            ->orLike('stock1.kode', $term)
            ->groupEnd()
            ->limit(5)
            ->findAll();

        return $this->response->setJSON($results);
    }
}
