<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelPenjualan;
use App\Models\ModelSatuan;
use App\Models\setup\ModelSetuppelanggan;
use App\Models\setup\ModelSetupsalesman;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class Penjualan extends ResourceController
{
    protected $objLokasi;
    protected $objSatuan;
    protected $objSetupsalesman;
    protected $objPenjualan;
    protected $objSetuppelanggan;
    protected $db;

    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objSetupsalesman = new ModelSetupsalesman();
        $this->objPenjualan = new ModelPenjualan();
        $this->objSetuppelanggan = new ModelSetuppelanggan();
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
        } else {
            $data['is_closed'] = 'FALSE';
        }
        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtpenjualan'] = $this->objPenjualan->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtpelanggan'] = $this->objSetuppelanggan->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->getAll();

        return view('penjualan/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtpenjualan'] = $this->objPenjualan->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtpenjualan'] = $this->objPenjualan->getById($id);
            if (empty($data['dtpenjualan'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }

        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtpelanggan'] = $this->objSetuppelanggan->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->getAll();
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('penjualan/printPDF', $data);
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
        $pdf->Output('nota_penjualan.pdf', 'D');
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
        $data['dtpenjualan'] = $this->objPenjualan->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtpelanggan'] = $this->objSetuppelanggan->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->getAll();

        return view('penjualan/new', $data);
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

        // Siapkan data untuk disimpan ke database
        $data = [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_pelanggan' => $this->request->getVar('id_pelanggan'),
            'TOP' => $this->request->getVar('TOP'),
            'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
            'id_setupsalesman' => $this->request->getVar('id_setupsalesman'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'no_fp' => $this->request->getVar('no_fp'),
            'nama_stock' => $this->request->getVar('nama_stock'),
            'id_satuan' => $this->request->getVar('id_satuan'),
            'qty_1' => $qty_1,
            'qty_2' => $qty_2,
            'harga_satuan' => $harga_satuan,
            'jml_harga' => $jml_harga,
            'disc_1' => $disc_1,
            'disc_2' => $disc_2,
            'total' => $totalAfterDisc2,
            'pembayaran' => $this->request->getVar('pembayaran'),
            'tipe' => $this->request->getVar('tipe'),
            'sub_total' => $sub_total,
            'disc_cash' => $disc_cash,
            'ppn' => $ppn,
            'grand_total' => $grand_total,
            'npwp' => $this->request->getVar('npwp'),
            'terbilang' => $this->request->getVar('terbilang'),
        ];
        $this->db->table('penjualan1')->insert($data);

        return redirect()->to(site_url('penjualan'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtpenjualan = $this->objPenjualan->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtpenjualan) {
            return redirect()->to(site_url('pembelian'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtpenjualan'] = $dtpenjualan;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtpelanggan'] = $this->objSetuppelanggan->getAll();
        $data['dtsalesman'] = $this->objSetupsalesman->getAll();
        return view('penjualan/edit', $data);
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
        $existingData = $this->objPenjualan->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('penjualan'))->with('error', 'Data tidak ditemukan');
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

        // Siapkan data untuk disimpan ke database
        $data = [
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_pelanggan' => $this->request->getVar('id_pelanggan'),
            'TOP' => $this->request->getVar('TOP'),
            'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
            'id_setupsalesman' => $this->request->getVar('id_setupsalesman'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'no_fp' => $this->request->getVar('no_fp'),
            'nama_stock' => $this->request->getVar('nama_stock'),
            'id_satuan' => $this->request->getVar('id_satuan'),
            'qty_1' => $qty_1,
            'qty_2' => $qty_2,
            'harga_satuan' => $harga_satuan,
            'jml_harga' => $jml_harga,
            'disc_1' => $disc_1,
            'disc_2' => $disc_2,
            'total' => $totalAfterDisc2,
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
        $this->objPenjualan->update($id, $data);

        return redirect()->to(site_url('penjualan'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('penjualan1')->where(['id_penjualan' => $id])->delete();
        return redirect()->to(site_url('penjualan'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
