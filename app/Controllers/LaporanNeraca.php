<?php

namespace App\Controllers;

use App\Models\setup\ModelSetupBuku;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanNeraca extends BaseController
{
    protected $modelSetupBuku;
    protected $db;
    function __construct()
    {
        $this->modelSetupBuku = new ModelSetupBuku();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $pertanggal = $this->request->getVar('pertanggal') ? $this->request->getVar('pertanggal') : '';

        $neraca = $this->modelSetupBuku->getNeraca($pertanggal);

        $total_aktiva_lancar = 0;
        $total_aktiva_tetap = 0;
        $total_akumulasi_penyusutan = 0;
        $total_aktiva_lainnya = 0;
        $total_hutang_lancar = 0;
        $total_hutang_panjang = 0;
        $total_modal = 0;
        $total_saldo_laba = 0;

        for ($i = 0; $i < 16; $i++) {
            $total_aktiva_lancar += $neraca[$i]->total_saldo_berjalan;
        }

        for ($i = 16; $i < 17; $i++) {
            $total_aktiva_tetap += $neraca[$i]->total_saldo_berjalan;
        }

        for ($i = 17; $i < 18; $i++) {
            $total_akumulasi_penyusutan += $neraca[$i]->total_saldo_berjalan;
        }

        for ($i = 18; $i < 19; $i++) {
            $total_aktiva_lainnya += $neraca[$i]->total_saldo_berjalan;
        }

        for ($i = 19; $i < 26; $i++) {
            $total_hutang_lancar += $neraca[$i]->total_saldo_berjalan;
        }

        for ($i = 26; $i < 28; $i++) {
            $total_hutang_panjang += $neraca[$i]->total_saldo_berjalan;
        }

        for ($i = 28; $i < 29; $i++) {
            $total_modal += $neraca[$i]->total_saldo_berjalan;
        }

        for ($i = 29; $i < 31; $i++) {
            $total_saldo_laba += $neraca[$i]->total_saldo_berjalan;
        }




        // // Ambil data tambahan untuk dropdown filter
        $data = [
            'pertanggal'              => $pertanggal,
            'kas' => $neraca[0]->total_saldo_berjalan,
            'bank' => $neraca[1]->total_saldo_berjalan,
            'piutang_usaha' => $neraca[2]->total_saldo_berjalan,
            'piutang_usaha_principal' => $neraca[3]->total_saldo_berjalan,
            'piutang_bg_mundur' => $neraca[4]->total_saldo_berjalan,
            'piutang_karyawan' => $neraca[5]->total_saldo_berjalan,
            'piutang_lain' => $neraca[6]->total_saldo_berjalan,
            'persediaan_bahan' => $neraca[7]->total_saldo_berjalan,
            'persediaan_packaging' => $neraca[8]->total_saldo_berjalan,
            'persediaan_barang_jadi' => $neraca[9]->total_saldo_berjalan,
            'persediaan_barang_proses' => $neraca[10]->total_saldo_berjalan,
            'persediaan_material' => $neraca[11]->total_saldo_berjalan,
            'uang_muka_pembelian' => $neraca[12]->total_saldo_berjalan,
            'pajak_dimuka' => $neraca[13]->total_saldo_berjalan,
            'biaya_dimuka' => $neraca[14]->total_saldo_berjalan,
            'biaya_praoperasional' => $neraca[15]->total_saldo_berjalan,

            'harga_perolehan' => $neraca[16]->total_saldo_berjalan,

            'akumulasi_penyusutan' => $neraca[17]->total_saldo_berjalan,

            'aktiva_tetap_lain' => $neraca[18]->total_saldo_berjalan,

            'uang_muka_penjualan' => $neraca[19]->total_saldo_berjalan,
            'hutang_usaha' => $neraca[20]->total_saldo_berjalan,
            'hutang_retur_penjualan' => $neraca[21]->total_saldo_berjalan,
            'hutang_pajak' => $neraca[22]->total_saldo_berjalan,
            'hutang_bg_mundur' => $neraca[23]->total_saldo_berjalan,
            'biaya_ymh' => $neraca[24]->total_saldo_berjalan,
            'hutang_lain' => $neraca[25]->total_saldo_berjalan,

            'hutang_bank' => $neraca[26]->total_saldo_berjalan,

            'modal_disetor' => $neraca[27]->total_saldo_berjalan,

            'saldo_laba' => $neraca[28]->total_saldo_berjalan,
            'laba_tahun' => $neraca[29]->total_saldo_berjalan,
            'laba_bulan' => $neraca[30]->total_saldo_berjalan,

            'total_aktiva_lancar' => $total_aktiva_lancar,
            'total_aktiva_tetap' => $total_aktiva_tetap,
            'total_akumulasi_penyusutan' => $total_akumulasi_penyusutan,
            'total_aktiva_lainnya' => $total_aktiva_lainnya,
            'total_hutang_lancar' => $total_hutang_lancar,
            'total_hutang_panjang' => $total_hutang_panjang,
            'total_modal' => $total_modal,
            'total_saldo_laba' => $total_saldo_laba,
        ];

        return view('laporan_keuangan/laporan_neraca/index', $data);
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
