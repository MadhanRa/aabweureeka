<?php

namespace App\Controllers;

use App\Models\ModelBiayaTransaksi;
use App\Models\setup\ModelSetupBuku;
use App\Models\setup\ModelSetupBiaya;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanBiayaKartu extends BaseController
{
    protected $modelBiayaTransaksi;
    protected $modelSetupBuku;
    protected $ModelSetupBiaya;
    protected $db;
    function __construct()
    {
        $this->modelBiayaTransaksi = new ModelBiayaTransaksi();
        $this->modelSetupBuku = new ModelSetupBuku();
        $this->ModelSetupBiaya = new ModelSetupBiaya();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $rekening = $this->request->getVar('rekening') ? $this->request->getVar('rekening') : '';
        $id_setupbiaya = $this->request->getVar('id_setupbiaya') ? $this->request->getVar('id_setupbiaya') : '';

        // Panggil model untuk mendapatkan data laporan
        $daftar_biaya = $this->modelBiayaTransaksi->getKartuBiaya($tglawal, $tglakhir, $rekening, $id_setupbiaya);


        $saldo_awal_total = 0;
        $debit_total = 0;
        $kredit_total = 0;
        $saldo_akhir_total = 0;

        foreach ($daftar_biaya as $row) {
            $saldo_awal_total += isset($row->debit_saldo_awal) ? floatval($row->debit_saldo_awal) : 0;
            $debit_total += floatval($row->debit_biasa);
            $kredit_total += floatval($row->kredit);
            $saldo_akhir_total +=  $row->debit_total;
        }

        // Ambil data tambahan untuk dropdown filter
        $data = [
            'dtdaftar_biaya'    => $daftar_biaya,
            'saldo_awal_total'      => $saldo_awal_total,
            'debit_total'       => $debit_total,
            'kredit_total'  => $kredit_total,
            'saldo_akhir_total'       => $saldo_akhir_total,
            'tglawal'        => $tglawal,
            'tglakhir'       => $tglakhir,
            'rekening'       => $rekening,
            'dtsetupbuku'       => $this->modelSetupBuku->findAll(),
            'id_setupbiaya'       => $id_setupbiaya,
            'dtsetup_biaya'       => $this->ModelSetupBiaya->getBiayaWithRekening(),
        ];

        return view('laporan_keuangan/laporan_biaya_kartu/index', $data);
    }

    public function cariBiaya($id_setupbiaya)
    {
        $biaya = $this->ModelSetupBiaya->find($id_setupbiaya);
        if ($biaya) {
            return $this->response->setJSON([
                'status' => true,
                'data' => $biaya
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Biaya tidak ditemukan'
            ]);
        }
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
