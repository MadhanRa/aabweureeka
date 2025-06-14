<?php

namespace App\Controllers;

use App\Models\transaksi\akuntansi\ModelKasKecil;
use App\Models\setup\ModelAntarmuka;
use App\Controllers\BaseController;
use App\Models\setup\ModelKelompokproduksi;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanKasKecil extends BaseController
{
    protected
        $objKasKecil,
        $objAntarmuka,
        $objKelompokproduksi,
        $db;
    //  INISIALISASI OBJECT DATA
    function __construct()
    {
        $this->objKasKecil = new ModelKasKecil();
        $this->objAntarmuka = new ModelAntarmuka();
        $this->objKelompokproduksi = new ModelKelompokproduksi();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $rekeningkas = $this->request->getVar('rekeningkas') ? $this->request->getVar('rekeningkas') : '';
        $kelproduksi = $this->request->getVar('kelproduksi') ? $this->request->getVar('kelproduksi') : '';

        // Ambil data untuk dropdown filter
        $dataRekeningKas = $this->objAntarmuka->findAll(); // Data rekening kas
        $dataKelompokProduksi = $this->objKelompokproduksi->findAll(); // Data kelompok produksi

        // Panggil model untuk mendapatkan data laporan
        $dtkaskecil = $this->objKasKecil->get_laporan($tglawal, $tglakhir, $rekeningkas, $kelproduksi);

        // Hitung total mutasi
        $totalMutasi = array_sum(array_column($dtkaskecil, 'rp'));

        // Siapkan data untuk view
        $data = [
            'dtkaskecil' => $dtkaskecil,
            'dataRekeningKas' => $dataRekeningKas,
            'dataKelompokProduksi' => $dataKelompokProduksi,
            'totalMutasi' => $totalMutasi,
            'filter' => [
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir,
                'rekeningkas' => $rekeningkas,
                'kelproduksi' => $kelproduksi,
            ],
        ];
        return view('laporankaskecil/index', $data);
    }
    public function printPDF()
    {
        $tglawal = $this->request->getVar('tglawal') ?? '';
        $tglakhir = $this->request->getVar('tglakhir') ?? '';
        $rekeningkas = $this->request->getVar('rekeningkas') ?? '';
        $kelproduksi = $this->request->getVar('kelproduksi') ?? '';

        // Ambil data laporan
        $dtkaskecil = $this->objKasKecil->get_laporan($tglawal, $tglakhir, $rekeningkas, $kelproduksi);

        // Konfigurasi TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // Buat konten HTML untuk PDF
        $html = view('laporankaskecil/printPDF', [
            'dtkaskecil' => $dtkaskecil,
            'filter' => [
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir,
            ],
        ]);

        // Tambahkan konten ke PDF
        $pdf->writeHTML($html);

        // Output PDF
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Laporan_Kas_Kecil.pdf', 'I');
    }
}
