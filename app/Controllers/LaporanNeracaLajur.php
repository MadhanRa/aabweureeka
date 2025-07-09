<?php

namespace App\Controllers;

use App\Models\setup\ModelSetupBuku;
use App\Models\transaksi\ModelRiwayatTransaksi;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanNeracaLajur extends BaseController
{
    protected $modelSetupBuku;
    protected $modelRiwayatTransaksi;
    protected $db;
    function __construct()
    {
        $this->modelSetupBuku = new ModelSetupBuku();
        $this->modelRiwayatTransaksi = new ModelRiwayatTransaksi();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';

        // Panggil model untuk mendapatkan data laporan
        $laporan_neraca = $this->modelRiwayatTransaksi->getLaporanNeraca($tglawal, $tglakhir);

        // Inisialisasi variabel untuk perhitungan
        $debit_awal_total = 0;
        $kredit_awal_total = 0;
        $debit_total = 0;
        $kredit_total = 0;
        $debit_akhir_total = 0;
        $kredit_akhir_total = 0;

        // Lakukan perhitungan jika ada data laporan
        if (!empty($laporan_neraca)) {
            // Jumlahkan semua nilai dari setiap baris
            foreach ($laporan_neraca as $item) {
                // Jumlahkan saldo awal
                $debit_awal_total += $item->awal_debit;
                $kredit_awal_total += $item->awal_kredit;

                // Jumlahkan mutasi
                $debit_total += $item->debit;
                $kredit_total += $item->kredit;

                // Jumlahkan saldo akhir positif saja
                if ($item->saldo_debit > 0) {
                    $debit_akhir_total += $item->saldo_debit;
                }
                if ($item->saldo_kredit > 0) {
                    $kredit_akhir_total += $item->saldo_kredit;
                }
            }
        }


        // Ambil data tambahan untuk dropdown filter
        $data = [
            'dt_laporan_neraca'    => $laporan_neraca,
            'debit_awal_total'     => $debit_awal_total,
            'kredit_awal_total'    => $kredit_awal_total,
            'debit_total'          => $debit_total,
            'kredit_total'         => $kredit_total,
            'debit_akhir_total'    => $debit_akhir_total,
            'kredit_akhir_total'   => $kredit_akhir_total,
            'tglawal'              => $tglawal,
            'tglakhir'             => $tglakhir
        ];

        return view('laporan_keuangan/laporan_neraca_lajur/index', $data);
    }

    public function cariBuku($id_setupbuku)
    {
        $buku = $this->modelSetupBuku->getBukuById($id_setupbuku);
        if ($buku) {
            return $this->response->setJSON([
                'status' => true,
                'data' => $buku
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Buku tidak ditemukan'
            ]);
        }
    }

    // public function printPDF()
    // {
    //     $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
    //     $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
    //     $id_setupbuku = $this->request->getVar('id_setupbuku') ? $this->request->getVar('id_setupbuku') : '';
    //     $rekening = $this->request->getVar('rekening') ? $this->request->getVar('rekening') : '';

    //     // Panggil model untuk mendapatkan data laporan
    //     $transaksi_buku_besar = $this->modelRiwayatTransaksi->getLaporanBuku($tglawal, $tglakhir, $id_setupbuku);

    //     // Inisialisasi variabel untuk perhitungan
    //     $saldo_awal_total = 0;
    //     $kredit_awal_total = 0;
    //     $debit_total = 0;
    //     $kredit_total = 0;
    //     $debit_akhir_total = 0;
    //     $kredit_akhir_total = 0;

    //     // Lakukan perhitungan jika ada data transaksi
    //     if (!empty($transaksi_buku_besar)) {
    //         // Hitung total debit dan kredit dari semua transaksi
    //         foreach ($transaksi_buku_besar as $transaksi) {
    //             $debit_total += $transaksi->debit;
    //             $kredit_total += $transaksi->kredit;
    //         }

    //         // Tentukan saldo awal berdasarkan transaksi pertama
    //         $first_transaction = $transaksi_buku_besar[0];
    //         $saldo_awal = $first_transaction->saldo_setelah - $first_transaction->debit + $first_transaction->kredit;

    //         // Tentukan jenis saldo awal (debit atau kredit)
    //         if ($saldo_awal >= 0) {
    //             $saldo_awal_total = $saldo_awal;
    //             $kredit_awal_total = 0;
    //         } else {
    //             $saldo_awal_total = 0;
    //             $kredit_awal_total = abs($saldo_awal);
    //         }

    //         // Tentukan saldo akhir berdasarkan saldo awal + debit - kredit
    //         $saldo_akhir = $saldo_awal + $debit_total - $kredit_total;

    //         // Tentukan jenis saldo akhir (debit atau kredit)
    //         if ($saldo_akhir >= 0) {
    //             $debit_akhir_total = $saldo_akhir;
    //             $kredit_akhir_total = 0;
    //         } else {
    //             $debit_akhir_total = 0;
    //             $kredit_akhir_total = abs($saldo_akhir);
    //         }
    //     }

    //     // Ambil nama rekening buku
    //     $nama_buku = '';
    //     if (!empty($id_setupbuku)) {
    //         $buku = $this->modelSetupBuku->getBukuById($id_setupbuku);
    //         if ($buku) {
    //             $nama_buku = $buku->nama_setupbuku;
    //         }
    //     } else {
    //         $nama_buku = 'Semua Rekening';
    //     }

    //     // Load view untuk PDF
    //     $html = view('laporan_keuangan/laporan_buku_besar/printPDF', [
    //         'dt_transaksi_buku'   => $transaksi_buku_besar,
    //         'saldo_awal_total'    => $saldo_awal_total,
    //         'kredit_awal_total'   => $kredit_awal_total,
    //         'debit_total'         => $debit_total,
    //         'kredit_total'        => $kredit_total,
    //         'debit_akhir_total'   => $debit_akhir_total,
    //         'kredit_akhir_total'  => $kredit_akhir_total,
    //         'tglawal'             => $tglawal,
    //         'tglakhir'            => $tglakhir,
    //         'rekening'            => $nama_buku,
    //     ]);

    //     // Inisialisasi TCPDF
    //     $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
    //     $pdf->SetCreator(PDF_CREATOR);
    //     $pdf->SetAuthor('AAB Weureeka');
    //     $pdf->SetTitle('Laporan Buku Besar');
    //     $pdf->SetSubject('Laporan Buku Besar');
    //     $pdf->SetKeywords('TCPDF, PDF, laporan, buku besar');

    //     // Set header dan footer
    //     $pdf->setPrintHeader(false);
    //     $pdf->setPrintFooter(false);

    //     // Set margins
    //     $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    //     // Add a page
    //     $pdf->AddPage();

    //     // Set content
    //     $pdf->writeHTML($html, true, false, true, false, '');

    //     // Output PDF
    //     $pdf->Output('laporan_buku_besar.pdf', 'I');
    // }
}
