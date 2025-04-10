<?php

namespace App\Controllers;

use App\Models\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupSupplier extends ResourceController
{
    protected $objSetupsupplier;
    protected $db;
    function __construct()
    {
        $this->objSetupsupplier = new ModelSetupsupplier();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();
        return view('setupsupplier/index', $data);
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
        $builder = $this->db->table('setupsupplier1');
        $query = $builder->get();
        $data['dtsetupsupplier'] = $query->getResult();
        return view('setupsupplier/new', $data);
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
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'kode' => $this->request->getVar('kode'),
            'nama' => $this->request->getVar('nama'),
            'alamat' => $this->request->getVar('alamat'),
            'telepon' => $this->request->getVar('telepon'),
            'contact_person' => $this->request->getVar('contact_person'),
            'npwp' => $this->request->getVar('npwp'),
            'tanggal' => $this->request->getVar('tanggal'),
            'saldo' => $this->request->getVar('saldo'),
            'tipe' => $this->request->getVar('tipe'),
        ];
        $this->db->table('setupsupplier1')->insert($data);

        return redirect()->to(site_url('setupsupplier'))->with('Sukses', 'Data Berhasil Disimpan');
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
       $dtsetupsupplier = $this->objSetupsupplier->find($id);

       // Cek jika data tidak ditemukan
       if (!$dtsetupsupplier) {
           return redirect()->to(site_url('setupsupplier'))->with('error', 'Data tidak ditemukan');
       }


       // Lanjutkan jika semua pengecekan berhasil
       $data['dtsetupsupplier'] = $dtsetupsupplier;
       
       return view('setupsupplier/edit', $data);
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
            'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
            'kode' => $this->request->getVar('kode'),
            'nama' => $this->request->getVar('nama'),
            'alamat' => $this->request->getVar('alamat'),
            'telepon' => $this->request->getVar('telepon'),
            'contact_person' => $this->request->getVar('contact_person'),
            'npwp' => $this->request->getVar('npwp'),
            'tanggal' => $this->request->getVar('tanggal'),
            'saldo' => $this->request->getVar('saldo'),
            'tipe' => $this->request->getVar('tipe'),
        ];
        // Update data berdasarkan ID
        $this->objSetupsupplier->update($id, $data);

        return redirect()->to(site_url('setupsupplier'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('setupsupplier1')->where(['id_setupsupplier' => $id])->delete();
        return redirect()->to(site_url('setupsupplier'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
