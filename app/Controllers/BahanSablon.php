<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelSatuan;
use App\Models\ModelBahanSablon;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class BahanSablon extends ResourceController
{
    protected $objLokasi;
    protected $objSatuan;
    protected $objBahanSablon;
    protected $db;
    
    
    //  INISIALISASI OBJECT DATA
   function __construct()
   {
       $this->objLokasi = new ModelLokasi();
       $this->objSatuan = new ModelSatuan();
       $this->objBahanSablon = new ModelBahanSablon();
       $this->db = \Config\Database::connect();
       
   }
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
         // Menggunakan Query Builder untuk join tabel lokasi1 dan satuan1
         $data['dtbahansablon'] = $this->objBahanSablon->getAll();
         $data['dtlokasi'] = $this->objLokasi->getAll();
         $data['dtsatuan'] = $this->objSatuan->getAll();
         return view('bahansablon/index', $data);
    }

    public function printPDF($id = null)
    {
        // Jika $id tidak diberikan, ambil semua data
        if ($id === null) {
            $data['dtbahansablon'] = $this->objBahanSablon->getAll();
        } else {
            // Jika $id diberikan, ambil data berdasarkan ID dengan join
            $data['dtbahansablon'] = $this->objBahanSablon->getById($id);
            if (empty($data['dtbahansablon'])) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
        }
    
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('bahansablon/printPDF', $data);
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
        $pdf->Output('bahandisablon.pdf', 'I');
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

        // Load view formulir
        return view('bahansablon/new', $data);
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
            'nota_pindah' => $this->request->getVar('nota_pindah'),
            'id_lokasi_asal'   => $this->request->getVar('id_lokasi_asal'),
            'id_lokasi_tujuan' => $this->request->getVar('id_lokasi_tujuan'),
            'nama_stock'       => $this->request->getVar('nama_stock'),
            'id_satuan'        => $this->request->getVar('id_satuan'),
            'qty_1'            => $this->request->getVar('qty_1'),
            'qty_2'            => $this->request->getVar('qty_2'),
            'tanggal'          => $this->request->getVar('tanggal'),
        ];
        $this->db->table('bahansablon1')->insert($data);

        return redirect()->to(site_url('bahansablon'))->with('Sukses', 'Data Berhasil Disimpan');
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
        $dtbahansablon = $this->objBahanSablon->find($id);

        // Cek jika data tidak ditemukan
        if (!$dtbahansablon) {
            return redirect()->to(site_url('bahansablon'))->with('error', 'Data tidak ditemukan');
        }


        // Lanjutkan jika semua pengecekan berhasil
        $data['dtbahansablon'] = $dtbahansablon;
        $data['dtlokasi'] = $this->objLokasi->findAll();
        $data['dtsatuan'] = $this->objSatuan->findAll();
        return view('bahansablon/edit', $data);
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
    $existingData = $this->objBahanSablon->find($id);
    if (!$existingData) {
        return redirect()->to(site_url('bahansablon'))->with('error', 'Data tidak ditemukan');
    }

    // Ambil data yang diinputkan dari form
    $data = [
        'id_bahan' => $this->request->getVar('id_bahan'),
        'nota_pindah' => $this->request->getVar('nota_pindah'),
        'id_lokasi_asal'   => $this->request->getVar('id_lokasi_asal'),
        'id_lokasi_tujuan' => $this->request->getVar('id_lokasi_tujuan'),
        'nama_stock'       => $this->request->getVar('nama_stock'),
        'id_satuan'        => $this->request->getVar('id_satuan'),
        'qty_1'            => $this->request->getVar('qty_1'),
        'qty_2'            => $this->request->getVar('qty_2'),
        'tanggal'          => $this->request->getVar('tanggal'),
    ];

    // Update data berdasarkan ID
    $this->objBahanSablon->update($id, $data);

    return redirect()->to(site_url('bahansablon'))->with('success', 'Data berhasil diupdate.');
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
        $this->db->table('bahansablon1')->where(['id_bahan' => $id])->delete();
        return redirect()->to(site_url('bahansablon'))->with('Sukses', 'Data Berhasil Dihapus');
    }
}
