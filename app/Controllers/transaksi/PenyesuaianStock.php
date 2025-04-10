<?php

namespace App\Controllers;

use App\Models\ModelGroup;
use App\Models\ModelLokasi;
use App\Models\ModelPenyesuaianStock;
use App\Models\ModelSatuan;
use App\Models\ModelSetupbank;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class PenyesuaianStock extends ResourceController
{
    protected $objLokasi;
    protected $objSatuan;
    protected $objSetupbank;
    protected $objGroup;
    protected $objPenyesuaianStock;
    protected $db;
    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objSetupbank = new ModelSetupbank();
        $this->objGroup = new ModelGroup();
        $this->objPenyesuaianStock = new ModelPenyesuaianStock();
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
        $data['dtgroup'] = $this->objGroup->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        $data['dtpenyesuaianstock'] = $this->objPenyesuaianStock->getAll();

        return view('penyesuaianstock/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtpenyesuaianstock'] = $this->objPenyesuaianStock->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtpenyesuaianstock'] = $this->objPenyesuaianStock->getById($id);
            if (empty($data['dtpenyesuaianstock'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }
        
        $data['dtgroup'] = $this->objGroup->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('penyesuaianstock/printPDF', $data);
        // echo $html;
        // exit; // Jika perlu debugging
    
        // Buat PDF baru
        $pdf = new TCPDF('landscape', PDF_UNIT, 'A4', true, 'UTF-8', false);
    
        // Hapus header/footer default
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
    
        // Set font
        $pdf->SetFont('helvetica', '', 12);
    
        // Tambah halaman baru
        $pdf->AddPage();
    
        // Cetak konten menggunakan WriteHTML
        $pdf->writeHTML($html, true, false, true, false, '');
    
        // Set tipe respons menjadi PDF
        $this->response->setContentType('application/pdf');
        $pdf->Output('penyesuaian_stock.pdf', 'D');
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
         // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
         $data['dtgroup'] = $this->objGroup->getAll();
         $data['dtlokasi'] = $this->objLokasi->getAll();
         $data['dtsatuan'] = $this->objSatuan->getAll();
         $data['dtsetupbank'] = $this->objSetupbank->getAll();
         $data['dtpenyesuaianstock'] = $this->objPenyesuaianStock->getAll();
 
         return view('penyesuaianstock/new', $data);
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
            'tanggal' => $this->request->getVar('tanggal'),
            'nota' => $this->request->getVar('nota'),
            'id_setupbank' => $this->request->getVar('id_setupbank'),
            'id_lokasi' => $this->request->getVar('id_lokasi'),
            'id_group' => $this->request->getVar('id_group'),
            'nama_stock' => $this->request->getVar('nama_stock'),
            'id_satuan' => $this->request->getVar('id_satuan'),
            'saldo' => $this->request->getVar('saldo'),
            'qty_1' => $this->request->getVar('qty_1'),
            'qty_2' => $this->request->getVar('qty_2'),
            'penyesuaian' => $this->request->getVar('penyesuaian'),
            'keterangan' => $this->request->getVar('keterangan'),
        ];
        $this->db->table('penyesuaianstock1')->insert($data);

        return redirect()->to(site_url('penyesuaianstock'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtpenyesuaianstock = $this->objPenyesuaianStock->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtpenyesuaianstock) {
            return redirect()->to(site_url('penyesuaianstock'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtpenyesuaianstock'] = $dtpenyesuaianstock;
        $data['dtgroup'] = $this->objGroup->getAll();
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['dtsetupbank'] = $this->objSetupbank->getAll();
        return view('penyesuaianstock/edit', $data);
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
    $existingData = $this->objPenyesuaianStock->find($id);
    if (!$existingData) {
        return redirect()->to(site_url('penyesuaianstock'))->with('error', 'Data tidak ditemukan');
    }

    // Ambil data yang diinputkan dari form
    $data = [
        'tanggal' => $this->request->getVar('tanggal'),
        'nota' => $this->request->getVar('nota'),
        'id_setupbank' => $this->request->getVar('id_setupbank'),
        'id_lokasi' => $this->request->getVar('id_lokasi'),
        'id_group' => $this->request->getVar('id_group'),
        'nama_stock' => $this->request->getVar('nama_stock'),
        'id_satuan' => $this->request->getVar('id_satuan'),
        'saldo' => $this->request->getVar('saldo'),
        'qty_1' => $this->request->getVar('qty_1'),
        'qty_2' => $this->request->getVar('qty_2'),
        'penyesuaian' => $this->request->getVar('penyesuaian'),
        'keterangan' => $this->request->getVar('keterangan'),
    ];
     // Update data berdasarkan ID
     $this->objPenyesuaianStock->update($id, $data);

     return redirect()->to(site_url('penyesuaianstock'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('penyesuaianstock1')->where(['id_penyesuaianstock' => $id])->delete();
        return redirect()->to(site_url('penyesuaianstock'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
