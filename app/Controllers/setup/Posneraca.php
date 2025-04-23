<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelPosneraca;
use App\Models\setup\ModelKlasifikasi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Posneraca extends ResourceController
{
    protected $objPosneraca;
    protected $objKlasifikasi;
    protected $db;
    //Inisialisasi object data
    function __construct()
    {
        $this->objPosneraca = new ModelPosneraca();
        $this->objKlasifikasi = new ModelKlasifikasi();
        $this->db = \Config\Database::connect();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtposneraca'] = $this->objPosneraca->getAll();
        return view('setup/posneraca/index', $data);
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

        $data['dtposneraca'] = $this->objPosneraca->getAll();
        $data['dtklasifikasi'] = $this->objKlasifikasi->findAll();
        return view('setup/posneraca/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        // Aturan validasi
        $validationRules = [
            'nama_posneraca' => 'required',
            'kode_posneraca' => 'required|min_length[3]',
            'id_klasifikasi' => 'required',
            'posisi_posneraca' => 'required'
        ];

        // Jalankan validasi
        if (!$this->validate($validationRules)) {
            // Jika validasi gagal, kembali ke form dengan pesan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $data = [
            'nama_posneraca' => strtoupper($this->request->getVar('nama_posneraca')),
            'kode_posneraca' => $this->request->getVar('kode_posneraca'),
            'id_klasifikasi' => $this->request->getVar('id_klasifikasi'),
            'posisi_posneraca' => $this->request->getVar('posisi_posneraca'),
        ];

        // Simpan data ke database menggunakan model
        if ($this->objPosneraca->insert($data)) {
            return redirect()->to(site_url('setup/posneraca'))->with('Sukses', 'Data Berhasil Disimpan');
        } else {
            // Log error
            log_message('error', 'Data Gagal Disimpan: ' . json_encode($this->objPosneraca->errors()));
            return redirect()->back()->withInput()->with('error', 'Data Gagal Disimpan');
        }
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
        $dtposneraca = $this->objPosneraca->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtposneraca) {
            return redirect()->to(site_url('setup/posneraca'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtposneraca'] = $dtposneraca;
        $data['dtklasifikasi'] = $this->objKlasifikasi->findAll();
        return view('setup/posneraca/edit', $data);
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
            'nama_posneraca' => $this->request->getVar('nama_posneraca'),
            'kode_posneraca' => $this->request->getVar('kode_posneraca'),
            'id_klasifikasi' => $this->request->getVar('id_klasifikasi'),
            'posisi_posneraca' => $this->request->getVar('posisi_posneraca'),
        ];
        // Update data berdasarkan ID
        $this->objPosneraca->update($id, $data);

        return redirect()->to(site_url('setup/posneraca'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('pos_neraca')->where(['id_posneraca' => $id])->delete();
        return redirect()->to(site_url('setup/posneraca'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
