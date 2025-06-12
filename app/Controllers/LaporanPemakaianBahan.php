<?php

namespace App\Controllers;

use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup_persediaan\ModelSatuan;
use App\Models\setup\ModelSetupbank;
use App\Controllers\BaseController;
use App\Models\transaksi\ModelPemakaianBahan;
use App\Models\setup\ModelKelompokproduksi;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanPemakaianBahan extends BaseController
{
    protected $objLokasi;
    protected $objSatuan;
    protected $objSetupbank;
    protected $objPemakaianBahan;
    protected $objKelompokproduksi;
    protected $db;

    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSatuan = new ModelSatuan();
        $this->objKelompokproduksi = new ModelKelompokproduksi();
        $this->objPemakaianBahan = new ModelPemakaianBahan();
        $this->objSetupbank = new ModelSetupbank();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        // Panggil model untuk mendapatkan data laporan
        $dtpemakaianbahan = $this->objPemakaianBahan->get_laporan($tglawal, $tglakhir);

        // $data['dtpembelian'] = $rowdata;
        $data = [
            'dtpemakaianbahan'      => $dtpemakaianbahan,
            'dtlokasi'              => $this->objLokasi->findAll(),
            'dtsatuan'              => $this->objSatuan->findAll(),
            'dtkelompokproduksi'    => $this->objKelompokproduksi->findAll(),
            'dtsetupbank'           => $this->objSetupbank->findAll(),
            'tglawal'               => $tglawal,
            'tglakhir'              => $tglakhir,
        ];
        return view('laporanpemakaianbahan/index', $data);
    }

    public function printPDF()
    {
        $tglawal = $this->request->getVar('tglawal') ?? '';
        $tglakhir = $this->request->getVar('tglakhir') ?? '';

        // Ambil laporan dari model
        $dtpemakaianbahan = $this->objPemakaianBahan->get_laporan($tglawal, $tglakhir);

        // Konfigurasi TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // Tambahkan konten ke PDF
        $html = view('laporanpemakaianbahan/printPDF', [
            'dtpemakaianbahan' => $dtpemakaianbahan,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
        ]);
        $pdf->writeHTML($html);

        // Output PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Laporan_Pemakaian_Bahan.pdf', 'I');
    }
}
