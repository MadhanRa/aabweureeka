<?php

namespace App\Controllers;

use App\Models\ModelPelunasanHutang;
use App\Models\ModelSetupbank;
use App\Models\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF; 

class PelunasanHutang extends ResourceController
{
    protected $objSetupsupplier;
    protected $objSetupbank;
    protected $objPelunasanHutang;
    protected $db;
    
    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objSetupsupplier = new ModelSetupsupplier();
       $this->objSetupbank = new ModelSetupbank();
       $this->objPelunasanHutang = new ModelPelunasanHutang();
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
        $data['dtpelunasanhutang'] = $this->objPelunasanHutang->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('pelunasanhutang/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtpelunasanhutang'] = $this->objPelunasanHutang->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtpelunasanhutang'] = $this->objPelunasanHutang->getById($id);
            if (empty($data['dtpelunasanhutang'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }
    
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('pelunasanhutang/printPDF', $data);
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
        $pdf->Output('pelunasan_hutang.pdf', 'I');
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
        $data['dtpelunasanhutang'] = $this->objPelunasanHutang->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('pelunasanhutang/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        // Ambil nilai dari form dan pastikan menjadi angka
        $saldo = floatval($this->request->getVar('saldo'));
        $nilai_pelunasan = floatval($this->request->getVar('nilai_pelunasan'));
        $diskon = floatval($this->request->getVar('diskon'));
    
        // Hitung diskon sebagai persentase dari nilai pelunasan
        $diskon_amount = ($diskon / 100) * $nilai_pelunasan;
    
        // Kalkulasi sisa sesuai logika yang diterapkan pada JavaScript
        $sisa = $saldo - $nilai_pelunasan + $diskon_amount;
 
        $data = [
            'id_lunashutang'    => $this->request->getVar('id_lunashutang'),
            'nota'              => $this->request->getVar('nota'),
            'id_setupsupplier'      => $this->request->getVar('id_setupsupplier'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'id_setupbank'      => $this->request->getVar('id_setupbank'),
            'saldo'             => $saldo,
            'nilai_pelunasan'   => $nilai_pelunasan,
            'diskon'            => $diskon,
            'pdpt'              => $this->request->getVar('pdpt'),
            'sisa'              => $sisa, 
            'keterangan'        => $this->request->getVar('keterangan'),
            
            
        ];
        $this->db->table('pelunasanhutang1')->insert($data);

        return redirect()->to(site_url('pelunasanhutang'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtpelunasanhutang = $this->objPelunasanHutang->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtpelunasanhutang) {
            return redirect()->to(site_url('pelunasanhutang'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtpelunasanhutang'] = $dtpelunasanhutang;
        $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        return view('pelunasanhutang/edit', $data);
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
        $existingData = $this->objPelunasanHutang->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('pelunasanhutang'))->with('error', 'Data tidak ditemukan');
        }

        // Ambil nilai dari form dan pastikan menjadi angka
        $saldo = floatval($this->request->getVar('saldo'));
        $nilai_pelunasan = floatval($this->request->getVar('nilai_pelunasan'));
        $diskon = floatval($this->request->getVar('diskon'));
    
        // Hitung diskon sebagai persentase dari nilai pelunasan
        $diskon_amount = ($diskon / 100) * $nilai_pelunasan;
    
        // Kalkulasi sisa sesuai logika yang diterapkan pada JavaScript
        $sisa = $saldo - $nilai_pelunasan + $diskon_amount;

        // Ambil data yang diinputkan dari form
        $data = [
            'id_lunashutang'    => $this->request->getVar('id_lunashutang'),
            'nota'              => $this->request->getVar('nota'),
            'id_setupsupplier'  => $this->request->getVar('id_setupsupplier'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'id_setupbank'      => $this->request->getVar('id_setupbank'),
            'saldo'             => $saldo,
            'nilai_pelunasan'   => $nilai_pelunasan,
            'diskon'            => $diskon,
            'pdpt'              => $this->request->getVar('pdpt'),
            'sisa'              => $sisa, 
            'keterangan'        => $this->request->getVar('keterangan'),
        ];

        // Update data berdasarkan ID
        $this->objPelunasanHutang->update($id, $data);

        return redirect()->to(site_url('pelunasanhutang'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('pelunasanhutang1')->where(['id_lunashutang' => $id])->delete();
        return redirect()->to(site_url('pelunasanhutang'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
