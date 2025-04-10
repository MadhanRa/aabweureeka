<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelSatuan;
use App\Models\ClosedPeriodsModel;
use App\Models\ModelHasilProduksi;
use App\Models\ModelKelompokproduksi;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class HasilProduksi extends ResourceController
{
    protected
    $objLokasi,
    $objSatuan, 
    $objKelompokproduksi,
    $objHasilProduksi,
    $closedPeriodsModel,
    $db;
    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objLokasi = new ModelLokasi();
       $this->objSatuan = new ModelSatuan();
       $this->objKelompokproduksi = new ModelKelompokproduksi();
       $this->objHasilProduksi = new ModelHasilProduksi();
       $this->closedPeriodsModel = new ClosedPeriodsModel();
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
        $data['dthasilproduksi'] = $this->objHasilProduksi->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtkelompokproduksi'] = $this->objKelompokproduksi->getAll();
        return view('hasilproduksi/index', $data);
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

        // Load view formulir
        return view('hasilproduksi/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
       
        // Ambil nilai dari form dan pastikan menjadi angka
        $qty_1 = floatval($this->request->getVar('qty_1'));
        $qty_2 = floatval($this->request->getVar('qty_2'));  // Ambil qty_2
        $harga_satuan = floatval($this->request->getVar('harga_satuan'));

        // Hitung jml_harga
        $jml_harga = (($qty_1 + $qty_2) * $harga_satuan);

        $data = [
            'id_produksi' => $this->request->getVar('id_produksi'),
            'nota_produksi' => $this->request->getVar('nota_produksi'),
            'id_lokasi'   => $this->request->getVar('id_lokasi'),
            'id_kelproduksi' => $this->request->getVar('id_kelproduksi'),
            'nama_stock'       => $this->request->getVar('nama_stock'),
            'id_satuan'        => $this->request->getVar('id_satuan'),
            'qty_1' => $qty_1,
            'qty_2' => $qty_2,
            'harga_satuan' => $harga_satuan,
            'jml_harga' => $jml_harga,
            'tanggal'          => $this->request->getVar('tanggal'),
        ];
        $this->db->table('hasilproduksi1')->insert($data);

        return redirect()->to(site_url('hasilproduksi'))->with('Sukses', 'Data Berhasil Disimpan');
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
    $dthasilproduksi = $this->objHasilProduksi->find($id);

    // Cek jika data tidak ditemukan
    if (!$dthasilproduksi) {
        return redirect()->to(site_url('hasilproduksi'))->with('error', 'Data tidak ditemukan');
    }

    // Cek apakah tanggal data berada dalam periode tertutup
    $transactionDate = $dthasilproduksi->tanggal;
    if ($this->closedPeriodsModel->isPeriodClosed($transactionDate)) {
        return redirect()->to(site_url('hasilproduksi'))->with('error', 'Akses edit dibatasi pada periode yang tertutup');
    }

    // Lanjutkan jika semua pengecekan berhasil
    $data['dthasilproduksi'] = $dthasilproduksi;
    $data['dtlokasi'] = $this->objLokasi->findAll();
    $data['dtsatuan'] = $this->objSatuan->findAll();
    $data['dtkelompokproduksi'] = $this->objKelompokproduksi->findAll();

    return view('hasilproduksi/edit', $data);
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

        // Ambil nilai dari form dan pastikan menjadi angka
        $qty_1 = floatval($this->request->getVar('qty_1'));
        $qty_2 = floatval($this->request->getVar('qty_2'));  // Ambil qty_2
        $harga_satuan = floatval($this->request->getVar('harga_satuan'));

        // Hitung jml_harga
        $jml_harga = (($qty_1 + $qty_2) * $harga_satuan);

        $data = [
            'id_produksi' => $this->request->getVar('id_produksi'),
            'nota_produksi' => $this->request->getVar('nota_produksi'),
            'id_lokasi'   => $this->request->getVar('id_lokasi'),
            'id_kelproduksi' => $this->request->getVar('id_kelproduksi'),
            'nama_stock'       => $this->request->getVar('nama_stock'),
            'id_satuan'        => $this->request->getVar('id_satuan'),
            'qty_1' => $qty_1,
            'qty_2' => $qty_2,
            'harga_satuan' => $harga_satuan,
            'jml_harga' => $jml_harga,
            'tanggal'          => $this->request->getVar('tanggal'),
        ];

        // Update data berdasarkan ID
        $this->objHasilProduksi->update($id, $data);

        return redirect()->to(site_url('hasilproduksi'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('hasilproduksi1')->where(['id_produksi' => $id])->delete();
        return redirect()->to(site_url('hasilproduksi'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
