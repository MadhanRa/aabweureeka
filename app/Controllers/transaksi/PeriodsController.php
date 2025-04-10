<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelBahanSablon;
use App\Models\ModelHasilProduksi;
use App\Models\ModelHasilSablon;
use App\Models\ModelJurnalUmum;
use App\Models\ModelKasKecil;
use App\Models\ModelLunasSalesman;
use App\Models\ModelMutasiKasBank;
use App\Models\ModelPelunasanHutang;
use App\Models\ModelPemakaianBahan;
use App\Models\ModelPembelian;
use App\Models\ModelPenjualan;
use App\Models\ModelPenyesuaianStock;
use App\Models\ModelPindahLokasi;
use App\Models\ModelPiutangUsaha;
use App\Models\ModelReturPembelian;
use App\Models\ModelReturPenjualan;
use App\Models\ModelStockOpname;
use App\Models\PeriodsModels;
use CodeIgniter\HTTP\ResponseInterface;
use TCPDF;

class PeriodsController extends BaseController
{
    protected $PeriodsModels;
    protected $db;
    protected $objPembelian;
    protected $objPenjualan;
    protected $ReturPembelian;
    protected $ReturPenjualan;
    protected $PenyesuianStok;
    protected $PindahLokasi;
    protected $BahanSablon;
    protected $HasilSablon;
    protected $PemakaianBahan;
    protected $HasilProduksi;
    protected $PiutangUsaha;
    protected $PiutangSalesman;
    protected $Hutang;
    protected $MutasiKasbank;
    protected $JurnalUmum;
    protected $Kaskecil;
    protected $StockOpname;


    public function __construct()
    {
        $this->PeriodsModels = new PeriodsModels();
        $this->objPembelian = new ModelPembelian();
        $this->objPenjualan = new ModelPenjualan();
        $this->ReturPembelian = new ModelReturPembelian();
        $this->ReturPenjualan = new ModelReturPenjualan();
        $this->PenyesuianStok = new ModelPenyesuaianStock();
        $this->PindahLokasi = new ModelPindahLokasi();
        $this->BahanSablon = new ModelBahanSablon();
        $this->HasilSablon = new ModelHasilSablon();
        $this->PemakaianBahan = new ModelPemakaianBahan();
        $this->HasilProduksi = new ModelHasilProduksi();
        $this->PiutangUsaha = new ModelPiutangUsaha();
        $this->PiutangSalesman = new ModelLunasSalesman();
        $this->Hutang = new ModelPelunasanHutang();
        $this->MutasiKasbank = new ModelMutasiKasBank();
        $this->JurnalUmum = new ModelJurnalUmum();
        $this->Kaskecil = new ModelKasKecil();
        $this->StockOpname = new ModelStockOpname();

        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // echo "Debug: Masuk ke controller";
        $data['periods'] = $this->PeriodsModels->findAll();
        return view('periods/index', $data);
    }

    public function report($id)
    {
        $data_periods = $this->db->table('closed_periods')->where('id',$id)->get();
        $cek_data = $data_periods->getRowArray();
        $month = $cek_data['month'];
        $year = $cek_data['year'];

        $pembelian = $this->objPembelian->getByMonthAndYear($month,$year);
        $penjualan = $this->objPenjualan->getByMonthAndYear($month,$year);
        $retur_pembelian = $this->ReturPembelian->getByMonthAndYear($month,$year);
        $retur_penjualan = $this->ReturPenjualan->getByMonthAndYear($month,$year);
        $penyesuian_stok = $this->PenyesuianStok->getByMonthAndYear($month,$year);
        $pindah_lokasi = $this->PindahLokasi->getByMonthAndYear($month,$year);
        $bahan_sablon = $this->BahanSablon->getByMonthAndYear($month,$year);
        $hasil_sablon = $this->HasilSablon->getByMonthAndYear($month,$year);
        $pemakaian_bahan = $this->PemakaianBahan->getByMonthAndYear($month,$year);
        $hasil_produksi = $this->HasilProduksi->getByMonthAndYear($month,$year);
        $piutang_usaha = $this->PiutangUsaha->getByMonthAndYear($month,$year);
        $piutang_salesman = $this->PiutangSalesman->getByMonthAndYear($month,$year);
        $hutang = $this->Hutang->getByMonthAndYear($month,$year);
        $mutasi_kasbank = $this->MutasiKasbank->getByMonthAndYear($month,$year);
        $jurnal_umum = $this->JurnalUmum->getByMonthAndYear($month,$year);
        $kas_kecil = $this->Kaskecil->getByMonthAndYear($month,$year);
        $stock_opname = $this->StockOpname->getByMonthAndYear($month,$year);

        $data['data_pembelian'] = $pembelian['data'];  // data pembelian
        $data['grandtotal_pembelian'] = $pembelian['grandtotal']; // grandtotal data pembelian
        $data['data_penjualan'] = $penjualan['data']; // data penjualan
        $data['grandtotal_penjualan'] = $penjualan['grandtotal']; // grandtotal data penjualan
        $data['retur_penjualan'] = $retur_penjualan['data']; 
        $data['grandtotal_returpenjualan'] = $retur_penjualan['grandtotal']; 
        $data['retur_pembelian'] = $retur_pembelian['data']; 
        $data['grandtotal_returpembelian'] = $retur_pembelian['grandtotal']; 
        $data['penyesuian_stok'] = $penyesuian_stok['data'];         
        $data['pindah_lokasi'] = $pindah_lokasi['data']; 
        $data['bahan_sablon'] = $bahan_sablon['data']; 
        $data['hasil_sablon'] = $hasil_sablon['data']; 
        $data['pemakaian_bahan'] = $pemakaian_bahan['data'];         
        $data['hasil_produksi'] = $hasil_produksi['data']; 
        $data['piutang_usaha'] = $piutang_usaha['data']; 
        $data['piutang_salesman'] = $piutang_salesman['data'];         
        $data['hutang'] = $hutang['data']; 
        $data['mutasi_kasbank'] = $mutasi_kasbank['data']; 
        $data['jurnal_umum'] = $jurnal_umum['data']; 
        $data['kas_kecil'] = $kas_kecil['data']; 
        $data['grandtotal_kas_kecil'] = $kas_kecil['grandtotal']; 
        $data['stock_opname'] = $stock_opname['data']; 
        $data['id'] = $id;
        $total_kas_kecil = $kas_kecil['grandtotal'];
        $total_penjualan = $penjualan['grandtotal'];
        $total_retur_penjualan = $retur_penjualan['grandtotal'];
        $total_pembelian = $pembelian['grandtotal'];
        $total_retur_pembelian = $retur_pembelian['grandtotal'];
        $data['grandtotal_pengeluaran'] = ($total_pembelian - $total_retur_pembelian) + $total_kas_kecil;
        $data['grandtotal_pemasukan'] = $total_penjualan - $total_retur_penjualan;

        return view('periods/report', $data);
    }

