<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelSatuan;
use App\Models\ModelSetupbank;
use App\Models\ModelPemakaianBahan;
use App\Models\ModelKelompokproduksi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class PemakaianBahan extends ResourceController
{
    protected $objLokasi;
    protected $objSatuan;
    protected $objSetupbank;
    protected $objPemakaianBahan;
    protected $objKelompokproduksi;
    protected $db;
    
    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objLokasi = new ModelLokasi();
       $this->objSatuan = new ModelSatuan();
       $this->objKelompokproduksi = new ModelKelompokproduksi();
       $this->objPemakaianBahan = new ModelPemakaianBahan();
       $this->objSetupbank = new ModelSetupbank();
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
        // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
        $data['dtpemakaianbahan'] = $this->objPemakaianBahan->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtkelompokproduksi'] = $this->objKelompokproduksi->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        return view('pemakaianbahan/index', $data);
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
         // Mengambil data lokasi, satuan, dan stock untuk dropdown
         $data['dtlokasi'] = $this->objLokasi->findAll();
         $data['dtsatuan'] = $this->objSatuan->findAll();
         $data['dtkelompokproduksi'] = $this->objKelompokproduksi->findAll();
         $data['dtsetupbank'] = $this->objSetupbank->findAll();
         $data['dtpemakaianbahan'] = $this->objPemakaianBahan->findAll();
 
         // Load view formulir
         return view('pemakaianbahan/new', $data);
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
            'id_bahan' => $this->request->getVar('id_bahan'),
            'nota_bahan' => $this->request->getVar('nota_bahan'),
            'id_lokasi'   => $this->request->getVar('id_lokasi'),
            'id_kelproduksi' => $this->request->getVar('id_kelproduksi'),
            'id_setupbank' => $this->request->getVar('id_setupbank'),
            'nama_stock'       => $this->request->getVar('nama_stock'),
            'id_satuan'        => $this->request->getVar('id_satuan'),
            'qty_1'            => $this->request->getVar('qty_1'),
            'qty_2'            => $this->request->getVar('qty_2'),
            'tanggal'          => $this->request->getVar('tanggal'),
        ];
        $this->db->table('bahan1')->insert($data);

        return redirect()->to(site_url('pemakaianbahan'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtpemakaianbahan = $this->objPemakaianBahan->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtpemakaianbahan) {
            return redirect()->to(site_url('bahansablon'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtpemakaianbahan'] = $dtpemakaianbahan;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtkelompokproduksi'] = $this->objKelompokproduksi->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        return view('pemakaianbahan/edit', $data);
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
    $existingData = $this->objPemakaianBahan->find($id);
    if (!$existingData) {
        return redirect()->to(site_url('pemakaianbahan'))->with('error', 'Data tidak ditemukan');
    }

    // Ambil data yang diinputkan dari form
    $data = [
        'id_bahan' => $this->request->getVar('id_bahan'),
            'nota_bahan' => $this->request->getVar('nota_bahan'),
            'id_lokasi'   => $this->request->getVar('id_lokasi'),
            'id_kelproduksi' => $this->request->getVar('id_kelproduksi'),
            'id_setupbank' => $this->request->getVar('id_setupbank'),
            'nama_stock'       => $this->request->getVar('nama_stock'),
            'id_satuan'        => $this->request->getVar('id_satuan'),
            'qty_1'            => $this->request->getVar('qty_1'),
            'qty_2'            => $this->request->getVar('qty_2'),
            'tanggal'          => $this->request->getVar('tanggal'),
    ];

    // Update data berdasarkan ID
    $this->objPemakaianBahan->update($id, $data);

    return redirect()->to(site_url('pemakaianbahan'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('bahan1')->where(['id_bahan' => $id])->delete();
        return redirect()->to(site_url('pemakaianbahan'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
