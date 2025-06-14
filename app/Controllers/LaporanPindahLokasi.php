<?php

namespace App\Controllers;

use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\transaksi\PeriodsModels;
use App\Models\transaksi\ModelPindahLokasi;
use App\Models\transaksi\ClosedPeriodsModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use TCPDF;

class LaporanPindahLokasi extends ResourceController
{

    protected $objPenyesuaianStock;
    protected $objLokasi;
    protected $objSatuan;
    protected $objPindahLokasi;
    protected $objClosedPeriods;
    protected $PeriodsModels;
    protected $db;
    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objPindahLokasi = new ModelPindahLokasi();
        $this->objClosedPeriods = new ClosedPeriodsModel();
        $this->PeriodsModels = new PeriodsModels();
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
        $dtpindahlokasi = $this->objPindahLokasi->get_laporan($tglawal, $tglakhir);

        // $data['dtpembelian'] = $rowdata;

        $data['dtpindahlokasi'] = $dtpindahlokasi;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;
        return view('laporanpindahlokasi/index', $data);
    }

    public function printPDF($tglawal = null, $tglakhir = null)
    {
        // If the dates are provided in the URL, use them; otherwise, use default values from the form (if applicable)
        $tglawal = $tglawal ?: $this->request->getVar('tglawal');
        $tglakhir = $tglakhir ?: $this->request->getVar('tglakhir');

        // Panggil model untuk mendapatkan data laporan berdasarkan tanggal
        $dtpindahlokasi = $this->objPindahLokasi->get_laporan($tglawal, $tglakhir);

        // Passing data to the view for the PDF generation
        $data['dtpindahlokasi'] = $dtpindahlokasi;
        $data['dtlokasi'] = $this->objLokasi->getAll();
        $data['dtsatuan'] = $this->objSatuan->getAll();
        $data['tglawal'] = $tglawal;
        $data['tglakhir'] = $tglakhir;

        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('laporanpindahlokasi/printPDF', $data);

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
        $pdf->Output('laporan_pindah_lokasi.pdf', 'D');
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