    public function printPDF($id)
    {
        $data_periods = $this->db->table('closed_periods')->where('id',$id)->get();
        $cek_data = $data_periods->getRowArray();
        $month = $cek_data['month'];
        $year = $cek_data['year'];

        $pembelian = $this->objPembelian->getByMonthAndYear($month,$year);
        $penjualan = $this->objPenjualan->getByMonthAndYear($month,$year);
        $retur_pembelian = $this->ReturPembelian->getByMonthAndYear($month,$year);
        $retur_penjualan = $this->ReturPenjualan->getByMonthAndYear($month,$year);
        $penyesuian_stok = $this->PenyesuianStok->getByMonthAndYear($month,$year);
        $pindah_lokasi = $this->PindahLokasi->getByMonthAndYear($month,$year);
        $bahan_sablon = $this->BahanSablon->getByMonthAndYear($month,$year);
        $hasil_sablon = $this->HasilSablon->getByMonthAndYear($month,$year);
        $pemakaian_bahan = $this->PemakaianBahan->getByMonthAndYear($month,$year);
        $hasil_produksi = $this->HasilProduksi->getByMonthAndYear($month,$year);
        $piutang_usaha = $this->PiutangUsaha->getByMonthAndYear($month,$year);
        $piutang_salesman = $this->PiutangSalesman->getByMonthAndYear($month,$year);
        $hutang = $this->Hutang->getByMonthAndYear($month,$year);
        $mutasi_kasbank = $this->MutasiKasbank->getByMonthAndYear($month,$year);
        $jurnal_umum = $this->JurnalUmum->getByMonthAndYear($month,$year);
        $kas_kecil = $this->Kaskecil->getByMonthAndYear($month,$year);
        $stock_opname = $this->StockOpname->getByMonthAndYear($month,$year);

        $data['data_pembelian'] = $pembelian['data'];  // data pembelian
        $data['grandtotal_pembelian'] = $pembelian['grandtotal']; // grandtotal data pembelian
        $data['data_penjualan'] = $penjualan['data']; // data penjualan
        $data['grandtotal_penjualan'] = $penjualan['grandtotal']; // grandtotal data penjualan
        $data['retur_penjualan'] = $retur_penjualan['data']; 
        $data['grandtotal_returpenjualan'] = $retur_penjualan['grandtotal']; 
        $data['retur_pembelian'] = $retur_pembelian['data']; 
        $data['grandtotal_returpembelian'] = $retur_pembelian['grandtotal']; 
        $data['penyesuian_stok'] = $penyesuian_stok['data'];         
        $data['pindah_lokasi'] = $pindah_lokasi['data']; 
        $data['bahan_sablon'] = $bahan_sablon['data']; 
        $data['hasil_sablon'] = $hasil_sablon['data']; 
        $data['pemakaian_bahan'] = $pemakaian_bahan['data'];         
        $data['hasil_produksi'] = $hasil_produksi['data']; 
        $data['piutang_usaha'] = $piutang_usaha['data']; 
        $data['piutang_salesman'] = $piutang_salesman['data'];         
        $data['hutang'] = $hutang['data']; 
        $data['mutasi_kasbank'] = $mutasi_kasbank['data']; 
        $data['jurnal_umum'] = $jurnal_umum['data']; 
        $data['kas_kecil'] = $kas_kecil['data']; 
        $data['grandtotal_kas_kecil'] = $kas_kecil['grandtotal']; 
        $data['stock_opname'] = $stock_opname['data']; 
        $total_kas_kecil = $kas_kecil['grandtotal'];
        $total_penjualan = $penjualan['grandtotal'];
        $total_retur_penjualan = $retur_penjualan['grandtotal'];
        $total_pembelian = $pembelian['grandtotal'];
        $total_retur_pembelian = $retur_pembelian['grandtotal'];
        $data['grandtotal_pengeluaran'] = ($total_pembelian - $total_retur_pembelian) + $total_kas_kecil;
        $data['grandtotal_pemasukan'] = $total_penjualan - $total_retur_penjualan;
        // Debugging: Tampilkan konten HTML sebelum PDF
        $html = view('periods/printPDF', $data);
        // echo $html;
        // exit; // Jika perlu debugging

        // Buat PDF baru
        $pdf = new TCPDF('landscape', PDF_UNIT, 'A4', true, 'UTF-8', false);

        // Hapus header/footer default
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Tambah halaman baru
        $pdf->AddPage();

        // Cetak konten menggunakan WriteHTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Set tipe respons menjadi PDF
        $this->response->setContentType('application/pdf');
        $pdf->Output('laporan_final_tutup_buku.pdf', 'I');
    }

    
    public function add()
    {
        return view('periods/add');
    }

