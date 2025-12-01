<?php

namespace App\Controllers\laporan_supplier;

use App\Models\setup\ModelSetupsupplier;
use App\Models\transaksi\ModelRiwayatHutang;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\BaseController;
use TCPDF;

class LaporanHutangSupplierKartu extends BaseController
{
    protected $objSetupSupplier;
    protected $objRiwayatHutang;
    protected $db;
    protected $view_path;
    function __construct()
    {
        $this->objSetupSupplier = new ModelSetupsupplier();
        $this->objRiwayatHutang = new ModelRiwayatHutang();
        $this->view_path = 'laporan/laporan_supplier/';
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : date('Y-m-01');
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : date('Y-m-d');
        $supplier = $this->request->getVar('supplier') ? $this->request->getVar('supplier') : '';

        if (!empty($supplier)) {
            // Panggil model untuk mendapatkan data laporan
            $riwayat_hutang = $this->objRiwayatHutang->get_laporan($tglawal, $tglakhir, $supplier);
            $riwayat_hutang_summary = $this->objRiwayatHutang->get_laporan_summary($tglawal, $tglakhir, $supplier);

            // Ambil data tambahan untuk dropdown filter
            $data = [
                'dtkartu_hutang'    => $riwayat_hutang,
                'saldo_awal_total'      => $riwayat_hutang_summary->saldo_awal,
                'debit_total'       => $riwayat_hutang_summary->debit,
                'kredit_total'  => $riwayat_hutang_summary->kredit,
                'saldo_akhir_total' => $riwayat_hutang_summary->saldo_akhir,
                'tglawal'        => $tglawal,
                'tglakhir'       => $tglakhir,
                'supplier'       => $supplier,
                'dtsupplier'     => $this->objSetupSupplier->findAll(),
            ];

            return view($this->view_path . 'laporan_hutangsupplier_kartu/index', $data);
        }

        return view($this->view_path . 'laporan_hutangsupplier_kartu/index', [
            'dtkartu_hutang'    => [],
            'saldo_awal_total'      => 0,
            'debit_total'       => 0,
            'kredit_total'  => 0,
            'saldo_akhir_total' => 0,
            'tglawal'        => $tglawal,
            'tglakhir'       => $tglakhir,
            'supplier'       => $supplier,
            'dtsupplier'     => $this->objSetupSupplier->findAll(),
        ]);
    }

    public function printPDF()
    {
        // $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        // $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        // $salesman = $this->request->getVar('salesman') ? $this->request->getVar('salesman') : '';
        // $lokasi = $this->request->getVar('lokasi') ? $this->request->getVar('lokasi') : '';

        // // Panggil model untuk mendapatkan data laporan
        // $penjualan = $this->objPenjualan->get_laporan($tglawal, $tglakhir, $salesman, $lokasi);


        // // Gabungkan data penjualan dan retur penjualan
        // $dtpenjualan = array_merge($penjualan);

        // // Ambil nama salesman dan lokasi
        // $nama_setupsalesman = !empty($salesman) ? $this->objSetupsalesman->find($salesman)->nama_setupsalesman : 'Semua Salesman';
        // $nama_lokasi = !empty($lokasi) ? $this->objLokasi->find($lokasi)->nama_lokasi : 'Semua Lokasi';

        // // Hitung jumlah harga, subtotal, discount cash, DPP, PPN, total, HPP, dan laba
        // $jml_harga = 0;
        // $subtotal = 0;
        // $discount_cash = 0;
        // $dpp = 0;
        // $ppn = 0;
        // $total = 0;
        // $hpp = 0;
        // $laba = 0;

        // foreach ($dtpenjualan as $row) {
        //     $jml_harga += isset($row->jml_harga) ? $row->jml_harga : 0;
        //     $subtotal += isset($row->sub_total) ? $row->sub_total : 0;
        //     $discount_cash += isset($row->discount_cash) ? $row->discount_cash : 0;
        //     $dpp += isset($row->dpp) ? $row->dpp : 0;
        //     $ppn += isset($row->ppn) ? $row->ppn : 0;
        //     $total += isset($row->total) ? $row->total : 0;
        //     $hpp += isset($row->hpp) ? $row->hpp : 0;
        //     $laba += isset($row->laba) ? $row->laba : 0;
        // }

        // // Load view untuk PDF
        // $html = view('laporanpenjualan/printPDF', [
        //     'dtpenjualan'    => $dtpenjualan,
        //     'jml_harga'      => $jml_harga,
        //     'subtotal'       => $subtotal,
        //     'discount_cash'  => $discount_cash,
        //     'dpp'            => $dpp,
        //     'ppn'            => $ppn,
        //     'total'          => $total,
        //     'hpp'            => $hpp,
        //     'laba'           => $laba,
        //     'tglawal'        => $tglawal,
        //     'tglakhir'       => $tglakhir,
        //     'salesman'       => $nama_setupsalesman,
        //     'lokasi'         => $nama_lokasi,
        // ]);

        // // Inisialisasi TCPDF
        // $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        // $pdf->SetCreator(PDF_CREATOR);
        // $pdf->SetAuthor('Your Name');
        // $pdf->SetTitle('Laporan Penjualan');
        // $pdf->SetSubject('Laporan Penjualan');
        // $pdf->SetKeywords('TCPDF, PDF, laporan, penjualan');

        // // Set header dan footer
        // $pdf->setPrintHeader(false);
        // $pdf->setPrintFooter(false);

        // // Set margins
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // // Add a page
        // $pdf->AddPage();

        // // Set content
        // $pdf->writeHTML($html, true, false, true, false, '');

        // // Output PDF
        // $pdf->Output('laporan_penjualan.pdf', 'I');
    }
}
