<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetuppelanggan;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupPelanggan extends ResourceController
{
    protected $pelangganModel;
    protected $db;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->pelangganModel = new ModelSetuppelanggan();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtsetuppelanggan'] = $this->pelangganModel->findAll();
        return view('setup/pelanggan/index', $data);
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
        // Buat kode pelanggan baru
        $jumlahData = $this->pelangganModel->countAllResults();
        $kode_pelanggan = str_pad($jumlahData + 1, 3, '0', STR_PAD_LEFT);
        $data['kode_pelanggan'] = $kode_pelanggan;
        return view('setup/pelanggan/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $nama_pelanggan = $this->request->getVar('nama_pelanggan');
        $kode_pelanggan = $this->request->getVar('kode_pelanggan');

        // Ambil huruf pertama dari nama pelanggan
        $kode_prefix = strtoupper(substr($nama_pelanggan, 0, 1));

        // Format kode pelanggan baru (e.g., I00001)
        $kode_pelanggan = $kode_pelanggan . '-' . $kode_prefix;

        $data = [
            'kode_pelanggan' => $kode_pelanggan,
            'nama_pelanggan' => $nama_pelanggan,
            'alamat_pelanggan' => $this->request->getVar('alamat_pelanggan'),
            'kota_pelanggan' => $this->request->getVar('kota_pelanggan'),
            'telp_pelanggan' => $this->request->getVar('telp_pelanggan'),
            'plafond' => $this->request->getVar('plafond'),
            'npwp' => $this->request->getVar('npwp'),
            'class_pelanggan' => $this->request->getVar('class_pelanggan'),
            'tipe' => $this->request->getVar('tipe'),
            'saldo' => '0',
        ];
        if ($this->pelangganModel->insert($data)) {
            return redirect()->to(site_url('setup/pelanggan'))->with('Sukses', 'Data Berhasil Disimpan');
        }
        return redirect()->to(site_url('setup/pelanggan/new'))->with('error', 'Data Gagal Disimpan');
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
        $dtsetuppelanggan = $this->pelangganModel->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetuppelanggan) {
            return redirect()->to(site_url('setup/pelanggan'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtsetuppelanggan'] = $dtsetuppelanggan;

        return view('setup/pelanggan/edit', $data);
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

        $data = $this->request->getPost([
            'kode_pelanggan',
            'nama_pelanggan',
            'alamat_pelanggan',
            'kota_pelanggan',
            'telp_pelanggan',
            'plafond',
            'npwp',
            'class_pelanggan',
            'tipe',
        ]);
        // Update data berdasarkan ID
        $this->pelangganModel->update($id, $data);

        return redirect()->to(site_url('setup/pelanggan'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('setuppelanggan1')->where(['id_pelanggan' => $id])->delete();
        return redirect()->to(site_url('setup/pelanggan'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
