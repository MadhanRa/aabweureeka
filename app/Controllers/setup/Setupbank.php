<?php

namespace App\Controllers;

use App\Models\ModelAntarmuka;
use App\Models\ModelSetupbank;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Setupbank extends ResourceController
{
    protected $objSetupbank, $db, $objAntarmuka;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objSetupbank = new ModelSetupbank();
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
        // Ambil data rekening dari tabel interface1
        $ModelAntarmuka = new ModelAntarmuka();
        $dtinterface = $ModelAntarmuka->findAll(); // Mengambil semua data rekening
        
        $data['dtinterface'] = $dtinterface;
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->getGroupWithInterface();
        $data['dtinterface'] = $this->db->table('interface1')->get()->getResult();

    // Pastikan $data dikirim ke view
    return view('setupbank/index', $data);
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
        //
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
            'id_setupbank' => $this->request->getVar('id_setupbank'),
            'kode_setupbank' => $this->request->getVar('kode_setupbank'),
            'nama_setupbank' => $this->request->getVar('nama_setupbank'),
            'id_interface' => $this->request->getVar('id_interface'),
        ];
        $this->db->table('setupbank1')->insert($data);

        return redirect()->to(site_url('setupbank'))->with('Sukses', 'Data Berhasil Disimpan');
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
        //
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
        //
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
        //
    }
}
