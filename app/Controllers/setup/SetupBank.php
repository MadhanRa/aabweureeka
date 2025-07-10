<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelAntarmuka;
use App\Models\setup\ModelPosneraca;
use App\Models\setup\ModelSetupBank;
use App\Models\setup\ModelSetupBuku;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupBank extends ResourceController
{
    protected $modelBank, $db, $modelAntarmuka, $modelPosneraca, $modelSetupBuku;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->modelBank = new ModelSetupBank();
        $this->modelPosneraca = new ModelPosneraca();
        $this->modelAntarmuka = new ModelAntarmuka();
        $this->modelSetupBuku = new ModelSetupBuku();
        $this->db = \Config\Database::connect();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {

        $data['dtsetupbank'] = $this->modelBank->getGroupWithBukuBesar();
        // Pastikan $data dikirim ke view
        return view('setup/bank/index', $data);
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
        // Ambil kode bank dari interface
        $kode_bank_interface = $this->modelAntarmuka->findAll()[0]->bank;

        // Ambil id_posneraca bank dari posneraca
        $id_bank_posneraca = $this->modelPosneraca->where('kode_posneraca', $kode_bank_interface)->first()->id_posneraca;

        // Ambil rekening bank dari buku besar
        $data['dtrekening'] = $this->modelSetupBuku->where('id_posneraca', $id_bank_posneraca)->findAll();

        return view('setup/bank/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $data = [
            'kode_setupbank' => $this->request->getVar('kode_setupbank'),
            'nama_setupbank' => $this->request->getVar('nama_setupbank'),
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
        ];
        $this->modelBank->insert($data);

        return redirect()->to(site_url('setup/bank'))->with('Sukses', 'Data Berhasil Disimpan');
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
        // Ambil kode bank dari interface
        $kode_bank_interface = $this->modelAntarmuka->findAll()[0]->bank;

        // Ambil id_posneraca bank dari posneraca
        $id_bank_posneraca = $this->modelPosneraca->where('kode_posneraca', $kode_bank_interface)->first()->id_posneraca;

        // Ambil rekening bank dari buku besar
        $data['dtrekening'] = $this->modelSetupBuku->where('id_posneraca', $id_bank_posneraca)->findAll();

        $data['dtsetupbank'] = $this->modelBank->find($id);

        return view('setup/bank/edit', $data);
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
            'kode_setupbank' => $this->request->getVar('kode_setupbank'),
            'nama_setupbank' => $this->request->getVar('nama_setupbank'),
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
        ];
        $this->modelBank->update($id, $data);

        return redirect()->to(site_url('setup/bank'))->with('Sukses', 'Data Berhasil Diperbarui');
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
        $this->modelBank->delete($id);
        return redirect()->to(site_url('setup/bank'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
