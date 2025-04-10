<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetupsalesman;
use App\Models\setup_persediaan\ModelLokasi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupSalesman extends ResourceController
{
    protected $salesmanModel, $lokasiModel, $db;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->salesmanModel = new ModelSetupsalesman();
        $this->lokasiModel = new ModelLokasi();
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
        } else {
            $data['is_closed'] = 'FALSE';
        }
        $data['dtsetupsalesman'] = $this->salesmanModel->getSalesmanwithLokasi();
        return view('setup/salesman/index', $data);
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
        // Menghitung jumlah data yang telah ada untuk kode
        $count = $this->salesmanModel->countAllResults();

        // Memformat kode
        $kodeSetupsalesman = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        $data['kode_setupsalesman'] = $kodeSetupsalesman;
        $data['dtlokasi'] = $this->lokasiModel->findAll();
        return view('setup/salesman/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $namaSetupsalesman = $this->request->getVar('nama_setupsalesman');
        $kodeSalesman = $this->request->getVar('kode_setupsalesman');

        // Ekstrak huruf pertama dari nama
        $initial = strtoupper(substr($namaSetupsalesman, 0, 1));

        // Buat kode otomatis
        $kodeSetupsalesman = $kodeSalesman . '-' . $initial;

        // Data untuk disimpan
        $data = [
            'kode_salesman' => $kodeSetupsalesman,
            'nama_salesman' => $namaSetupsalesman,
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'saldo' => '0'
        ];

        if ($this->salesmanModel->insert($data)) {
            return redirect()->to(site_url('setup/salesman'))->with('Sukses', 'Data Berhasil Disimpan');
        }
        return redirect()->to(site_url('setup/salesman/new'))->with('error', 'Data Gagal Disimpan');
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
        $dtsetupsalesman = $this->salesmanModel->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetupsalesman) {
            return redirect()->to(site_url('setup/salesman'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtsalesman'] = $dtsetupsalesman;
        $data['dtlokasi'] = $this->lokasiModel->findAll();
        return view('setup/salesman/edit', $data);
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

        // Data untuk disimpan
        $data = [
            'kode_salesman' => $this->request->getVar('kode_salesman'),
            'nama_salesman' => $this->request->getVar('nama_salesman'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
        ];
        // Update data berdasarkan ID
        $this->salesmanModel->update($id, $data);

        return redirect()->to(site_url('setup/salesman'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('setupsalesman1')->where(['id_salesman' => $id])->delete();
        return redirect()->to(site_url('setup/salesman'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