    public function close()
    {
        $month = $this->request->getPost('month');
        // $status = $this->request->getPost('is_closed');
        list($year, $monthNumber) = explode('-', $month);

        $cek_data = $this->PeriodsModels->where('month', $monthNumber)
            ->where('year', $year)
            ->where('is_closed', 1)
            ->first();
        if ($cek_data) {
            return redirect()->to(site_url('close-period'))->with('Error', 'Periode Tutup Buku Sudah Ada dan Sedang Berlangsung');
        } else {
            $data = array(
                'month' => $monthNumber,
                'year' => $year, 
                'is_closed' => 1,
            );
            $this->db->table('closed_periods')->insert($data);

            // if ($status == 1) {
                // Buka periode berikutnya
                $nextPeriod = strtotime("+1 month", strtotime("$year-$monthNumber"));
                $nextMonth = date('m', $nextPeriod); // Nama bulan periode berikutnya
                $nextYear  = date('Y', $nextPeriod); // Tahun periode berikutnya

                $nextQuery = $this->db->table('closed_periods')
                    ->where('month', $nextMonth)
                    ->where('year', $nextYear)
                    ->get();

                if ($nextQuery->getNumRows() === 0) {
                    // Jika periode belum ada, tambahkan dengan status "open"
                    $nextData = [
                        'month' => $nextMonth,
                        'year'  => $nextYear,
                        'is_closed'  => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->table('closed_periods')->insert($nextData);
                }

                return redirect()->to(site_url('close-period'))->with('Sukses', 'Data Berhasil Disimpan');
            // } else {
            //     return redirect()->to(site_url('close-period'))->with('Sukses', 'Data Berhasil Disimpan');
            // }
        }
    }

    public function open($id)
    {
        $this->PeriodsModels->update($id, ['is_closed' => 0]);
        return redirect()->back()->with('success', 'Periode berhasil dibuka kembali.');
    }

    public function closeBook($id)
    {
        $this->PeriodsModels->update($id, ['is_closed' => 1]);
        $data_periode = $this->PeriodsModels->where('id', $id)->first();
        // Ambil month dan year
        $month = $data_periode->month;
        $year = $data_periode->year;
        $nextPeriod = strtotime("+1 month", strtotime("$year-$month"));
        $nextMonth = date('m', $nextPeriod); // Nama bulan periode berikutnya
        $nextYear  = date('Y', $nextPeriod); // Tahun periode berikutnya

        $nextQuery = $this->db->table('closed_periods')
            ->where('month', $nextMonth)
            ->where('year', $nextYear)
            ->get();

        if ($nextQuery->getNumRows() === 0) {
            // Jika periode belum ada, tambahkan dengan status "open"
            $nextData = [
                'month' => $nextMonth,
                'year'  => $nextYear,
                'is_closed'  => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->table('closed_periods')->insert($nextData);
        }
        return redirect()->to(site_url('close-period'))->with('Sukses', 'Periode Berhasil Di Tutup');
    }
    public function delete($id)
    {
        $this->PeriodsModels->delete($id);
        return redirect()->to(site_url('close-period'))->with('Sukses', 'Periode Berhasil Di Hapus');
    }
}
