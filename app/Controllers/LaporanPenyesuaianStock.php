<?php

namespace App\Controllers;

use App\Models\setup_persediaan\ModelGroup;
use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\setup\ModelSetupbank;
use App\Models\transaksi\ModelPenyesuaianStock;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class LaporanPenyesuaianStock extends ResourceController
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
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        // Panggil model untuk mendapatkan data laporan
        $dtpenyesuaianstock = $this->objPenyesuaianStock->get_laporan($tglawal, $tglakhir);

        // $data['dtpembelian'] = $rowdata;

        $data['dtpenyesuaianstock'] = $dtpenyesuaianstock;
        $data['dtgroup'] = $this->objGroup->findAll();
        $data['dtlokasi'] = $this->objLokasi->findAll();
        $data['dtsatuan'] = $this->objSatuan->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        $data['dtpenyesuaianstock'] = $this->objPenyesuaianStock->findAll();
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        return view('laporanpenyesuaianstock/index', $data);
    }

    public function printPDF($id = null)
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        // Panggil model untuk mendapatkan data laporan
        $dtpenyesuaianstock = $this->objPenyesuaianStock->get_laporan($tglawal, $tglakhir);

        $data['dtpenyesuaianstock'] = $dtpenyesuaianstock;
        $data['tglawal'] = $tglawal; // Tambahkan variabel ini
        $data['tglakhir'] = $tglakhir; // Tambahkan variabel ini
        $data['dtgroup'] = $this->objGroup->findAll();
        $data['dtlokasi'] = $this->objLokasi->findAll();
        $data['dtsatuan'] = $this->objSatuan->findAll();
        $data['dtsetupbank'] = $this->objSetupbank->findAll();
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('laporanpenyesuaianstock/printPDF', $data);
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
        $pdf->Output('laporan_penyesuaian_stock.pdf', 'D');
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
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
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
        //
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
        //
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
        //
    }
}
