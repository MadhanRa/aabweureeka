<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelPembelian;
use App\Models\ModelSatuan;
use App\Models\ModelSetupbank;
use App\Models\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class Pembelian extends ResourceController
{
    protected $objLokasi, $objSatuan, $objSetupbank, $objPembelian, $objSetupsupplier, $db;

    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objSetupsupplier = new ModelSetupsupplier();
        $this->objPembelian = new ModelPembelian();
        $this->objSetupbank = new ModelSetupbank();
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
        $data['dtpembelian'] = $this->objPembelian->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();

        return view('pembelian/index', $data);
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
        $html = view('pembelian/printPDF', $data);
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
        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtpembelian'] = $this->objPembelian->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();

        return view('pembelian/new', $data);
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

        // Menyusun data untuk disimpan
        $data = [
            'id_pembelian' => $this->request->getVar('id_pembelian'),
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'TOP' => $this->request->getVar('TOP'),
            'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
            'tgl_invoice' => $this->request->getVar('tgl_invoice'),
            'no_invoice' => $this->request->getVar('no_invoice'),
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
            'id_setupbank' => $this->request->getVar('id_setupbank'),
            'tipe' => $this->request->getVar('tipe'),
            'sub_total' => $sub_total,
            'disc_cash' => $disc_cash,
            'ppn' => $ppn,
            'grand_total' => $grand_total,
            'npwp' => $this->request->getVar('npwp'),
            'terbilang' => $this->request->getVar('terbilang'),
        ];
        $this->db->table('pembelian1')->insert($data);

        return redirect()->to(site_url('pembelian'))->with('Sukses', 'Data Berhasil Disimpan');
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
            return redirect()->to(site_url('pembelian'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtpembelian'] = $dtpembelian;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        return view('pembelian/edit', $data);
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
            return redirect()->to(site_url('pembelian'))->with('error', 'Data tidak ditemukan');
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
            'id_pembelian' => $this->request->getVar('id_pembelian'),
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'TOP' => $this->request->getVar('TOP'),
            'tgl_jatuhtempo' => $this->request->getVar('tgl_jatuhtempo'),
            'tgl_invoice' => $this->request->getVar('tgl_invoice'),
            'no_invoice' => $this->request->getVar('no_invoice'),
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
            'id_setupbank' => $this->request->getVar('id_setupbank'),
            'tipe' => $this->request->getVar('tipe'),
            'sub_total' => $sub_total,
            'disc_cash' => $disc_cash,
            'ppn' => $ppn,
            'grand_total' => $grand_total,
            'npwp' => $this->request->getVar('npwp'),
            'terbilang' => $this->request->getVar('terbilang'),
        ];

        // Update data berdasarkan ID
        $this->objPembelian->update($id, $data);

        return redirect()->to(site_url('pembelian'))->with('success', 'Data berhasil diupdate.');
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
        return redirect()->to(site_url('pembelian'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
