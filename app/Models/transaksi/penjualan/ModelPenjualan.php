<?php

namespace App\Models\transaksi\penjualan;

use CodeIgniter\Model;

class ModelPenjualan extends Model
{
    protected $table            = 'penjualan1';
    protected $primaryKey       = 'id_penjualan';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'nota',
        'id_pelanggan',
        'TOP',
        'tgl_jatuhtempo',
        'id_salesman',
        'id_lokasi',
        'no_fp',
        'opsi_pembayaran',
        'ppn_option',
        'sub_total',
        'disc_cash',
        'netto',
        'ppn',
        'grand_total',
    ];

    function getAll()
    {
        return $this->select('penjualan1.*, setupsalesman1.nama_salesman AS nama_salesman,, setuppelanggan1.nama_pelanggan AS nama_pelanggan, lokasi1.nama_lokasi AS lokasi_asal')
            ->join('setupsalesman1', 'penjualan1.id_salesman = setupsalesman1.id_salesman', 'left')
            ->join('setuppelanggan1', 'penjualan1.id_pelanggan = setuppelanggan1.id_pelanggan', 'left')
            ->join('lokasi1', 'penjualan1.id_lokasi = lokasi1.id_lokasi', 'left')
            ->orderBy('penjualan1.tanggal', 'DESC')
            ->findAll(); // Mengambil semua data dari tabel penjualan1
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom dari tabel utama dan tabel terkait
        $builder->select('
            p.*, 
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama_pelanggan AS nama_pelanggan, 
            s.kode_satuan AS kode_satuan,
            sm.nama_salesman AS nama_salesman
        ');

        // Join dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        // Join dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
        $builder->join('setuppelanggan1 sp', 'p.id_pelanggan = sp.id_pelanggan', 'left');

        // Join dengan tabel 'satuan1' untuk mendapatkan kode satuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');

        // Join dengan tabel 'setupsalesman1' untuk mendapatkan nama salesman
        $builder->join('setupsalesman1 sm', 'p.id_salesman = sm.id_salesman', 'left');
        $builder->where('MONTH(p.tanggal)', $bulan);
        $builder->where('YEAR(p.tanggal)', $tahun);
        $data = $builder->get()->getResult();

        $grandtotal =  $this->selectSum('grand_total')
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->get()
            ->getRow()
            ->grand_total ?? 0;

        return [
            'data' => $data,           // Semua data
            'grandtotal' => $grandtotal, // Total nilai grand_total
        ];
    }

    function getById($id)
    {

        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom dari tabel utama dan tabel terkait
        $builder->select('
                p.*, 
                l1.nama_lokasi AS nama_lokasi, 
                sp.*,
                sm.*
            ');

        // Join dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        // Join dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
        $builder->join('setuppelanggan1 sp', 'p.id_pelanggan = sp.id_pelanggan', 'left');

        // Join dengan tabel 'setupsalesman1' untuk mendapatkan nama salesman
        $builder->join('setupsalesman1 sm', 'p.id_salesman = sm.id_salesman', 'left');

        // Tambahkan kondisi where untuk id
        $builder->where('p.id_penjualan', $id);

        return $builder->get()->getRow();
    }

    public function searchAndDisplay($keyword = null, $start = 0, $length = 0)
    {
        $builder = $this->select(
            '
            penjualan1.tanggal, 
            penjualan1.nota, 
            penjualan1.tgl_jatuhtempo, 
            setuppelanggan1.nama_pelanggan,
            setupsalesman1.nama_salesman,
            lokasi1.nama_lokasi'
        )
            ->join('setuppelanggan1', 'penjualan1.id_pelanggan = setuppelanggan1.id_pelanggan', 'left')
            ->join('setupsalesman1', 'penjualan1.id_salesman = setupsalesman1.id_salesman', 'left')
            ->join('lokasi1', 'penjualan1.id_lokasi = lokasi1.id_lokasi', 'left');


        if ($keyword) {
            $builder->groupStart();
            $arr_keywords = explode(" ", $keyword);
            for ($i = 0; $i < count($arr_keywords); $i++) {
                $builder->orlike('penjualan1.nota', $arr_keywords[$i]);
                $builder->orlike('penjualan1.tgl_jatuhtempo', $arr_keywords[$i]);
                $builder->orlike('setuppelanggan1.nama_pelanggan', $arr_keywords[$i]);
                $builder->orlike('setupsalesman1.nama_salesman', $arr_keywords[$i]);
                $builder->orlike('lokasi1.nama_lokasi', $arr_keywords[$i]);
            }
            $builder->groupEnd();
        }

        if ($start != 0 or $length != 0) {
            $builder->limit($length, $start);
        }

        return $builder->orderBy('penjualan1.tanggal', 'DESC')->get()->getResult();
    }

    public function get_laporan($tglawal, $tglakhir, $salesman, $lokasi = null)
    {
        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom yang dibutuhkan
        $builder->select('
            p.tanggal,
            p.nota,
            pd.id as detail_id,
            pd.kode,
            pd.nama_barang,
            pd.satuan,
            pd.qty1,
            pd.qty2,
            pd.harga_satuan, 
            pd.jml_harga, 
            pd.disc_1_perc, 
            pd.disc_2_perc,
            pd.total AS sub_total,
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama_salesman AS nama_salesman, 
            plg.nama_pelanggan AS nama_pelanggan
        ');

        // Join dengan tabel terkait
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');

        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        $builder->join('setupsalesman1 sp', 'p.id_salesman = sp.id_salesman', 'left');

        $builder->join('setuppelanggan1 plg', 'p.id_pelanggan = plg.id_pelanggan', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Filter berdasarkan salesman jika diberikan
        if (!empty($salesman)) {
            $builder->where('p.id_salesman', $salesman);
        }
        // Filter berdasarkan lokasi jika diberikan
        if (!empty($lokasi)) {
            $builder->where('p.id_lokasi', $lokasi);
        }

        $builder->orderBy('p.id_penjualan, pd.id');

        // Eksekusi query dan kembalikan hasil
        return $builder->get()->getResult();
    }

    public function get_laporan_p3($tglawal, $tglakhir, $salesman, $lokasi = null, $supplier = null, $stock = null, $pelanggan = null)
    {
        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom yang dibutuhkan
        $builder->select('
            p.tanggal,
            p.nota,
            pd.id as detail_id,
            pd.kode,
            pd.nama_barang,
            sup.nama AS nama_supplier,
            pd.satuan,
            pd.qty1,
            pd.qty2,
            pd.harga_satuan, 
            pd.jml_harga, 
            pd.disc_1_perc, 
            pd.disc_2_perc,
            pd.total AS sub_total,
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama_salesman AS nama_salesman, 
            plg.nama_pelanggan AS nama_pelanggan
        ');

        // Join dengan tabel terkait
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');


        $builder->join('stock1 stck', 'pd.id_stock = stck.id_stock', 'left');
        $builder->join('setupsupplier1 sup', 'stck.id_setupsupplier = sup.id_setupsupplier', 'left');

        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        $builder->join('setupsalesman1 sp', 'p.id_salesman = sp.id_salesman', 'left');

        $builder->join('setuppelanggan1 plg', 'p.id_pelanggan = plg.id_pelanggan', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        if (!empty($salesman)) {
            $builder->where('p.id_salesman', $salesman);
        }
        if (!empty($lokasi)) {
            $builder->where('p.id_lokasi', $lokasi);
        }
        if (!empty($supplier)) {
            $builder->where('sup.id_supplier', $supplier);
        }
        if (!empty($stock)) {
            $builder->where('pd.id_stock', $stock);
        }
        if (!empty($pelanggan)) {
            $builder->where('plgn.id_pelanggan', $pelanggan);
        }

        $builder->orderBy('p.id_penjualan, pd.id');

        // Eksekusi query dan kembalikan hasil
        return $builder->get()->getResult();
    }

    public function get_laporan_p3t($tahun, $salesman, $lokasi = null, $supplier = null, $stock = null, $pelanggan = null, $view_option = 'qty')
    {

        if ($view_option == 'qty') {
            $subquery1 = $this->db->table('penjualan1_detail d2')
                ->select('SUM(d2.qty1)')
                ->join('penjualan1 h2', 'd2.id_penjualan = h2.id_penjualan')
                ->join('stock1 stck2', 'd2.id_stock = stck2.id_stock')
                ->join('setupsupplier1 sup2', 'stck2.id_setupsupplier = sup2.id_setupsupplier')
                ->where('YEAR(h2.tanggal)', $tahun - 1)
                ->where('h2.id_salesman = p.id_salesman', null, false)
                ->where('h2.id_pelanggan = p.id_pelanggan', null, false)
                ->where('d2.id_stock = pd.id_stock', null, false)
                ->where('sup2.id_setupsupplier = stck.id_setupsupplier', null, false)
                ->getCompiledSelect();

            // Subquery for qty2
            $subquery2 = $this->db->table('penjualan1_detail d2')
                ->select('SUM(d2.qty2)')
                ->join('penjualan1 h2', 'd2.id_penjualan = h2.id_penjualan')
                ->join('stock1 stck2', 'd2.id_stock = stck2.id_stock')
                ->join('setupsupplier1 sup2', 'stck2.id_setupsupplier = sup2.id_setupsupplier')
                ->where('YEAR(h2.tanggal)', $tahun - 1)
                ->where('h2.id_salesman = p.id_salesman', null, false)
                ->where('h2.id_pelanggan = p.id_pelanggan', null, false)
                ->where('d2.id_stock = pd.id_stock', null, false)
                ->where('sup2.id_setupsupplier = stck.id_setupsupplier', null, false)
                ->getCompiledSelect();

            $builder = $this->db->table('penjualan1 p');

            $builder->select("
                sp.nama_salesman,
                sup.nama AS nama_supplier,
                plg.nama_pelanggan,
                pd.kode,
                pd.nama_barang,
                stck.conv_factor,
                pd.satuan,
                COALESCE(($subquery1), 0) AS total_tahun_lalu_qty1,
                COALESCE(($subquery2), 0) AS total_tahun_lalu_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN pd.qty1 ELSE 0 END) AS Jan_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN pd.qty2 ELSE 0 END) AS Jan_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN pd.qty1 ELSE 0 END) AS Feb_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN pd.qty2 ELSE 0 END) AS Feb_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN pd.qty1 ELSE 0 END) AS Mar_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN pd.qty2 ELSE 0 END) AS Mar_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN pd.qty1 ELSE 0 END) AS Apr_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN pd.qty2 ELSE 0 END) AS Apr_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN pd.qty1 ELSE 0 END) AS Mei_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN pd.qty2 ELSE 0 END) AS Mei_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN pd.qty1 ELSE 0 END) AS Jun_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN pd.qty2 ELSE 0 END) AS Jun_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN pd.qty1 ELSE 0 END) AS Jul_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN pd.qty2 ELSE 0 END) AS Jul_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN pd.qty1 ELSE 0 END) AS Agu_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN pd.qty2 ELSE 0 END) AS Agu_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN pd.qty1 ELSE 0 END) AS Sep_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN pd.qty2 ELSE 0 END) AS Sep_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN pd.qty1 ELSE 0 END) AS Okt_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN pd.qty2 ELSE 0 END) AS Okt_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN pd.qty1 ELSE 0 END) AS Nov_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN pd.qty2 ELSE 0 END) AS Nov_qty2,
                SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN pd.qty1 ELSE 0 END) AS Des_qty1,
                SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN pd.qty2 ELSE 0 END) AS Des_qty2,
                SUM(pd.qty1) AS Total1,
                SUM(pd.qty2) AS Total2
            ", false);
        } else {
            $subquery = $this->db->table('penjualan1_detail d2')
                ->select('SUM(d2.total)')
                ->join('penjualan1 h2', 'd2.id_penjualan = h2.id_penjualan')
                ->join('stock1 stck2', 'd2.id_stock = stck2.id_stock')
                ->join('setupsupplier1 sup2', 'stck2.id_setupsupplier = sup2.id_setupsupplier')
                ->where('YEAR(h2.tanggal)', $tahun - 1)
                ->where('h2.id_salesman = p.id_salesman', null, false)
                ->where('h2.id_pelanggan = p.id_pelanggan', null, false)
                ->where('d2.id_stock = pd.id_stock', null, false)
                ->where('sup2.id_setupsupplier = stck.id_setupsupplier', null, false)
                ->getCompiledSelect();

            $builder = $this->db->table('penjualan1 p');

            $builder->select("
                sp.nama_salesman,
                sup.nama AS nama_supplier,
                plg.nama_pelanggan,
                pd.kode,
                pd.nama_barang,
                stck.conv_factor,
                pd.satuan,
                COALESCE(($subquery), 0) AS total_tahun_lalu,
                SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN pd.total ELSE 0 END) AS Jan,
                SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN pd.total ELSE 0 END) AS Feb,
                SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN pd.total ELSE 0 END) AS Mar,
                SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN pd.total ELSE 0 END) AS Apr,
                SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN pd.total ELSE 0 END) AS Mei,
                SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN pd.total ELSE 0 END) AS Jun,
                SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN pd.total ELSE 0 END) AS Jul,
                SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN pd.total ELSE 0 END) AS Agu,
                SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN pd.total ELSE 0 END) AS Sep,
                SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN pd.total ELSE 0 END) AS Okt,
                SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN pd.total ELSE 0 END) AS Nov,
                SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN pd.total ELSE 0 END) AS Des,
                SUM(pd.total) AS Total
            ", false);
        }

        // JOINs
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');
        $builder->join('stock1 stck', 'pd.id_stock = stck.id_stock', 'left');
        $builder->join('setupsupplier1 sup', 'stck.id_setupsupplier = sup.id_setupsupplier', 'left');
        $builder->join('setupsalesman1 sp', 'p.id_salesman = sp.id_salesman', 'left');
        $builder->join('setuppelanggan1 plg', 'p.id_pelanggan = plg.id_pelanggan', 'left');

        // Filter tahun berjalan
        $builder->where('YEAR(p.tanggal)', $tahun);

        // Filter opsional
        if (!empty($salesman)) {
            $builder->where('p.id_salesman', $salesman);
        }
        if (!empty($lokasi)) {
            $builder->where('p.id_lokasi', $lokasi);
        }
        if (!empty($supplier)) {
            $builder->where('sup.id_supplier', $supplier); // sesuaikan jika nama id supplier berbeda
        }
        if (!empty($stock)) {
            $builder->where('pd.id_stock', $stock);
        }
        if (!empty($pelanggan)) {
            $builder->where('plg.id_pelanggan', $pelanggan);
        }

        // Group by sesuai kombinasi entitas yang unik
        $builder->groupBy([
            'sp.nama_salesman',
            'sup.nama',
            'plg.nama_pelanggan',
            'pd.kode',
            'pd.nama_barang',
            'pd.satuan',
            'p.id_salesman',
            'p.id_pelanggan',
            'pd.id_stock'
        ]);

        $builder->orderBy('sp.nama_salesman');
        $builder->orderBy('sup.nama');
        $builder->orderBy('plg.nama_pelanggan');
        $builder->orderBy('pd.nama_barang');

        return $builder->get()->getResult();
    }

    public function get_laporan_ptb($tahun, $salesman, $lokasi = null, $supplier = null, $stock = null, $pelanggan = null, $view_option = 'qty')
    {

        $builder = $this->db->table('penjualan1 p');

        if ($view_option == 'qty') {
            // Get last year's totals for comparison
            $subquery1 = $this->db->table('penjualan1_detail d2')
                ->select('SUM(d2.qty1)')
                ->join('penjualan1 h2', 'd2.id_penjualan = h2.id_penjualan')
                ->join('stock1 stck2', 'd2.id_stock = stck2.id_stock')
                ->where('YEAR(h2.tanggal)', $tahun - 1)
                ->where('d2.id_stock = pd.id_stock', null, false)
                ->getCompiledSelect();

            $subquery2 = $this->db->table('penjualan1_detail d2')
                ->select('SUM(d2.qty2)')
                ->join('penjualan1 h2', 'd2.id_penjualan = h2.id_penjualan')
                ->join('stock1 stck2', 'd2.id_stock = stck2.id_stock')
                ->where('YEAR(h2.tanggal)', $tahun - 1)
                ->where('d2.id_stock = pd.id_stock', null, false)
                ->getCompiledSelect();

            $builder->select("
            stck.id_stock,
            stck.kode AS kode_stock,
            pd.kode,
            pd.nama_barang,
            stck.conv_factor,
            pd.satuan,
            sup.nama AS nama_supplier,
            COALESCE(($subquery1), 0) AS total_tahun_lalu_qty1,
            COALESCE(($subquery2), 0) AS total_tahun_lalu_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN pd.qty1 ELSE 0 END) AS Jan_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN pd.qty2 ELSE 0 END) AS Jan_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN pd.qty1 ELSE 0 END) AS Feb_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN pd.qty2 ELSE 0 END) AS Feb_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN pd.qty1 ELSE 0 END) AS Mar_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN pd.qty2 ELSE 0 END) AS Mar_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN pd.qty1 ELSE 0 END) AS Apr_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN pd.qty2 ELSE 0 END) AS Apr_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN pd.qty1 ELSE 0 END) AS Mei_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN pd.qty2 ELSE 0 END) AS Mei_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN pd.qty1 ELSE 0 END) AS Jun_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN pd.qty2 ELSE 0 END) AS Jun_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN pd.qty1 ELSE 0 END) AS Jul_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN pd.qty2 ELSE 0 END) AS Jul_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN pd.qty1 ELSE 0 END) AS Agu_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN pd.qty2 ELSE 0 END) AS Agu_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN pd.qty1 ELSE 0 END) AS Sep_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN pd.qty2 ELSE 0 END) AS Sep_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN pd.qty1 ELSE 0 END) AS Okt_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN pd.qty2 ELSE 0 END) AS Okt_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN pd.qty1 ELSE 0 END) AS Nov_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN pd.qty2 ELSE 0 END) AS Nov_qty2,
            SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN pd.qty1 ELSE 0 END) AS Des_qty1,
            SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN pd.qty2 ELSE 0 END) AS Des_qty2,
            SUM(pd.qty1) AS Total1,
            SUM(pd.qty2) AS Total2
        ", false);
        } else {
            // Value/monetary option
            $subquery = $this->db->table('penjualan1_detail d2')
                ->select('SUM(d2.total)')
                ->join('penjualan1 h2', 'd2.id_penjualan = h2.id_penjualan')
                ->join('stock1 stck2', 'd2.id_stock = stck2.id_stock')
                ->where('YEAR(h2.tanggal)', $tahun - 1)
                ->where('d2.id_stock = pd.id_stock', null, false)
                ->getCompiledSelect();

            $builder->select("
                stck.id_stock,
                stck.kode AS kode_stock,
                pd.kode,
                pd.nama_barang,
                stck.conv_factor,
                pd.satuan,
                sup.nama AS nama_supplier,
                COALESCE(($subquery), 0) AS total_tahun_lalu,
                SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN pd.total ELSE 0 END) AS Jan,
                SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN pd.total ELSE 0 END) AS Feb,
                SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN pd.total ELSE 0 END) AS Mar,
                SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN pd.total ELSE 0 END) AS Apr,
                SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN pd.total ELSE 0 END) AS Mei,
                SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN pd.total ELSE 0 END) AS Jun,
                SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN pd.total ELSE 0 END) AS Jul,
                SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN pd.total ELSE 0 END) AS Agu,
                SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN pd.total ELSE 0 END) AS Sep,
                SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN pd.total ELSE 0 END) AS Okt,
                SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN pd.total ELSE 0 END) AS Nov,
                SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN pd.total ELSE 0 END) AS Des,
                SUM(pd.total) AS Total
            ", false);
        }

        // JOINs
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');
        $builder->join('stock1 stck', 'pd.id_stock = stck.id_stock', 'left');
        $builder->join('setupsupplier1 sup', 'stck.id_setupsupplier = sup.id_setupsupplier', 'left');

        // Filter tahun berjalan
        $builder->where('YEAR(p.tanggal)', $tahun);

        // Filter by supplier if provided
        if (!empty($supplier)) {
            $builder->where('sup.id_setupsupplier', $supplier);
        }

        // Group by stock-related columns only
        $builder->groupBy([
            'stck.id_stock',
            'pd.id_stock',
            'pd.kode',
            'pd.nama_barang',
            'stck.kode',
            'stck.conv_factor',
            'pd.satuan',
            'sup.nama'
        ]);

        // Order by stock code
        $builder->orderBy('pd.kode');

        return $builder->get()->getResult();
    }

    public function get_laporan_st($tahun)
    {

        $builder = $this->db->table('penjualan1 p');

        $subquery = $this->db->table('penjualan1 p2')
            ->select('SUM(p2.grand_total)')
            ->join('setupsalesman1 sm2', 'p2.id_salesman = sm2.id_salesman')
            ->where('YEAR(p2.tanggal)', $tahun - 1)
            ->getCompiledSelect();

        $builder->select("
            sm.nama_salesman,
            COALESCE(($subquery), 0) AS total_tahun_lalu,
            SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN p.grand_total ELSE 0 END) AS Jan,
            SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN p.grand_total ELSE 0 END) AS Feb,
            SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN p.grand_total ELSE 0 END) AS Mar,
            SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN p.grand_total ELSE 0 END) AS Apr,
            SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN p.grand_total ELSE 0 END) AS Mei,
            SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN p.grand_total ELSE 0 END) AS Jun,
            SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN p.grand_total ELSE 0 END) AS Jul,
            SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN p.grand_total ELSE 0 END) AS Agu,
            SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN p.grand_total ELSE 0 END) AS Sep,
            SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN p.grand_total ELSE 0 END) AS Okt,
            SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN p.grand_total ELSE 0 END) AS Nov,
            SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN p.grand_total ELSE 0 END) AS Des,
            SUM(p.grand_total) AS Total
        ", false);

        // Join dengan tabel terkait
        $builder->join('setupsalesman1 sm', 'p.id_salesman = sm.id_salesman', 'left');

        // Filter tahun berjalan
        $builder->where('YEAR(p.tanggal)', $tahun);

        // Group by stock-related columns only
        $builder->groupBy([
            'sm.id_salesman',
            'sm.nama_salesman'
        ]);

        // Order by stock code
        $builder->orderBy('sm.id_salesman');

        return $builder->get()->getResult();
    }

    public function get_laporan_s($tglawal, $tglakhir)
    {
        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom yang dibutuhkan
        $builder->select('
            sp.id_salesman,
            sp.nama_salesman, 
            SUM(pd.jml_harga) AS jml_harga,  
            SUM(pd.disc_1_perc) AS disc_1_perc, 
            SUM(pd.disc_1_rp) AS disc_1_rp, 
            SUM(pd.disc_2_perc) AS disc_2_perc,
            SUM(pd.disc_2_rp) AS disc_2_rp,
            SUM(pd.total) AS sub_total,
            SUM(p.disc_cash) AS disc_cash,
            SUM(p.netto) AS netto,
            SUM(p.ppn) AS ppn,
            SUM(p.grand_total) AS total
        ');

        // Join dengan tabel terkait
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');

        $builder->join('setupsalesman1 sp', 'p.id_salesman = sp.id_salesman', 'left');


        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Group by stock-related columns only
        $builder->groupBy([
            'sp.id_salesman',
            'sp.nama_salesman',
        ]);

        // Order by stock code
        $builder->orderBy('sp.id_salesman');

        // Eksekusi query dan kembalikan hasil
        return $builder->get()->getResult();
    }

    public function get_laporan_pp($tglawal, $tglakhir)
    {
        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom yang dibutuhkan
        $builder->select('
            pp.nama_pelanggan,
            pp.alamat_pelanggan,
            pp.class_pelanggan,
            SUM(pd.jml_harga) AS jml_harga,  
            SUM(pd.disc_1_perc) AS disc_1_perc, 
            SUM(pd.disc_1_rp) AS disc_1_rp, 
            SUM(pd.disc_2_perc) AS disc_2_perc,
            SUM(pd.disc_2_rp) AS disc_2_rp,
            SUM(pd.total) AS sub_total,
            SUM(p.disc_cash) AS disc_cash,
            SUM(p.netto) AS netto,
            SUM(p.ppn) AS ppn,
            SUM(p.grand_total) AS total
        ');

        // Join dengan tabel terkait
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');

        $builder->join('setuppelanggan1 pp', 'p.id_pelanggan = pp.id_pelanggan', 'left');


        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Group by stock-related columns only
        $builder->groupBy([
            'pp.id_pelanggan',
            'pp.nama_pelanggan',
            'pp.alamat_pelanggan',
            'pp.class_pelanggan',
        ]);

        // Order by stock code
        $builder->orderBy('pp.id_pelanggan');

        // Eksekusi query dan kembalikan hasil
        return $builder->get()->getResult();
    }

    public function get_laporan_ppt($tahun)
    {

        $builder = $this->db->table('penjualan1 p');

        $subquery = $this->db->table('penjualan1 p2')
            ->select('SUM(p2.grand_total)')
            ->join('setuppelanggan1 pp2', 'p2.id_pelanggan = pp2.id_pelanggan')
            ->where('YEAR(p2.tanggal)', $tahun - 1)
            ->getCompiledSelect();

        $builder->select("
            pp.nama_pelanggan,
            COALESCE(($subquery), 0) AS total_tahun_lalu,
            SUM(CASE WHEN MONTH(p.tanggal) = 1 THEN p.grand_total ELSE 0 END) AS Jan,
            SUM(CASE WHEN MONTH(p.tanggal) = 2 THEN p.grand_total ELSE 0 END) AS Feb,
            SUM(CASE WHEN MONTH(p.tanggal) = 3 THEN p.grand_total ELSE 0 END) AS Mar,
            SUM(CASE WHEN MONTH(p.tanggal) = 4 THEN p.grand_total ELSE 0 END) AS Apr,
            SUM(CASE WHEN MONTH(p.tanggal) = 5 THEN p.grand_total ELSE 0 END) AS Mei,
            SUM(CASE WHEN MONTH(p.tanggal) = 6 THEN p.grand_total ELSE 0 END) AS Jun,
            SUM(CASE WHEN MONTH(p.tanggal) = 7 THEN p.grand_total ELSE 0 END) AS Jul,
            SUM(CASE WHEN MONTH(p.tanggal) = 8 THEN p.grand_total ELSE 0 END) AS Agu,
            SUM(CASE WHEN MONTH(p.tanggal) = 9 THEN p.grand_total ELSE 0 END) AS Sep,
            SUM(CASE WHEN MONTH(p.tanggal) = 10 THEN p.grand_total ELSE 0 END) AS Okt,
            SUM(CASE WHEN MONTH(p.tanggal) = 11 THEN p.grand_total ELSE 0 END) AS Nov,
            SUM(CASE WHEN MONTH(p.tanggal) = 12 THEN p.grand_total ELSE 0 END) AS Des,
            SUM(p.grand_total) AS Total
        ", false);

        // Join dengan tabel terkait
        $builder->join('setuppelanggan1 pp', 'p.id_pelanggan = pp.id_pelanggan', 'left');

        // Filter tahun berjalan
        $builder->where('YEAR(p.tanggal)', $tahun);

        // Group by stock-related columns only
        $builder->groupBy([
            'pp.id_pelanggan',
            'pp.nama_pelanggan'
        ]);

        // Order by stock code
        $builder->orderBy('pp.id_pelanggan');

        return $builder->get()->getResult();
    }

    public function get_laporan_sb($tglawal, $tglakhir)
    {
        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom yang dibutuhkan
        $builder->select('
            sp.kode AS kode_supplier,
            sp.nama AS nama_supplier,
            stck.conv_factor,
            pd.kode AS kode_barang,
            pd.nama_barang,
            SUM(pd.qty1) AS qty1,
            SUM(pd.qty2) AS qty2,
            SUM(pd.harga_satuan) AS harga_satuan,
            SUM(pd.jml_harga) AS jml_harga,  
            SUM(pd.disc_1_perc) AS disc_1_perc, 
            SUM(pd.disc_1_rp) AS disc_1_rp, 
            SUM(pd.disc_2_perc) AS disc_2_perc,
            SUM(pd.disc_2_rp) AS disc_2_rp,
            SUM(pd.total) AS sub_total,
            SUM(p.disc_cash) AS disc_cash,
            SUM(p.netto) AS netto,
            SUM(p.ppn) AS ppn,
            SUM(p.grand_total) AS total
        ');

        // Join dengan tabel terkait
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');

        $builder->join('stock1 stck', 'pd.id_stock = stck.id_stock', 'left');

        $builder->join('setupsupplier1 sp', 'stck.id_setupsupplier = sp.id_setupsupplier', 'left');


        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Group by stock-related columns only
        $builder->groupBy([
            'sp.id_setupsupplier',
            'sp.kode',
            'stck.conv_factor',
            'sp.nama',
            'pd.kode',
            'pd.nama_barang',
        ]);

        // Order by stock code
        $builder->orderBy('sp.id_setupsupplier');

        // Eksekusi query dan kembalikan hasil
        return $builder->get()->getResult();
    }


    public function get_laporan_summary($tglawal, $tglakhir, $salesman, $lokasi = null)
    {
        $builder = $this->db->table('penjualan1 p');
        $builder->select('
        p.id_penjualan,
        p.grand_total,
        p.netto,
        p.ppn,
        p.disc_cash,
        p.sub_total,
        sm.nama_salesman,
        pg.nama_pelanggan
    ');

        // Join dengan tabel supplier
        $builder->join('setupsalesman1 sm', 'p.id_salesman = sm.id_salesman', 'left');
        $builder->join('setuppelanggan1 pg', 'p.id_pelanggan = pg.id_pelanggan', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Filter berdasarkan salesman jika diberikan
        if (!empty($salesman)) {
            $builder->where('p.id_salesman', $salesman);
        }
        // Filter berdasarkan lokasi jika diberikan
        if (!empty($lokasi)) {
            $builder->where('p.id_lokasi', $lokasi);
        }

        $builder->orderBy('p.tanggal');

        return $builder->get()->getResult();
    }

    public function get_laporan_summary_p($tglawal, $tglakhir, $salesman, $lokasi = null, $supplier = null, $stock = null, $pelanggan = null)
    {
        $builder = $this->db->table('penjualan1 p');
        $builder->select('
        p.id_penjualan,
        p.grand_total,
        p.netto,
        p.ppn,
        p.disc_cash,
        p.sub_total,
        sm.nama_salesman,
        pg.nama_pelanggan
    ');

        // Join dengan tabel terkait
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');

        $builder->join('setupsalesman1 sm', 'p.id_salesman = sm.id_salesman', 'left');
        $builder->join('setuppelanggan1 pg', 'p.id_pelanggan = pg.id_pelanggan', 'left');

        $builder->join('stock1 stck', 'pd.id_stock = stck.id_stock', 'left');
        $builder->join('setupsupplier1 sup', 'stck.id_setupsupplier = sup.id_setupsupplier', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        if (!empty($salesman)) {
            $builder->where('p.id_salesman', $salesman);
        }
        if (!empty($lokasi)) {
            $builder->where('p.id_lokasi', $lokasi);
        }
        if (!empty($supplier)) {
            $builder->where('sup.id_supplier', $supplier);
        }
        if (!empty($stock)) {
            $builder->where('pd.id_stock', $stock);
        }
        if (!empty($pelanggan)) {
            $builder->where('pg.id_pelanggan', $pelanggan);
        }

        $builder->orderBy('p.tanggal');

        return $builder->get()->getResult();
    }

    public function get_laporan_summary_s($tglawal, $tglakhir)
    {
        $builder = $this->db->table('penjualan1 p');
        $builder->select('
            sm.nama_salesman, 
            SUM(pd.jml_harga) AS jml_harga,
            SUM(pd.total) AS sub_total,
            SUM(p.disc_cash) AS disc_cash,
            SUM(p.netto) AS netto,
            SUM(p.ppn) AS ppn,
            SUM(p.grand_total) AS grand_total
        ');

        $builder->join('setupsalesman1 sm', 'p.id_salesman = sm.id_salesman', 'left');
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');


        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        $builder->groupBy([
            'sm.nama_salesman',
        ]);


        return $builder->get()->getResult();
    }

    public function get_laporan_summary_pp($tglawal, $tglakhir)
    {
        $builder = $this->db->table('penjualan1 p');
        $builder->select('
            pp.nama_pelanggan, 
            SUM(pd.jml_harga) AS jml_harga,
            SUM(pd.total) AS sub_total,
            SUM(p.disc_cash) AS disc_cash,
            SUM(p.netto) AS netto,
            SUM(p.ppn) AS ppn,
            SUM(p.grand_total) AS grand_total
        ');

        $builder->join('setuppelanggan1 pp', 'p.id_pelanggan = pp.id_pelanggan', 'left');
        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');


        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        $builder->groupBy([
            'pp.nama_pelanggan',
        ]);


        return $builder->get()->getResult();
    }

    public function get_laporan_summary_sb($tglawal, $tglakhir)
    {
        $builder = $this->db->table('penjualan1 p');
        $builder->select('
            sp.id_setupsupplier, 
            SUM(pd.jml_harga) AS jml_harga,
            SUM(pd.total) AS sub_total,
            SUM(p.disc_cash) AS disc_cash,
            SUM(p.netto) AS netto,
            SUM(p.ppn) AS ppn,
            SUM(p.grand_total) AS grand_total
        ');

        $builder->join('penjualan1_detail pd', 'p.id_penjualan = pd.id_penjualan', 'left');
        $builder->join('stock1 stck', 'pd.id_stock = stck.id_stock', 'left');
        $builder->join('setupsupplier1 sp', 'stck.id_setupsupplier = sp.id_setupsupplier', 'left');


        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        $builder->groupBy([
            'sp.id_setupsupplier',
        ]);


        return $builder->get()->getResult();
    }


    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // // Dates
    // protected $useTimestamps = false;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // // Validation
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // // Callbacks
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];
}
