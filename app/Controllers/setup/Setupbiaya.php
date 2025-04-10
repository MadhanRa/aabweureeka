<?php

namespace App\Controllers;

use App\Models\ModelAntarmuka;
use App\Models\ModelSetupbiaya;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Setupbiaya extends ResourceController
{
    protected $objSetupbiaya;
    protected $objAntarmuka;
    protected $db;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objSetupbiaya = new ModelSetupbiaya();
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
        $data['dtsetupbiaya'] = $this->objSetupbiaya->findAll();
        $data['dtsetupbiaya'] = $this->objSetupbiaya->getGroupWithInterface();
        $data['dtinterface'] = $this->db->table('interface1')->get()->getResult();
        return view('setupbiaya/index', $data);
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
        $data['dtsetupbiaya'] = $this->objSetupbiaya->findAll();
        $data['dtsetupbiaya'] = $this->objSetupbiaya->getGroupWithInterface();
        $data['dtinterface'] = $this->db->table('interface1')->get()->getResult();
        return view('setupbiaya/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        {
            $kode = $this->request->getVar('kode_setupbiaya');
            $nama = $this->request->getVar('nama_setupbiaya');
        
            // Cek apakah kode atau nama biaya sudah ada
            $cekDuplikat = $this->objSetupbiaya
                ->where('kode_setupbiaya', $kode)
                ->orWhere('nama_setupbiaya', $nama)
                ->first();
        
            if ($cekDuplikat) {
                return redirect()->back()->with('error', 'Kode atau Nama Biaya sudah digunakan!');
            }
        }
        
        $data = $this->request->getPost();
        $data = [
            'id_setupbiaya' => $this->request->getVar('id_setupbiaya'),
            'kode_setupbiaya' => $kode,
            'nama_setupbiaya' => $nama,
            'id_interface' => $this->request->getVar('id_interface'),
        ];
        $this->db->table('setupbiaya1')->insert($data);

        return redirect()->to(site_url('setupbiaya'))->with('Sukses', 'Data Berhasil Disimpan');
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
        // Ambil data berdasarkan ID
       $dtsetupbiaya = $this->objSetupbiaya->find($id);

       // Cek jika data tidak ditemukan
       if (!$dtsetupbiaya) {
           return redirect()->to(site_url('setupbiaya'))->with('error', 'Data tidak ditemukan');
       }
        // Ambil data rekening dari tabel interface1
        $ModelAntarmuka = new ModelAntarmuka();
        $dtinterface = $ModelAntarmuka->findAll(); // Mengambil semua data rekening

        // Kirim data ke view
        $data['dtsetupbiaya'] = $dtsetupbiaya;
        $data['dtinterface'] = $dtinterface;
       
       return view('setupbiaya/edit', $data);
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
        $data = $this->request->getPost();
        $data = [
            'id_setupbiaya' => $this->request->getVar('id_setupbiaya'),
            'kode_setupbiaya' => $this->request->getVar('kode_setupbiaya'),
            'nama_setupbiaya' => $this->request->getVar('nama_setupbiaya'),
            'id_interface' => $this->request->getVar('id_interface'),
        ];
        // Update data berdasarkan ID
        $this->objSetupbiaya->update($id, $data);

        return redirect()->to(site_url('setupbiaya'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('setupbiaya1')->where(['id_setupbiaya' => $id])->delete();
        return redirect()->to(site_url('setupbiaya'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
