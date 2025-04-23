<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelSatuan;
use App\Models\ModelSetupuser;
use App\Models\ModelStockOpname;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class StockOpname extends ResourceController
{
    
    protected $objStockOpname;
    protected $objLokasi;
    protected $objSetupUser;
    protected $objSatuan;
    protected $db;
    
    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objStockOpname = new ModelStockOpname();   
       $this->objLokasi = new ModelLokasi();
       $this->objSetupUser = new ModelSetupuser();
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
        }else{
            $data['is_closed'] = 'FALSE';
        }
        $data['dtstockopname'] = $this->objStockOpname->getAll();
        $data['dtlokasi'] = $this->objLokasi->findAll();
        $data['dtsetupuser'] = $this->objSetupUser->findAll();
        $data['dtsatuan'] = $this->objSatuan->findAll();
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
        $data['dtstockopname'] = $this->objStockOpname->getAll();
        $data['dtlokasi'] = $this->objLokasi->findAll();
        $data['dtsetupuser'] = $this->objSetupUser->findAll();
        $data['dtsatuan'] = $this->objSatuan->findAll();
        return view('stockopname/new', $data);
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
            'id_stockopname'    => $this->request->getVar('id_stockopname'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'id_lokasi'      => $this->request->getVar('id_lokasi'),
            'id_setupuser'      => $this->request->getVar('id_setupuser'),
            'nama_stock'             => $this->request->getVar('nama_stock'),
            'id_satuan'              => $this->request->getVar('id_satuan'),
            'qty_1'            => $this->request->getVar('qty_1'),
            'qty_2'            => $this->request->getVar('qty_2'),
            
            
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
        $dtstockopname = $this->objStockOpname->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtstockopname) {
            return redirect()->to(site_url('stockopname'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtstockopname'] = $dtstockopname;
        $data['dtlokasi'] = $this->objLokasi->findAll();
        $data['dtsetupuser'] = $this->objSetupUser->findAll();
        $data['dtsatuan'] = $this->objSatuan->findAll();
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

    // Ambil data yang diinputkan dari form
    $data = [
        'id_stockopname'    => $this->request->getVar('id_stockopname'),
            'tanggal'           => $this->request->getVar('tanggal'),
            'nota'              => $this->request->getVar('nota'),
            'id_lokasi'      => $this->request->getVar('id_lokasi'),
            'id_setupuser'      => $this->request->getVar('id_setupuser'),
            'nama_stock'             => $this->request->getVar('nama_stock'),
            'id_satuan'              => $this->request->getVar('id_satuan'),
            'qty_1'            => $this->request->getVar('qty_1'),
            'qty_2'            => $this->request->getVar('qty_2'),
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
}
