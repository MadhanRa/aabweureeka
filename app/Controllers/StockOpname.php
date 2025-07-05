<?php

namespace App\Controllers;

use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelStockGudang;
use App\Models\setup\ModelSetupUserOpname;
use App\Models\transaksi\ModelStockOpname;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class StockOpname extends ResourceController
{

    protected $objStockOpname;
    protected $objLokasi;
    protected $objSetupUser;
    protected $objSatuan;
    protected $objStock;
    protected $objStockGudang;
    protected $db;

    //  INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objStockOpname = new ModelStockOpname();
        $this->objLokasi = new ModelLokasi();
        $this->objSetupUser = new ModelSetupUserOpname();
        $this->objStock = new ModelStock();
        $this->objStockGudang = new ModelStockGudang();
        $this->objSatuan = new ModelSatuan();
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
        $data['dtstockopname'] = $this->objStockOpname->getAll();
        return view('stockopname/index', $data);
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
        $data['dtlokasi'] = $this->objLokasi->findAll();
        $data['dtsetupuser'] = $this->objSetupUser->findAll();
        return view('stockopname/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        // ambil qty_1 dan qty_2 dari lokasi di stock gudang
        $id_stock = $this->request->getVar('id_stock');
        $id_lokasi = $this->request->getVar('id_lokasi');
        $stock_gudang = $this->objStockGudang->where(['id_stock' => $id_stock, 'id_lokasi' => $id_lokasi])->first();
        $selisih_1 = $this->request->getVar('qty_1') - $stock_gudang->qty1;
        $selisih_2 = $this->request->getVar('qty_2') - $stock_gudang->qty2;

        $data = [
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'id_lokasi'      => $id_lokasi,
            'id_user'      => $this->request->getVar('id_user'),
            'id_stock'             => $id_stock,
            'satuan'              => $this->request->getVar('satuan'),
            'qty_1'            => $this->request->getVar('qty_1'),
            'qty_2'            => $this->request->getVar('qty_2'),
            'qty_1_sys'         => $stock_gudang->qty1,
            'qty_2_sys'         => $stock_gudang->qty2,
            'selisih_1'         => $selisih_1,
            'selisih_2'         => $selisih_2,
        ];
        $this->db->table('stockopname1')->insert($data);

        return redirect()->to(site_url('stockopname'))->with('Sukses', 'Data Berhasil Disimpan');
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
        // Cek apakah pengguna memiliki peran admin
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }

        // Ambil data berdasarkan ID
        $dtstockopname = $this->objStockOpname->getById($id);

        // Cek jika data tidak ditemukan
        if (!$dtstockopname) {
            return redirect()->to(site_url('stockopname'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtstockopname'] = $dtstockopname;
        $data['dtlokasi'] = $this->objLokasi->findAll();
        $data['dtsetupuser'] = $this->objSetupUser->findAll();
        return view('stockopname/edit', $data);
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
        // Cek apakah pengguna memiliki peran admin
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }

        // Cek apakah data dengan ID yang diberikan ada di database
        $existingData = $this->objStockOpname->find($id);
        if (!$existingData) {
            return redirect()->to(site_url('stockopname'))->with('error', 'Data tidak ditemukan');
        }

        // ambil qty_1 dan qty_2 dari lokasi di stock gudang
        $id_stock = $this->request->getVar('id_stock');
        $id_lokasi = $this->request->getVar('id_lokasi');
        $stock_gudang = $this->objStockGudang->where(['id_stock' => $id_stock, 'id_lokasi' => $id_lokasi])->first();
        $selisih_1 = $this->request->getVar('qty_1') - $stock_gudang->qty1;
        $selisih_2 = $this->request->getVar('qty_2') - $stock_gudang->qty2;

        // Ambil data yang diinputkan dari form
        $data = [
            'id_stockopname'    => $this->request->getVar('id_stockopname'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'id_lokasi'      => $id_lokasi,
            'id_user'      => $this->request->getVar('id_user'),
            'id_stock'             => $id_stock,
            'satuan'              => $this->request->getVar('satuan'),
            'qty_1'            => $this->request->getVar('qty_1'),
            'qty_2'            => $this->request->getVar('qty_2'),
            'qty_1_sys'         => $stock_gudang->qty1,
            'qty_2_sys'         => $stock_gudang->qty2,
            'selisih_1'         => $selisih_1,
            'selisih_2'         => $selisih_2,
        ];

        // Update data berdasarkan ID
        $this->objStockOpname->update($id, $data);

        return redirect()->to(site_url('stockopname'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('stockopname1')->where(['id_stockopname' => $id])->delete();
        return redirect()->to(site_url('stockopname'))->with('Sukses', 'Data Berhasil Dihapus');
    }

    public function autocomplete()
    {
        $term = $this->request->getGet('term');
        $location_id = $this->request->getGet('location_id');

        // Validasi apakah term tidak kosong
        if (empty($term)) {
            return $this->response->setJSON([]);
        }

        $results = $this->objStock
            ->select("stock1.id_stock, stock1.kode, stock1.nama_barang, sat1.kode_satuan as satuan_1, sat2.kode_satuan as satuan_2")
            ->join('satuan1 sat1', 'stock1.id_satuan = sat1.id_satuan', 'left')
            ->join('satuan1 sat2', 'stock1.id_satuan2 = sat2.id_satuan', 'left')
            ->join('stock1_gudang sg', 'stock1.id_stock = sg.id_stock', 'left')
            ->where('sg.id_lokasi', $location_id)
            ->groupStart()
            ->like('stock1.nama_barang', $term)
            ->orLike('stock1.kode', $term)
            ->groupEnd()
            ->limit(5)
            ->findAll();
        // Mengembalikan data dalam format JSON
        return $this->response->setJSON($results);
    }
}
