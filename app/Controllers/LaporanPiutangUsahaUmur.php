<?php

namespace App\Controllers;

use App\Models\setup\ModelSetuppelanggan;
use App\Models\setup\ModelSetupsalesman;
use App\Models\transaksi\ModelRiwayatPiutang;
use App\Models\transaksi\penjualan\ModelPenjualan;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanPiutangUsahaUmur extends BaseController
{
    protected $objSetupPelanggan;
    protected $objSetupSalesman;
    protected $objRiwayatPiutang;
    protected $objPenjualan;
    protected $db;
    function __construct()
    {
        $this->objSetupPelanggan = new ModelSetuppelanggan();
        $this->objSetupSalesman = new ModelSetupsalesman();
        $this->objRiwayatPiutang = new ModelRiwayatPiutang();
        $this->objPenjualan = new ModelPenjualan();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $salesman = $this->request->getVar('salesman') ? $this->request->getVar('salesman') : '';

        // Panggil model untuk mendapatkan data laporan
        $riwayat_piutang = $this->objRiwayatPiutang->get_laporan_daftar_umur($tglawal, $tglakhir, $salesman);
        $dataTerkategori = $this->kategorisasiUmurPiutang($riwayat_piutang);

        // cek data
        log_message('info', 'Riwayat Piutang: ' . print_r($riwayat_piutang, true));

        // Ambil data tambahan untuk dropdown filter
        $data = [
            'dtdaftar_piutang'    => $dataTerkategori['data'],
            'kurang_dari_total'   => $dataTerkategori['total']['kurang_dari'],
            'antara1_total'       => $dataTerkategori['total']['antara1'],
            'antara2_total'       => $dataTerkategori['total']['antara2'],
            'lebih_dari_total'    => $dataTerkategori['total']['lebih_dari'],
            'grand_total'         => $dataTerkategori['total']['grand_total'],
            'tglawal'        => $tglawal,
            'tglakhir'       => $tglakhir,
            'salesman'       => $salesman,
            'dtsalesman'     => $this->objSetupSalesman->findAll(),
        ];

        return view('laporan_piutangusaha_umur/index', $data);
    }

    /**
     * Mengkategorikan piutang berdasarkan umur jatuh tempo
     * 
     * @param array $piutangData Data piutang dari model
     * @return array Data yang sudah dikategorikan berdasarkan umur
     */
    private function kategorisasiUmurPiutang($piutangData)
    {
        $today = new \DateTime(date('Y-m-d'));
        $kategorisasiData = [];
        $totalUmur = [
            'kurang_dari' => 0,
            'antara1' => 0,
            'antara2' => 0,
            'lebih_dari' => 0,
            'grand_total' => 0
        ];

        foreach ($piutangData as $item) {
            $tanggalJatuhTempo = new \DateTime($item->tgl_jatuhtempo);
            $selisihHari = $today->diff($tanggalJatuhTempo)->days;
            $isOverdue = $today > $tanggalJatuhTempo;

            $sisaPiutang = $item->grand_total - $item->total_pelunasan;

            // Inisialisasi kolom untuk setiap kategori umur
            $dataKategori = [
                'kode_pelanggan' => $item->kode_pelanggan,
                'nama_pelanggan' => $item->nama_pelanggan,
                'tanggal' => $item->tanggal,
                'nota' => $item->nota,
                'tgl_jatuhtempo' => $item->tgl_jatuhtempo,
                'kurang_dari' => 0,
                'antara1' => 0,
                'antara2' => 0,
                'lebih_dari' => 0,
                'jumlah' => $sisaPiutang,
                'umur_piutang' => $isOverdue ? $selisihHari : 0
            ];

            // Kategorisasi berdasarkan umur piutang
            if (!$isOverdue || $selisihHari <= 30) {
                $dataKategori['kurang_dari'] = $sisaPiutang;
                $totalUmur['kurang_dari'] += $sisaPiutang;
            } elseif ($selisihHari <= 60) {
                $dataKategori['antara1'] = $sisaPiutang;
                $totalUmur['antara1'] += $sisaPiutang;
            } elseif ($selisihHari <= 90) {
                $dataKategori['antara2'] = $sisaPiutang;
                $totalUmur['antara2'] += $sisaPiutang;
            } else {
                $dataKategori['lebih_dari'] = $sisaPiutang;
                $totalUmur['lebih_dari'] += $sisaPiutang;
            }

            $totalUmur['grand_total'] += $sisaPiutang;
            $kategorisasiData[] = $dataKategori;
        }

        return [
            'data' => $kategorisasiData,
            'total' => $totalUmur
        ];
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
