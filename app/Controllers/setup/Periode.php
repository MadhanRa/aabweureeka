<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelPeriode;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Periode extends ResourceController
{
    // INISIALISASI OBJECT DATA

    protected $periodeModel;
    protected $db;
    protected $validation;
    function __construct()
    {
        $this->periodeModel = new ModelPeriode();
        $this->db = \Config\Database::connect();
        $this->validation = \Config\Services::validation();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtperiode'] = $this->periodeModel->findAll();
        return view('setup/periode/index', $data);
    }

    public function setPeriode()
    {
        $periode_bulan = $this->request->getPost('periode_bulan');
        $periode_tahun = $this->request->getPost('periode_tahun');

        // Simpan data periode di session
        session()->set('periode_bulan', $periode_bulan);
        session()->set('periode_tahun', $periode_tahun);

        // Redirect ke halaman dashboard atau lainnya
        return redirect()->to(base_url('periode'));
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
        return view('setup/periode/new');
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $this->validation->setRules([
            'periode_bulan' => [
                'label' => 'Bulan',
                'rules' => 'required|in_list[Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember]',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'in_list' => '{field} harus salah satu dari: Januari, Februari, Maret, April, Mei, Juni, Juli, Agustus, September, Oktober, November, Desember.'
                ]
            ],
            'periode_tahun' => [
                'label' => 'Tahun',
                'rules' => 'required|numeric|min_length[4]|max_length[4]',
                'errors' => [
                    'required' => '{field} harus diisi.',
                    'numeric' => '{field} harus berupa angka.',
                    'min_length' => '{field} harus terdiri dari 4 karakter.',
                    'max_length' => '{field} harus terdiri dari 4 karakter.'
                ]
            ]
        ]);

        if (!$this->validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $this->validation->getErrors());
        }

        $data = [
            'periode_bulan' => $this->request->getVar('periode_bulan'),
            'periode_tahun' => $this->request->getVar('periode_tahun'),
        ];

        // Menggunakan model untuk menyimpan data
        $this->periodeModel->insert($data);

        return redirect()->to(site_url('setup/periode'))->with('Sukses', 'Data Berhasil Disimpan');
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
