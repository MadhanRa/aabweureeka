<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetupBuku;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupBuku extends ResourceController
{
    protected $objSetupBuku;
    protected $db;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objSetupBuku = new ModelSetupBuku();
        $this->db = \Config\Database::connect();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtsetupbuku'] = $this->objSetupBuku->findAll();
        return view('setup/buku/index', $data);
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
        $data['dtposneraca'] = $this->db->table('pos_neraca')->get()->getResult();
        return view('setup/buku/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $data = [
            'kode_setupbuku' => $this->request->getVar('kode_setupbuku'),
            'nama_setupbuku' => $this->request->getVar('nama_setupbuku'),
            'id_posneraca' => $this->request->getVar('id_posneraca'),
            'tanggal_awal_saldo' => $this->request->getVar('tanggal_awal_saldo'),
            'saldo_awal' => $this->request->getVar('saldo_awal'),
            'saldo_berjalan' => $this->request->getVar('saldo_awal'),
        ];
        $this->objSetupBuku->insert($data);

        return redirect()->to(site_url('setup/buku'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $data['dtposneraca'] = $this->db->table('pos_neraca')->get()->getResult();
        $data['dtsetupbuku'] = $this->objSetupBuku->find($id);
        return view('setup/buku/edit', $data);
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
        $data = [
            'id_setupbuku' => $id,
            'kode_setupbuku' => $this->request->getVar('kode_setupbuku'),
            'nama_setupbuku' => $this->request->getVar('nama_setupbuku'),
            'id_posneraca' => $this->request->getVar('id_posneraca'),
            'tanggal_awal_saldo' => $this->request->getVar('tanggal_awal_saldo'),
            'saldo_awal' => $this->request->getVar('saldo_awal'),
            'saldo_berjalan' => $this->request->getVar('saldo_awal'),
        ];
        $this->objSetupBuku->save($data);

        return redirect()->to(site_url('setup/buku'))->with('Sukses', 'Data Berhasil Diubah');
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
        $this->objSetupBuku->delete($id);
        return redirect()->to(site_url('setup/buku'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
