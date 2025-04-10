<?php

namespace App\Controllers;

use App\Models\ModelAntarmuka;
use App\Models\ModelMutasiKasBank;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class MutasiKasBank extends ResourceController
{
    protected $objMutasiKasBank;
    protected $objAntarmuka;
    protected $db;
    
    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objMutasiKasBank = new ModelMutasiKasBank();
       $this->objAntarmuka = new ModelAntarmuka();
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
        $data['dtmutasikasbank'] = $this->objMutasiKasBank->getAll();
        $data['dtinterface'] = $this->objAntarmuka->findAll();
        return view('mutasikasbank/index', $data);
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
        $data['dtmutasikasbank'] = $this->objMutasiKasBank->getAll();
        $data['dtinterface'] = $this->objAntarmuka->findAll();
        return view('mutasikasbank/new', $data);
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
            'id_mutasikasbank'    => $this->request->getVar('id_mutasikasbank'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'id_interface'      => $this->request->getVar('id_interface'),
            'rekening'      => $this->request->getVar('rekening'),
            'b_pembantu'      => $this->request->getVar('b_pembantu'),
            'nama_rekening'             => $this->request->getVar('nama_rekening'),
            'nama_bpembantu'   => $this->request->getVar('nama_bpembantu'),
            'no_ref'            => $this->request->getVar('no_ref'),
            'debet'              => $this->request->getVar('debet'),
            'kredit'              => $this->request->getVar('kredit'),
            'mutasi'             => $this->request->getVar('mutasi'),
            'tgl_nota'           => $this->request->getVar('tgl_nota'),
            'keterangan'        => $this->request->getVar('keterangan'),
            
            
        ];
        $this->db->table('mutasikasbank1')->insert($data);

        return redirect()->to(site_url('mutasikasbank'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtmutasikasbank = $this->objMutasiKasBank->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtmutasikasbank) {
            return redirect()->to(site_url('mutasikasbank'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtmutasikasbank'] = $dtmutasikasbank;
        $data['dtinterface'] = $this->objAntarmuka->findAll();
        return view('mutasikasbank/edit', $data);
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
        $existingData = $this->objMutasiKasBank->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('mutasikasbank'))->with('error', 'Data tidak ditemukan');
        }

        // Ambil data yang diinputkan dari form
        $data = [
            'id_mutasikasbank'    => $this->request->getVar('id_mutasikasbank'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'id_interface'      => $this->request->getVar('id_interface'),
            'rekening'      => $this->request->getVar('rekening'),
            'b_pembantu'      => $this->request->getVar('b_pembantu'),
            'nama_rekening'             => $this->request->getVar('nama_rekening'),
            'nama_bpembantu'   => $this->request->getVar('nama_bpembantu'),
            'no_ref'            => $this->request->getVar('no_ref'),
            'debet'              => $this->request->getVar('debet'),
            'kredit'              => $this->request->getVar('kredit'),
            'mutasi'             => $this->request->getVar('mutasi'),
            'tgl_nota'           => $this->request->getVar('tgl_nota'),
            'keterangan'        => $this->request->getVar('keterangan'),
        ];

        // Update data berdasarkan ID
        $this->objMutasiKasBank->update($id, $data);

        return redirect()->to(site_url('mutasikasbank'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('mutasikasbank1')->where(['id_mutasikasbank' => $id])->delete();
        return redirect()->to(site_url('mutasikasbank'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
