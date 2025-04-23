<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelAntarmuka;
use App\Models\setup\ModelSetupBiaya;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelPosneraca;
use App\Models\setup\ModelKlasifikasi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class SetupBiaya extends ResourceController
{
    protected $modelSetupBiaya;
    protected $modelAntarmuka;
    protected $modelSetupBuku;
    protected $modelPosneraca;
    protected $modelKlasifikasi;
    protected $db;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->modelSetupBiaya = new ModelSetupBiaya();
        $this->modelAntarmuka = new ModelAntarmuka();
        $this->modelSetupBuku = new ModelSetupBuku();
        $this->modelPosneraca = new ModelPosneraca();
        $this->modelKlasifikasi = new ModelKlasifikasi();
        $this->db = \Config\Database::connect();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtsetupbiaya'] = $this->modelSetupBiaya->getBiayaWithRekening();
        return view('setup/biaya/index', $data);
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
        // Ambil nilai biaya dari tabel interface
        $kode_biaya = $this->modelAntarmuka->findAll()[0]->biaya;
        $id_klasifikasi = $this->modelKlasifikasi->where('kode_klasifikasi', $kode_biaya)->first()->id_klasifikasi;
        // Ambil data posneraca berdasarkan kode_biaya
        $data_posneraca = $this->modelPosneraca->where('id_klasifikasi', $id_klasifikasi)->findAll();
        $id_posneraca = [];

        foreach ($data_posneraca as $posneraca) {
            $id_posneraca[] = $posneraca->id_posneraca;
        }
        // Ambil data buku besar yang sesuai posneraca
        if (!empty($id_posneraca)) {
            $data['dtrekening'] = $this->modelSetupBuku->whereIn('id_posneraca', $id_posneraca)->findAll();
        } else {
            $data['dtrekening'] = []; // No matching records
        }

        return view('setup/biaya/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $kode = $this->request->getVar('kode_setupbiaya');
        $nama = $this->request->getVar('nama_setupbiaya');

        // Cek apakah kode atau nama biaya sudah ada
        $cekDuplikat = $this->modelSetupBiaya
            ->where('kode_setupbiaya', $kode)
            ->orWhere('nama_setupbiaya', $nama)
            ->first();

        if ($cekDuplikat) {
            return redirect()->back()->with('error', 'Kode atau Nama Biaya sudah digunakan!');
        }

        $data = [
            'id_setupbiaya' => $this->request->getVar('id_setupbiaya'),
            'kode_setupbiaya' => $kode,
            'nama_setupbiaya' => $nama,
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
        ];
        $this->modelSetupBiaya->insert($data);

        return redirect()->to(site_url('setup/biaya'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtsetupbiaya = $this->modelSetupBiaya->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtsetupbiaya) {
            return redirect()->to(site_url('setup/biaya'))->with('error', 'Data tidak ditemukan');
        }
        // Ambil data rekening dari tabel interface1
        $ModelAntarmuka = new ModelAntarmuka();
        $dtinterface = $ModelAntarmuka->findAll(); // Mengambil semua data rekening

        // Kirim data ke view
        $data['dtsetupbiaya'] = $dtsetupbiaya;
        $data['dtinterface'] = $dtinterface;

        return view('setup/biaya/edit', $data);
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
            'id_setupbiaya' => $this->request->getVar('id_setupbiaya'),
            'kode_setupbiaya' => $this->request->getVar('kode_setupbiaya'),
            'nama_setupbiaya' => $this->request->getVar('nama_setupbiaya'),
            'id_interface' => $this->request->getVar('id_interface'),
        ];
        // Update data berdasarkan ID
        $this->modelSetupBiaya->update($id, $data);

        return redirect()->to(site_url('setup/biaya'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('setupbiaya1')->where(['id_setupbiaya' => $id])->delete();
        return redirect()->to(site_url('setup/biaya'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
