<?php

namespace App\Controllers;

use App\Models\ModelAntarmuka;
use App\Models\ModelKasKecil;
use App\Models\ModelKelompokproduksi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class KasKecil extends ResourceController
{
    protected $objKelompokproduksi;
    protected $objKasKecil;
    protected $objAntarmuka;
    protected $db;
    
    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objKasKecil = new ModelKasKecil();
       $this->objKelompokproduksi = new ModelKelompokproduksi();
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
        $data['dtkaskecil'] = $this->objKasKecil->getAll();
        $data['dtkelompokproduksi'] = $this->objKelompokproduksi->findAll();
        $data['dtantarmuka'] = $this->objAntarmuka->findAll();
        return view('kaskecil/index', $data);
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
        $data['dtkaskecil'] = $this->objKasKecil->getAll();
        $data['dtkelompokproduksi'] = $this->objKelompokproduksi->findAll();
        $data['dtantarmuka'] = $this->objAntarmuka->findAll();
        return view('kaskecil/new', $data);
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
            'id_kaskecil'    => $this->request->getVar('id_kaskecil'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'id_interface'      => $this->request->getVar('id_interface'),
            'id_kelproduksi'      => $this->request->getVar('id_kelproduksi'),
            'rekening'             => $this->request->getVar('rekening'),
            'b_pembantu'   => $this->request->getVar('b_pembantu'),
            'nama_rekening'            => $this->request->getVar('nama_rekening'),
            'nama_bpembantu'              => $this->request->getVar('nama_bpembantu'),
            'rp'              => $this->request->getVar('rp'),
            'keterangan'        => $this->request->getVar('keterangan'),
            
            
        ];
        $this->db->table('kaskecil1')->insert($data);

        return redirect()->to(site_url('kaskecil'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtkaskecil = $this->objKasKecil->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtkaskecil) {
            return redirect()->to(site_url('kaskecil'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtkaskecil'] = $dtkaskecil;
        $data['dtkelompokproduksi'] = $this->objKelompokproduksi->findAll();
        $data['dtantarmuka'] = $this->objAntarmuka->findAll();
        return view('kaskecil/edit', $data);
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
    $existingData = $this->objKasKecil->find($id);
    if (!$existingData) {
        return redirect()->to(site_url('kaskecil'))->with('error', 'Data tidak ditemukan');
    }

    // Ambil data yang diinputkan dari form
    $data = [
        'id_kaskecil'    => $this->request->getVar('id_kaskecil'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'id_interface'      => $this->request->getVar('id_interface'),
            'id_kelproduksi'      => $this->request->getVar('id_kelproduksi'),
            'rekening'             => $this->request->getVar('rekening'),
            'b_pembantu'   => $this->request->getVar('b_pembantu'),
            'nama_rekening'            => $this->request->getVar('nama_rekening'),
            'nama_bpembantu'              => $this->request->getVar('nama_bpembantu'),
            'rp'              => $this->request->getVar('rp'),
            'keterangan'        => $this->request->getVar('keterangan'),
    ];

    // Update data berdasarkan ID
    $this->objKasKecil->update($id, $data);

    return redirect()->to(site_url('kaskecil'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('kaskecil1')->where(['id_kaskecil' => $id])->delete();
        return redirect()->to(site_url('kaskecil'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
