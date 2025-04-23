<?php

namespace App\Controllers\setup;

use App\Models\setup\ModelAntarmuka;
use App\Models\setup\ModelKelompokproduksi;
use App\Models\setup\ModelSetupBuku;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Kelompokproduksi extends ResourceController
{
    protected $db;
    protected $modelKelProduksi;
    protected $modelSetupBuku;
    protected $modelAntarmuka;
    // INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->modelKelProduksi = new ModelKelompokproduksi();
        $this->modelSetupBuku = new ModelSetupBuku();
        $this->modelAntarmuka = new ModelAntarmuka();
        $this->db = \Config\Database::connect();
    }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data['dtkelompokproduksi'] = $this->modelKelProduksi->getKelProduksiWithBukuBesar();
        return view('setup/kelompokproduksi/index', $data);
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
        // Ambil data rekening produksi dari tabel interface1
        $dtinterface = $this->modelAntarmuka->findAll()[0]->rekening_biaya;
        $kode_rekening_biaya = explode(',', $dtinterface);

        // Ambil data buku besar berdasarkan kode rekening
        $data['dtsetupbuku'] = $this->modelSetupBuku->whereIn('kode_setupbuku', $kode_rekening_biaya)->findAll();

        return view('setup/kelompokproduksi/new', $data);
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
            'id_kelproduksi' => $this->request->getVar('id_kelproduksi'),
            'kode_kelproduksi' => $this->request->getVar('kode_kelproduksi'),
            'nama_kelproduksi' => $this->request->getVar('nama_kelproduksi'),
            'id_setupbuku' => $this->request->getVar('id_setupbuku'),
        ];
        $this->modelKelProduksi->insert($data);

        return redirect()->to(site_url('setup/kelompokproduksi'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtkelompokproduksi = $this->modelKelProduksi->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtkelompokproduksi) {
            return redirect()->to(site_url('setup/kelompokproduksi'))->with('error', 'Data tidak ditemukan');
        }

        // Ambil data rekening produksi dari tabel interface1
        $dtinterface = $this->modelAntarmuka->findAll()[0]->rekening_biaya;
        $kode_rekening_biaya = explode(',', $dtinterface);

        // Ambil data buku besar berdasarkan kode rekening
        $data['dtsetupbuku'] = $this->modelSetupBuku->whereIn('kode_setupbuku', $kode_rekening_biaya)->findAll();

        // Kirim data ke view
        $data['dtkelompokproduksi'] = $dtkelompokproduksi;

        return view('setup/kelompokproduksi/edit', $data);
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
            'id_kelproduksi' => $this->request->getVar('id_kelproduksi'),
            'kode_kelproduksi' => $this->request->getVar('kode_kelproduksi'),
            'nama_kelproduksi' => $this->request->getVar('nama_kelproduksi'),
            'id_interface' => $this->request->getVar('id_interface'),
        ];
        // Update data berdasarkan ID
        $this->modelKelProduksi->update($id, $data);

        return redirect()->to(site_url('setup/kelompokproduksi'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('kelompokproduksi1')->where(['id_kelproduksi' => $id])->delete();
        return redirect()->to(site_url('setup/kelompokproduksi'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
