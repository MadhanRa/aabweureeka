<?php

namespace App\Controllers;

use App\Models\ModelLokasi;
use App\Models\ModelSatuan;
use App\Models\ClosedPeriodsModel;
use App\Models\ModelHasilProduksi;
use App\Controllers\BaseController;
use App\Models\ModelKelompokproduksi;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanHasilProduksi extends BaseController
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
    
    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir')? $this->request->getVar('tglakhir') : '';

        // Panggil model untuk mendapatkan data laporan
        $dthasilproduksi = $this->objHasilProduksi->get_laporan($tglawal, $tglakhir);
        
        // $data['dtpembelian'] = $rowdata;
        $data = [
            'dthasilproduksi'       => $dthasilproduksi,
            'dtlokasi'              => $this->objLokasi->getAll(),
            'dtsatuan'              => $this->objSatuan->getAll(),
            'dtkelompokproduksi'    => $this->objKelompokproduksi->getAll(),
            'tglawal'               => $tglawal,
            'tglakhir'              => $tglakhir,
        ];
        return view('laporanhasilproduksi/index', $data);
    }

    public function printPDF()
    {
        $tglawal = $this->request->getVar('tglawal') ?? '';
        $tglakhir = $this->request->getVar('tglakhir') ?? '';
    
        // Ambil laporan dari model
        $dthasilproduksi = $this->objHasilProduksi->get_laporan($tglawal, $tglakhir);
    
        // Konfigurasi TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
    
        // Tambahkan konten ke PDF
        $html = view('laporanhasilproduksi/printPDF', [
            'dthasilproduksi' => $dthasilproduksi,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
        ]);
        $pdf->writeHTML($html);
    
        // Output PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Laporan_Hasil_Produksi.pdf', 'I');
    }
}
