<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupSupplier extends ResourceController
{
    protected $supplierModel;
    protected $db;
    function __construct()
    {
        $this->supplierModel = new ModelSetupsupplier();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtsetupsupplier'] = $this->supplierModel->findAll();
        return view('setup/supplier/index', $data);
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
        // Buat supplier kode
        $count = $this->supplierModel->countAllResults();
        $data['kode_supplier'] = sprintf('%03d', $count + 1);
        return view('setup/supplier/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $namaSalesman = $this->request->getVar('nama');
        $kodeSalesman = $this->request->getVar('kode');

        // Ekstrak huruf pertama dari nama
        $initial = strtoupper(substr($namaSalesman, 0, 1));

        // Buat kode otomatis
        $kodeSetupSalesman = $kodeSalesman . '-' . $initial;


        $data = $this->request->getPost([
            'nama',
            'alamat',
            'telepon',
            'contact_person',
            'npwp',
            'tipe',
        ]);

        $data['kode'] = $kodeSetupSalesman;
        $data['saldo'] = '0';

        if ($this->supplierModel->insert($data)) {
            return redirect()->to(site_url('setup/supplier'))->with('Sukses', 'Data Berhasil Disimpan');
        }
        return redirect()->to(site_url('setup/supplier/new'))->with('error', 'Data Gagal Disimpan');
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
        $dtsetupsupplier = $this->supplierModel->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetupsupplier) {
            return redirect()->to(site_url('setup/supplier'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtsetupsupplier'] = $dtsetupsupplier;

        return view('setup/supplier/edit', $data);
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
        // Update data berdasarkan ID
        $this->supplierModel->update($id, $data);

        return redirect()->to(site_url('setup/supplier'))->with('Sukses', 'Data Berhasil Diupdate');
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
        return redirect()->to(site_url('setup/supplier'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
