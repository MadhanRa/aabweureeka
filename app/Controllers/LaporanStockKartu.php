<?php

namespace App\Controllers;

use App\Models\setup_persediaan\ModelStock;
use App\Models\setup_persediaan\ModelLokasi;
use App\Models\transaksi\ModelMutasiStock;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class LaporanStockKartu extends BaseController
{
    protected $modelStock;
    protected $modelLokasi;
    protected $modelMutasi;
    protected $db;
    function __construct()
    {
        $this->modelStock = new ModelStock();
        $this->modelLokasi = new ModelLokasi();
        $this->modelMutasi = new ModelMutasiStock();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $tglawal = $this->request->getVar('tglawal') ? $this->request->getVar('tglawal') : '';
        $tglakhir = $this->request->getVar('tglakhir') ? $this->request->getVar('tglakhir') : '';
        $lokasi = $this->request->getVar('lokasi') ? $this->request->getVar('lokasi') : '';
        $stock = $this->request->getVar('id_stock') ? $this->request->getVar('id_stock') : '';

        $nama_stock = $this->request->getVar('nama_stock') ? $this->request->getVar('nama_stock') : '';
        $kode_stock = $this->request->getVar('kode_stock') ? $this->request->getVar('kode_stock') : '';
        $isi_stock = $this->request->getVar('isi_stock') ? $this->request->getVar('isi_stock') : '';

        // Panggil model untuk mendapatkan data laporan
        $riwayat_mutasi = $this->modelMutasi->get_laporan($tglawal, $tglakhir, $lokasi, $stock);
        $riwayat_mutasi_summary = $this->modelMutasi->get_laporan_summary($tglawal, $tglakhir, $lokasi, $stock);

        $dataTerkategori = $this->kategorisasiMutasi($riwayat_mutasi);

        // cek data
        log_message('info', 'Riwayat mutasi: ' . print_r($riwayat_mutasi, true));
        log_message('info', 'Riwayat mutasi Summary: ' . print_r($riwayat_mutasi_summary, true));

        $masuk_total_q1 = 0;
        $masuk_total_q2 = 0;
        $masuk_total_r = 0;
        $keluar_total_q1 = 0;
        $keluar_total_q2 = 0;
        $keluar_total_r = 0;

        foreach ($riwayat_mutasi_summary as $row) {
            if ($row->jenis == 'masuk') {
                $masuk_total_q1 += isset($row->qty1) ? floatval($row->qty1) : 0;
                $masuk_total_q2 += isset($row->qty2) ? floatval($row->qty2) : 0;
                $masuk_total_r += isset($row->nilai) ? floatval($row->nilai) : 0;
            } elseif ($row->jenis == 'keluar') {
                $keluar_total_q1 += isset($row->qty1) ? floatval($row->qty1) : 0;
                $keluar_total_q2 += isset($row->qty2) ? floatval($row->qty2) : 0;
                $keluar_total_r += isset($row->nilai) ? floatval($row->nilai) : 0;
            }
        }

        // Ambil data tambahan untuk dropdown filter
        $data = [
            'dtkartu_mutasi'    => $dataTerkategori,
            'masuk_total_q'      => $masuk_total_q1 . '/' . $masuk_total_q2,
            'masuk_total_r'       => $masuk_total_r,
            'keluar_total_q'  => $keluar_total_q1 . '/' . $keluar_total_q2,
            'keluar_total_r'       => $keluar_total_r,
            'tglawal'        => $tglawal,
            'tglakhir'       => $tglakhir,
            'lokasi'       => $lokasi,
            'dtlokasi'     => $this->modelLokasi->findAll(),
            'stock'       => $stock,
            'dtstock'     => $this->modelStock->getStockWithSatuanRelations(),
            'nama_stock'  => $nama_stock,
            'kode_stock'  => $kode_stock,
            'isi_stock'   => $isi_stock,
        ];

        return view('laporan_stock_kartu/index', $data);
    }

    public function getStockData($id)
    {
        if ($this->request->isAJAX()) {
            $stockData = $this->modelStock->getStockById($id);
            if ($stockData) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => $stockData
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan.'
                ]);
            }
        }
    }

    private function kategorisasiMutasi($riwayat_mutasi)
    {
        $kategorisasiData = [];

        foreach ($riwayat_mutasi as $item) {

            $masuk_q = '0/0';
            $masuk_r = 0;
            $keluar_q = '0/0';
            $keluar_r = 0;
            if ($item->jenis == 'masuk') {
                $masuk_q = $item->qty1 . '/' . $item->qty2;
                $masuk_r = isset($item->nilai) ? floatval($item->nilai) : 0;
            } elseif ($item->jenis == 'keluar') {
                $keluar_q = $item->qty1 . '/' . $item->qty2;
                $keluar_r = isset($item->nilai) ? floatval($item->nilai) : 0;
            }
            $saldo_q = $item->g_qty1 . '/' . $item->g_qty2;
            $normalizedSaldo = $item->g_qty1 * $item->conv_factor + $item->g_qty2;
            $rata_rata = $item->jml_harga / $normalizedSaldo;

            $dataKategori = [
                'tanggal' => $item->tanggal,
                'nota' => $item->nota,
                'sumber_transaksi' => $item->sumber_transaksi,
                'masuk_q' => $masuk_q,
                'masuk_r' => $masuk_r,
                'keluar_q' => $keluar_q,
                'keluar_r' => $keluar_r,
                'saldo_q' => $saldo_q,
                'saldo_r' => $item->jml_harga,
                'rata_rata' => $rata_rata
            ];
            $kategorisasiData[] = $dataKategori;
        }

        return $kategorisasiData;
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
