<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelSetupUserOpname;
use App\Models\setup\ModelUserLokasi;
use App\Models\setup_persediaan\ModelLokasi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupUserOpname extends ResourceController
{
    protected $modelSetupUser;
    protected $modelLokasi;
    protected $modelUserLokasi;
    protected $db;
    function __construct()
    {
        $this->modelSetupUser = new ModelSetupUserOpname();
        $this->modelLokasi = new ModelLokasi();
        $this->modelUserLokasi = new ModelUserLokasi();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtsetupuser'] = $this->modelSetupUser->findAll();
        return view('setup/setupuser/index', $data);
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
        // Ambil data berdasarkan ID
        $dtsetupuser = $this->modelSetupUser->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetupuser) {
            return redirect()->to(site_url('setup/useropname'))->with('error', 'Data tidak ditemukan');
        }

        $data['data'] = $dtsetupuser;
        $data['dtlokasi'] = $this->modelLokasi->findAll();
        $data['dtuserlokasi'] = $this->modelUserLokasi->where('id_user', $id)->findColumn('id_lokasi');

        if ($this->request->isAJAX()) {
            $msg = [
                'success' => true,
                'data' => view('setup/setupuser/detail', $data),
            ];
            return $this->response->setJSON($msg);
        }
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        $data['dtlokasi'] = $this->modelLokasi->findAll();
        return view('setup/setupuser/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        // $password = $this->request->getVar('password');
        // $kode_aktivasi = $this->request->getVar('kode_aktivasi');

        // Validasi password dan kode aktivasi
        // if ($password !== 'aabweureeka' || $kode_aktivasi !== 'eureeka123') {
        //     return redirect()->back()->with('error', 'Password atau kode aktivasi salah.');
        // }

        $data = [
            'kode_user' => $this->request->getPost('kode_user'),
            'nama_user' => $this->request->getPost('nama_user'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'kode_aktivasi' => $this->request->getPost('kode_aktivasi'),
            'nonaktif' => $this->request->getPost('nonaktif'),
        ];

        $lokasi_terpilih = $this->request->getPost('lokasi');
        $this->modelSetupUser->insert($data);

        $id_userbaru = $this->modelSetupUser->getInsertID(); // Ambil ID user yang baru saja dimasukkan

        foreach ($lokasi_terpilih as $id_lokasi) {
            $this->modelUserLokasi->insert([
                'id_user' => $id_userbaru,
                'id_lokasi' => $id_lokasi,
            ]);
        }

        return redirect()->to(site_url('setup/useropname'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtsetupuser = $this->modelSetupUser->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetupuser) {
            return redirect()->to(site_url('setup/useropname'))->with('error', 'Data tidak ditemukan');
        }

        $data['dtlokasi'] = $this->modelLokasi->findAll();
        $data['dtuserlokasi'] = $this->modelUserLokasi->where('id_user', $id)->findColumn('id_lokasi');

        // Lanjutkan jika semua pengecekan berhasil
        $data['dtsetupuser'] = $dtsetupuser;

        return view('setup/setupuser/edit', $data);
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
        // $password = $this->request->getVar('password');
        // $kode_aktivasi = $this->request->getVar('kode_aktivasi');

        // // Validasi password dan kode aktivasi
        // if ($password !== 'aabweureeka' || $kode_aktivasi !== 'eureeka123') {
        //     return redirect()->back()->with('error', 'Password atau kode aktivasi salah.');
        // }

        $data = [
            'kode_user' => $this->request->getPost('kode_user'),
            'nama_user' => $this->request->getPost('nama_user'),
            'kode_aktivasi' => $this->request->getPost('kode_aktivasi'),
            'nonaktif' => $this->request->getPost('nonaktif'),
        ];

        // hanya update password jika terisi
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        // Update data berdasarkan ID
        $this->modelSetupUser->update($id, $data);

        //Hapus semua relasi lama
        $this->modelUserLokasi->where('id_user', $id)->delete();

        // Tambah relasi yang baru
        $lokasi_terpilih = $this->request->getPost('lokasi');
        if ($lokasi_terpilih) {
            foreach ($lokasi_terpilih as $id_lokasi) {
                $this->modelUserLokasi->insert([
                    'id_user' => $id,
                    'id_lokasi' => $id_lokasi,
                ]);
            }
        }

        return redirect()->to(site_url('setup/useropname'))->with('Sukses', 'Data Berhasil Disimpan');
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
        // hapus relasi lokasi dulu agar foreign tidak error
        $this->modelUserLokasi->where('id_user', $id)->delete();

        // hapus data user
        $this->modelSetupUser->delete($id);
        return redirect()->to(site_url('setup/useropname'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
