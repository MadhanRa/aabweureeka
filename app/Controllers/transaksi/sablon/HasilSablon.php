<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelSatuan;
use App\Models\ModelBahanSablon;
use App\Models\ModelHasilSablon;
use App\Models\ClosedPeriodsModel;
use App\Models\ModelSetupsupplier;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class HasilSablon extends ResourceController
{
    protected $objLokasi;
    protected $objSatuan;
    protected $objSetupsupplier;
    protected $objHasilSablon;
    protected $objBahanSablon;
    protected $closedPeriodsModel;
    protected $db;
     
    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objLokasi = new ModelLokasi();
       $this->objSatuan = new ModelSatuan();
       $this->objSetupsupplier = new ModelSetupsupplier();
       $this->objHasilSablon = new ModelHasilSablon();
       $this->objBahanSablon = new ModelBahanSablon();
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
        $data['dthasilsablon'] = $this->objHasilSablon->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupsupplier'] = $this->objSetupsupplier->getAll();
        return view('hasilsablon/index', $data);
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
        $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();

        // Load view formulir
        return view('hasilsablon/new', $data);
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
        $disc_1 = floatval($this->request->getVar('disc_1'));
        $disc_2 = floatval($this->request->getVar('disc_2'));
        $disc_cash = floatval($this->request->getVar('disc_cash'));
        $ppn = floatval($this->request->getVar('ppn'));

        // Hitung jml_harga
        $jml_harga = (($qty_1 + $qty_2) * $harga_satuan);  // Menghitung harga total berdasarkan qty_1, qty_2, dan harga_satuan

        // Hitung diskon bertingkat
        $totalAfterDisc1 = $jml_harga - (($jml_harga * $disc_1) / 100);  // Diskon pertama
        $totalAfterDisc2 = $totalAfterDisc1 - (($totalAfterDisc1 * $disc_2) / 100);  // Diskon kedua

        // Menghitung sub_total setelah diskon cash
        $sub_total = $totalAfterDisc2 - (($totalAfterDisc2 * $disc_cash) / 100);

        // Menghitung grand total setelah PPN
        $grand_total = $sub_total + (($sub_total * $ppn) / 100);

        // Ambil data yang diinputkan
        $data = [
        'nota_sablon' => $this->request->getVar('nota_sablon'),
        'bahan_sablon' => $this->request->getVar('bahan_sablon'),
        'id_lokasi' => $this->request->getVar('id_lokasi'),
        'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
        'terpakai' => $this->request->getVar('terpakai'),
        'rusak' => $this->request->getVar('rusak'),
        'nama_stock' => $this->request->getVar('nama_stock'),
        'id_satuan' => $this->request->getVar('id_satuan'),
        'qty_1' => $qty_1,
        'qty_2' => $qty_2,
        'harga_satuan' => $harga_satuan,
        'jml_harga' => $jml_harga,
        'tanggal' => $this->request->getVar('tanggal'),
    ];
        $this->db->table('hasilsablon1')->insert($data);

        return redirect()->to(site_url('hasilsablon'))->with('Sukses', 'Data Berhasil Disimpan');
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
    $dthasilsablon = $this->objHasilSablon->find($id);

    // Cek jika data tidak ditemukan
    if (!$dthasilsablon) {
        return redirect()->to(site_url('hasilsablon'))->with('error', 'Data tidak ditemukan');
    }

    // Cek apakah tanggal data berada dalam periode tertutup
    $transactionDate = $dthasilsablon->tanggal;
    if ($this->closedPeriodsModel->isPeriodClosed($transactionDate)) {
        return redirect()->to(site_url('hasilsablon'))->with('error', 'Akses edit dibatasi pada periode yang tertutup');
    }

    // Lanjutkan jika semua pengecekan berhasil
    $data['dthasilsablon'] = $dthasilsablon;
    $data['dtlokasi'] = $this->objLokasi->findAll();
    $data['dtsatuan'] = $this->objSatuan->findAll();
    $data['dtsetupsupplier'] = $this->objSetupsupplier->findAll();
    return view('hasilsablon/edit', $data);
}

// Tambahkan fungsi ini di dalam controller HasilSablon
private function getUserGroup($userId)
{
    $builder = $this->db->table('auth_groups_users');
    $builder->select('group_id');
    $builder->where('user_id', $userId);
    $query = $builder->get();

    if ($query->getNumRows() > 0) {
        // Ambil group_id pertama
        return $query->getRow()->group_id; 
    }

    return null; // Jika tidak ada grup ditemukan
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
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses');
        }
    
        // Ambil nilai dari form dan pastikan menjadi angka
        $qty_1 = floatval($this->request->getVar('qty_1'));
        $qty_2 = floatval($this->request->getVar('qty_2'));
        $harga_satuan = floatval($this->request->getVar('harga_satuan'));

        // Hitung jml_harga
        $jml_harga = ($qty_1 + $qty_2) * $harga_satuan;  // Pastikan nilai ini dihitung ulang

        // Ambil data yang diinputkan
        $data = [
        'nota_sablon' => $this->request->getVar('nota_sablon'),
        'bahan_sablon' => $this->request->getVar('bahan_sablon'),
        'id_lokasi' => $this->request->getVar('id_lokasi'),
        'id_setupsupplier' => $this->request->getVar('id_setupsupplier'),
        'terpakai' => $this->request->getVar('terpakai'),
        'rusak' => $this->request->getVar('rusak'),
        'nama_stock' => $this->request->getVar('nama_stock'),
        'id_satuan' => $this->request->getVar('id_satuan'),
        'qty_1' => $qty_1,
        'qty_2' => $qty_2,
        'harga_satuan' => $harga_satuan,
        'jml_harga' => $jml_harga,  // Pastikan jml_harga terbaru disertakan
        'tanggal' => $this->request->getVar('tanggal'),
    ];
    
        // Update data di database
        $this->objHasilSablon->update($id, $data);
    
        return redirect()->to(site_url('hasilsablon'))->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    
    
     public function getDateById($id)
     {
         // Ambil data berdasarkan ID dari tabel yang sesuai
         $data = $this->db->table('hasilsablon1')->where('id_sablon', $id)->get()->getRowArray();
         
         // Kembalikan tanggal jika ada, jika tidak, kembalikan null
         return $data ? $data['tanggal'] : null; // Ganti 'tanggal' dengan nama kolom yang sesuai
     }
     
    
     public function delete($id = null)
    {
        
        $this->db->table('hasilsablon1')->where(['id_sablon' => $id])->delete();
        return redirect()->to(site_url('hasilsablon'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
