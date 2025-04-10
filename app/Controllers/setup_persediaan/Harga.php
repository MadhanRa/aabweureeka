<?php

namespace App\Controllers\setup_persediaan;

use App\Models\setup_persediaan\ModelHarga;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelKelompok;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Harga extends ResourceController
{
    protected $objHarga, $db, $modelStock, $modelKelompok;
    function __construct()
    {
        $this->objHarga = new ModelHarga();
        $this->modelStock = new ModelStock();
        $this->modelKelompok = new ModelKelompok();
        $this->db = \Config\Database::connect();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        // Ambil parameter pencarian dari URL
        $searchKelompok = $this->request->getGet('search_kelompok');

        // Siapkan query dasar
        $query = $this->objHarga->join('stock1', 'stock1.id_stock = harga1.id_stock');

        // Jika ada parameter pencarian kelompok, tambahkan filter
        if (!empty($searchKelompok)) {
            $query->where('stock1.id_kelompok', $searchKelompok);
        }

        // Dapatkan data harga berdasarkan filter
        $dtharga = $query->findAll();

        // Data untuk dropdown filter
        $dtkelompok = $this->modelKelompok->findAll();

        return view('setup_persediaan/harga/index', [
            'dtharga' => $dtharga,
            'dtkelompok' => $dtkelompok,
            'searchKelompok' => $searchKelompok,
        ]);
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
        $data['dtstock'] = $this->modelStock->findAll();

        return view('setup_persediaan/harga/new', $data);
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
            'id_harga' => $this->request->getVar('id_harga'),
            'id_stock' => $this->request->getVar('id_stock'),
            'harga_jualexc' => $this->request->getVar('harga_jualexc'),
            'harga_jualinc' => $this->request->getVar('harga_jualinc'),
            'harga_beli' => $this->request->getVar('harga_beli'),
        ];
        $this->db->table('harga1')->insert($data);

        return redirect()->to(site_url('setup_persediaan/harga'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtharga = $this->objHarga->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtharga) {
            return redirect()->to(site_url('setup_persediaan/harga'))->with('error', 'Data tidak ditemukan');
        }
        $data['dtstock'] = $this->modelStock->findAll();

        // Lanjutkan jika semua pengecekan berhasil
        $data['dtharga'] = $dtharga;

        return view('setup_persediaan/harga/edit', $data);
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
            'id_harga' => $this->request->getVar('id_harga'),
            'kode_harga' => $this->request->getVar('kode_harga'),
            'nama_barang' => $this->request->getVar('nama_barang'),
            'harga_jualexc' => $this->request->getVar('harga_jualexc'),
            'harga_jualinc' => $this->request->getVar('harga_jualinc'),
            'harga_beli' => $this->request->getVar('harga_beli'),
        ];
        // Update data berdasarkan ID
        $this->objHarga->update($id, $data);

        return redirect()->to(site_url('setup_persediaan/harga'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $this->db->table('harga1')->where(['id_harga' => $id])->delete();
        return redirect()->to(site_url('setup_persediaan/harga'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
