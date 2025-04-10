<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelSatuan;
use App\Models\ModelPenjualan;
use App\Models\ModelSetupsalesman;
use App\Models\ModelReturPenjualan;
use App\Models\ModelSetuppelanggan;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class ReturPenjualan extends ResourceController
{
    protected $objLokasi, $objSatuan, $objSetupsalesman, $objPenjualan, $objReturPenjualan,$objPelanggan, $db;
    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objSetupsalesman = new ModelSetupsalesman();
        $this->objPelanggan = new ModelSetuppelanggan();
        $this->objPenjualan = new ModelPenjualan();
        $this->objReturPenjualan = new ModelReturPenjualan();
        $this->db = \Config\Database::connect();
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
        }else{
            $data['is_closed'] = 'FALSE';
        }
         // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
         $data['dtreturpenjualan'] = $this->objReturPenjualan->getAll();
         $data['dtpelanggan'] = $this->objPelanggan->getAll();
         $data['dtlokasi'] = $this->objLokasi->getAll();
         $data['dtsatuan'] = $this->objSatuan->getAll();
         $data['dtsalesman'] = $this->objSetupsalesman->getAll();
         $data['dtpenjualan'] = $this->objPenjualan->getAll();
 
         return view('returpenjualan/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtreturpenjualan'] = $this->objReturPenjualan->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtreturpenjualan'] = $this->objReturPenjualan->getById($id);
            if (empty($data['dtreturpenjualan'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }
        
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->getAll();
        $data['dtpenjualan'] = $this->objPenjualan->getAll();
        $data['dtpelanggan'] = $this->objPelanggan->getAll();
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('returpenjualan/printPDF', $data);
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
        $pdf->Output('retur_penjualan.pdf', 'D');
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
        $data['dtreturpenjualan'] = $this->objReturPenjualan->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtpelanggan'] = $this->objPelanggan->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->getAll();
        $data['dtpenjualan'] = $this->objPenjualan->getAll();

        return view('returpenjualan/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
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

        // Simpan data ke database
        $data = [
            'tanggal'           => $this->request->getPost('tanggal'),
            'nota'              => $this->request->getPost('nota'),
            'id_pelanggan'      => $this->request->getPost('id_pelanggan'),
            'id_setupsalesman'  => $this->request->getPost('id_setupsalesman'),
            'id_lokasi'         => $this->request->getPost('id_lokasi'),
            'nama_stock'        => $this->request->getPost('nama_stock'),
            'id_satuan'         => $this->request->getPost('id_satuan'),
            'qty_1' => $qty_1,
            'qty_2' => $qty_2,
            'harga_satuan' => $harga_satuan,
            'jml_harga' => $jml_harga,
            'disc_1' => $disc_1,
            'disc_2' => $disc_2,
            'total' => $totalAfterDisc2,
            'id_penjualan_tgl'  => $this->request->getPost('id_penjualan_tgl'),
            'id_penjualan_nota' => $this->request->getPost('id_penjualan_nota'),
            'pembayaran'        => $this->request->getPost('pembayaran'),
            'tipe'              => $this->request->getPost('tipe'),
            'sub_total' => $sub_total,
            'disc_cash' => $disc_cash,
            'ppn' => $ppn,
            'grand_total' => $grand_total,
            'npwp' => $this->request->getVar('npwp'),
            'terbilang' => $this->request->getVar('terbilang'),
        ];
        $this->db->table('returpenjualan1')->insert($data);

        return redirect()->to(site_url('returpenjualan'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtreturpenjualan = $this->objReturPenjualan->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtreturpenjualan) {
            return redirect()->to(site_url('returpenjualan'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtreturpenjualan'] = $dtreturpenjualan;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtpelanggan'] = $this->objPelanggan->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->getAll();
        $data['dtpenjualan'] = $this->objPenjualan->getAll();
        return view('returpenjualan/edit', $data);
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
    $existingData = $this->objReturPenjualan->find($id);
    if (!$existingData) {
        return redirect()->to(site_url('returpenjualan'))->with('error', 'Data tidak ditemukan');
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

    // Ambil data yang diinputkan dari form
    $data = [
            'tanggal'           => $this->request->getPost('tanggal'),
            'nota'              => $this->request->getPost('nota'),
            'id_pelanggan'      => $this->request->getPost('id_pelanggan'),
            'id_setupsalesman'  => $this->request->getPost('id_setupsalesman'),
            'id_lokasi'         => $this->request->getPost('id_lokasi'),
            'nama_stock'        => $this->request->getPost('nama_stock'),
            'id_satuan'         => $this->request->getPost('id_satuan'),
            'qty_1' => $qty_1,
            'qty_2' => $qty_2,
            'harga_satuan' => $harga_satuan,
            'jml_harga' => $jml_harga,
            'disc_1' => $disc_1,
            'disc_2' => $disc_2,
            'total' => $totalAfterDisc2,
            'id_penjualan_tgl'  => $this->request->getPost('id_penjualan_tgl'),
            'id_penjualan_nota' => $this->request->getPost('id_penjualan_nota'),
            'pembayaran'        => $this->request->getPost('pembayaran'),
            'tipe'              => $this->request->getPost('tipe'),
            'sub_total' => $sub_total,
            'disc_cash' => $disc_cash,
            'ppn' => $ppn,
            'grand_total' => $grand_total,
            'npwp' => $this->request->getVar('npwp'),
            'terbilang' => $this->request->getVar('terbilang'),
    ];

    // Update data berdasarkan ID
    $this->objReturPenjualan->update($id, $data);

    return redirect()->to(site_url('returpenjualan'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('returpenjualan1')->where(['id_returpenjualan' => $id])->delete();
        return redirect()->to(site_url('returpenjualan'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
