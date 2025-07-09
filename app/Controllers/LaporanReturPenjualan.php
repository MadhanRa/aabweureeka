<?php

namespace App\Controllers;

use App\Models\setup_persediaan\ModelLokasi;
use App\Models\transaksi\penjualan\ModelPenjualan;
use App\Models\setup\ModelSetupsalesman;
use App\Controllers\BaseController;
use App\Models\transaksi\penjualan\ModelReturPenjualan;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanReturPenjualan extends BaseController
{
    protected $objLokasi;
    protected $objSetupsalesman;
    protected $objPenjualan;
    protected $objReturPenjualan;
    protected $db;
    function __construct()
    {
        $this->objLokasi = new ModelLokasi();
        $this->objSetupsalesman = new ModelSetupsalesman();
        $this->objPenjualan = new ModelPenjualan();
        $this->objReturPenjualan = new ModelReturPenjualan();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $salesman = $this->request->getVar('salesman') ? $this->request->getVar('salesman') : '';
        $lokasi = $this->request->getVar('lokasi') ? $this->request->getVar('lokasi') : '';


        // Panggil model untuk mendapatkan data laporan
        $returpenjualan = $this->objReturPenjualan->get_laporan($tglawal, $tglakhir, $salesman, $lokasi);


        $returpenjualan_summary = $this->objReturPenjualan->get_laporan_summary($tglawal, $tglakhir, $salesman, $lokasi);


        // Hitung jumlah harga, subtotal, discount cash, DPP, PPN, total, HPP, dan laba
        $jml_harga = 0;
        $subtotal = 0;
        $discount_cash = 0;
        $dpp = 0;
        $ppn = 0;
        $total = 0;
        $hpp = 0;
        $laba = 0;

        foreach ($returpenjualan as $row) {
            $jml_harga += isset($row->jml_harga) ? floatval($row->jml_harga) : 0;
        }

        foreach ($returpenjualan_summary as $row) {
            $subtotal += isset($row->sub_total) ? floatval($row->sub_total) : 0;
            $discount_cash += isset($row->disc_cash) ? $row->disc_cash * floatval($row->sub_total) : 0;
            $dpp += isset($row->netto) ? floatval($row->netto) : 0;
            $ppn += isset($row->ppn) ? floatval($row->ppn) : 0;
            $total += isset($row->grand_total) ? floatval($row->grand_total) : 0;
            $hpp += isset($row->hpp) ? $row->hpp : 0;
            $laba += isset($row->laba) ? $row->laba : 0;
        }

        foreach ($returpenjualan as $key => $value) {
            // masukkan key 'diskon_1' dan 'diskon_2' berdarasarkan kondisi

            $returpenjualan[$key]->disc_1 = isset($value->disc_1_perc) ? floatval($value->disc_1_perc) * floatval($value->jml_harga) : $value->disc_1_rp;
            $returpenjualan[$key]->disc_2 = isset($value->disc_2_perc) ? floatval($value->disc_2_perc) * floatval($value->jml_harga) : $value->disc_2_rp;
        }

        // Ambil data tambahan untuk dropdown filter
        $data = [
            'dtreturpenjualan'    => $returpenjualan,
            'jml_harga'      => $jml_harga,
            'subtotal'       => $subtotal,
            'discount_cash'  => $discount_cash,
            'dpp'            => $dpp,
            'ppn'            => $ppn,
            'grand_total'          => $total,
            'hpp'            => $hpp,
            'laba'           => $laba,
            'dtlokasi'       => $this->objLokasi->findAll(),
            'dtsalesman'     => $this->objSetupsalesman->findAll(),
            'tglawal'        => $tglawal,
            'tglakhir'       => $tglakhir,
            'salesman'       => $salesman,
            'lokasi'         => $lokasi,
        ];

        return view('laporanreturpenjualan/index', $data);
    }

    public function printPDF()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $salesman = $this->request->getVar('salesman') ? $this->request->getVar('salesman') : '';
        $lokasi = $this->request->getVar('lokasi') ? $this->request->getVar('lokasi') : '';

        // Panggil model untuk mendapatkan data laporan
        $returpenjualan = $this->objReturPenjualan->get_laporan($tglawal, $tglakhir, $salesman, $lokasi);


        // Gabungkan data penjualan dan retur penjualan
        $dtreturpenjualan = array_merge($returpenjualan);

        // Ambil nama salesman dan lokasi
        $nama_setupsalesman = !empty($salesman) ? $this->objSetupsalesman->find($salesman)->nama_setupsalesman : 'Semua Salesman';
        $nama_lokasi = !empty($lokasi) ? $this->objLokasi->find($lokasi)->nama_lokasi : 'Semua Lokasi';

        // Hitung jumlah harga, subtotal, discount cash, DPP, PPN, total, HPP, dan laba
        $jml_harga = 0;
        $subtotal = 0;
        $discount_cash = 0;
        $dpp = 0;
        $ppn = 0;
        $total = 0;
        $hpp = 0;
        $laba = 0;

        foreach ($dtreturpenjualan as $row) {
            $jml_harga += isset($row->jml_harga) ? $row->jml_harga : 0;
            $subtotal += isset($row->sub_total) ? $row->sub_total : 0;
            $discount_cash += isset($row->discount_cash) ? $row->discount_cash : 0;
            $dpp += isset($row->dpp) ? $row->dpp : 0;
            $ppn += isset($row->ppn) ? $row->ppn : 0;
            $total += isset($row->total) ? $row->total : 0;
            $hpp += isset($row->hpp) ? $row->hpp : 0;
            $laba += isset($row->laba) ? $row->laba : 0;
        }

        // Load view untuk PDF
        $html = view('laporanreturpenjualan/printPDF', [
            'dtreturpenjualan'    => $dtreturpenjualan,
            'jml_harga'      => $jml_harga,
            'subtotal'       => $subtotal,
            'discount_cash'  => $discount_cash,
            'dpp'            => $dpp,
            'ppn'            => $ppn,
            'total'          => $total,
            'hpp'            => $hpp,
            'laba'           => $laba,
            'tglawal'        => $tglawal,
            'tglakhir'       => $tglakhir,
            'salesman'       => $nama_setupsalesman,
            'lokasi'         => $nama_lokasi,
        ]);

        // Inisialisasi TCPDF
        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Laporan Retur Penjualan');
        $pdf->SetSubject('Laporan Retur Penjualan');
        $pdf->SetKeywords('TCPDF, PDF, laporan, penjualan');

        // Set header dan footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Set content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $pdf->Output('laporan_retur_penjualan.pdf', 'I');
    }
}
