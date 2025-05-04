<?php

namespace App\Controllers\setup_persediaan;

use App\Models\setup_persediaan\ModelGroup;
use App\Models\setup\ModelSetupBuku;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Group extends ResourceController
{
    protected $objGroup;
    protected $objSetupBuku;
    protected $db;
    function __construct()
    {
        $this->objGroup = new ModelGroup();
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

        $data['dtgroup'] = $this->objGroup->getGroupWithSetupBuku();
        return view('setup_persediaan/group/index', $data);
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
        $data['dtrekening'] = $this->objSetupBuku->findAll();
        return view('setup_persediaan/group/new', $data);
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
            'id_group' => $this->request->getVar('id_group'),
            'kode_group' => $this->request->getVar('kode_group'),
            'nama_group' => $this->request->getVar('nama_group'),
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
        ];

        $this->objGroup->insert($data);
        return redirect()->to(site_url('setup_persediaan/group'))->with('Sukses', 'Data Berhasil Disimpan');
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
        // Ambil data group berdasarkan ID
        $dtgroup = $this->objGroup->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtgroup) {
            return redirect()->to(site_url('setup_persediaan/group'))->with('error', 'Data tidak ditemukan');
        }

        // Ambil data rekening


        // Kirim data ke view
        $data['dtgroup'] = $dtgroup;
        $data['dtrekening'] = $this->objSetupBuku->findAll(); // Kirimkan data rekening ke view

        return view('setup_persediaan/group/edit', $data);
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
            'id_group' => $this->request->getVar('id_group'),
            'kode_group' => $this->request->getVar('kode_group'),
            'nama_group' => $this->request->getVar('nama_group'),
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
        ];
        // Update data berdasarkan ID
        $this->objGroup->update($id, $data);

        return redirect()->to(site_url('setup_persediaan/group'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('group1')->where(['id_group' => $id])->delete();
        return redirect()->to(site_url('setup_persediaan/group'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
