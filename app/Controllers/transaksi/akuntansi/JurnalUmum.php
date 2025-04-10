<?php

namespace App\Controllers;

use App\Models\ModelJurnalUmum;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class JurnalUmum extends ResourceController
{
    protected $objJurnalUmum;
    protected $db;

    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objJurnalUmum = new ModelJurnalUmum();
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
        $data['dtjurnalumum'] = $this->objJurnalUmum->findAll();
        return view('jurnalumum/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtjurnalumum'] = $this->objJurnalUmum->findAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtjurnalumum'] = $this->objJurnalUmum->getById($id);
            if (empty($data['dtjurnalumum'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }
        
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('jurnalumum/printPDF', $data);
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
        $pdf->Output('transaksi_jurnalumum.pdf', 'I');
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
        $data['dtjurnalumum'] = $this->objJurnalUmum->findAll();
        return view('jurnalumum/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $data = $this->request->getPost();
        $data = [
            'id_jurnalumum'    => $this->request->getVar('id_jurnalumum'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'rekening'      => $this->request->getVar('rekening'),
            'b_pembantu' => $this->request->getVar('b_pembantu'),
            'nama_rekening'             => $this->request->getVar('nama_rekening'),
            'nama_bpembantu'   => $this->request->getVar('nama_bpembantu'),
            'no_ref'            => $this->request->getVar('no_ref'),
            'debet'              => $this->request->getVar('debet'),
            'kredit'              => $this->request->getVar('kredit'),
            'tgl_nota'           => $this->request->getVar('tgl_nota'),
            'keterangan'        => $this->request->getVar('keterangan'),
            
            
        ];
        $this->db->table('jurnalumum1')->insert($data);

        return redirect()->to(site_url('jurnalumum'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtjurnalumum = $this->objJurnalUmum->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtjurnalumum) {
            return redirect()->to(site_url('bahansablon'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtjurnalumum'] = $dtjurnalumum;
        return view('jurnalumum/edit', $data);
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
    $existingData = $this->objJurnalUmum->find($id);
    if (!$existingData) {
        return redirect()->to(site_url('jurnalumum'))->with('error', 'Data tidak ditemukan');
    }

    // Ambil data yang diinputkan dari form
    $data = [
        'id_jurnalumum'    => $this->request->getVar('id_jurnalumum'),
        'tanggal'           => $this->request->getVar('tanggal'),
        'nota'              => $this->request->getVar('nota'),
        'rekening'      => $this->request->getVar('rekening'),
        'b_pembantu'      => $this->request->getVar('b_pembantu'),
        'nama_rekening'             => $this->request->getVar('nama_rekening'),
        'nama_bpembantu'   => $this->request->getVar('nama_bpembantu'),
        'no_ref'            => $this->request->getVar('no_ref'),
        'debet'              => $this->request->getVar('debet'),
        'kredit'              => $this->request->getVar('kredit'),
        'tgl_nota'           => $this->request->getVar('tgl_nota'),
        'keterangan'        => $this->request->getVar('keterangan'),
    ];

    // Update data berdasarkan ID
    $this->objJurnalUmum->update($id, $data);

    return redirect()->to(site_url('jurnalumum'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('jurnalumum1')->where(['id_jurnalumum' => $id])->delete();
        return redirect()->to(site_url('jurnalumum'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
