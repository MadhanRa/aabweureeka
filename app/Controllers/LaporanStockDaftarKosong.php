<?php

namespace App\Controllers;

use App\Models\setup_persediaan\ModelKelompok;
use App\Models\setup_persediaan\ModelGroup;
use App\Models\setup_persediaan\ModelLokasi;
use App\Models\setup\ModelSetupsupplier;
use App\Models\transaksi\ModelMutasiStock;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanStockDaftarKosong extends BaseController
{
    protected $modelGroup;
    protected $modelKelompok;
    protected $modelMutasi;
    protected $modelLokasi;
    protected $modelSetupsupplier;
    protected $db;
    function __construct()
    {
        $this->modelGroup = new ModelGroup();
        $this->modelKelompok = new ModelKelompok();
        $this->modelMutasi = new ModelMutasiStock();
        $this->modelLokasi = new ModelLokasi();
        $this->modelSetupsupplier = new ModelSetupsupplier();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $kelompok = $this->request->getVar('kelompok') ? $this->request->getVar('kelompok') : '';
        $group = $this->request->getVar('group') ? $this->request->getVar('group') : '';
        $lokasi = $this->request->getVar('lokasi') ? $this->request->getVar('lokasi') : '';
        $supplier = $this->request->getVar('supplier') ? $this->request->getVar('supplier') : '';

        // Panggil model untuk mendapatkan data laporan
        $riwayat_mutasi = $this->modelMutasi->get_laporan_daftar_kosong($tglawal, $tglakhir, $kelompok, $group, $lokasi, $supplier);
        $riwayat_mutasi_summary = $this->modelMutasi->get_laporan_daftar_summary_kosong($tglawal, $tglakhir, $kelompok, $group, $lokasi, $supplier);
        $riwayat_mutasi_total = $this->modelMutasi->get_laporan_daftar_total_kosong($kelompok, $group, $lokasi, $supplier);


        // Ambil data tambahan untuk dropdown filter
        $data = [
            'dtdaftar_mutasi'    => $riwayat_mutasi,
            'awal_total'       => $riwayat_mutasi_summary->initial_nilai, // $awal_total,
            'masuk_total'      => $riwayat_mutasi_summary->in_nilai, // $masuk_total,
            'keluar_total'     => $riwayat_mutasi_summary->out_nilai, // $keluar_total,
            'akhir_total'      => $riwayat_mutasi_summary->ending_nilai, // $akhir_total,
            'akhir_total_all'  => $riwayat_mutasi_total->total_akhir, // $akhir_total_all,
            'tglawal'        => $tglawal,
            'tglakhir'       => $tglakhir,
            'kelompok'       => $kelompok,
            'dtkelompok'     => $this->modelKelompok->findAll(),
            'group'       => $group,
            'dtgroup'     => $this->modelGroup->findAll(),
            'lokasi'       => $lokasi,
            'dtlokasi'    => $this->modelLokasi->findAll(),
            'supplier'       => $supplier,
            'dtsupplier'   => $this->modelSetupsupplier->findAll(),
        ];

        return view('laporan_stock_daftar_kosong/index', $data);
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
