<?php

namespace App\Models\transaksi\pembelian;

use CodeIgniter\Model;

class ModelPembelian extends Model
{
    protected $table            = 'pembelian1';
    protected $primaryKey       = 'id_pembelian';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'nota', 'id_setupsupplier', 'TOP', 'tgl_jatuhtempo', 'tgl_invoice', 'no_invoice', 'id_lokasi', 'id_setupbuku', 'sub_total', 'disc_cash', 'disc_cash_rp', 'dpp', 'ppn_option', 'ppn', 'tunai', 'hutang', 'grand_total', 'status_lunas'];

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

    public function getDateById($id)
    {
        // Mengambil tanggal berdasarkan ID hasil_sablon
        $result = $this->select('date')->find($id);
        return $result ? $result['date'] : null;
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        $builder = $this->db->table('pembelian1 p');

        // Pilih kolom dari tabel utama dan tabel terkait
        $builder->select('
            p.*, 
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama AS nama_supplier, 
            b.nama_setupbuku AS nama_setupbuku
        ');

        // Join dengan tabel 'lokasi1' untuk mendapatkan nama lokasi asal
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        // Join dengan tabel 'setupsupplier1' untuk mendapatkan nama supplier
        $builder->join('setupsupplier1 sp', 'p.id_setupsupplier = sp.id_setupsupplier', 'left');


        // Join dengan tabel 'setupbuku1' untuk mendapatkan nama bank
        $builder->join('setupbuku1 b', 'p.id_setupbuku = b.id_setupbuku', 'left');
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

    function getAll()
    {
        return $this->select('pembelian1.*, setupsupplier1.nama AS nama_supplier, lokasi1.nama_lokasi AS lokasi_asal, setupbuku1.nama_setupbuku AS nama_setupbuku')
            ->join('setupsupplier1', 'pembelian1.id_setupsupplier = setupsupplier1.id_setupsupplier', 'left')
            ->join('lokasi1', 'pembelian1.id_lokasi = lokasi1.id_lokasi', 'left')
            ->join('setupbuku1', 'pembelian1.id_setupbuku = setupbuku1.id_setupbuku', 'left')
            ->orderBy('pembelian1.id_pembelian', 'desc')
            ->findAll(); // Mengambil semua data dari tabel pembelian1
    }

    function getAllNota()
    {
        return $this->select(
            'id_pembelian, nota'
        )
            ->orderBy('tanggal', 'DESC')
            ->findAll(); // Mengambil semua data dari tabel pembelian1
    }


    function getById($id)
    {
        return $this->select('pembelian1.*, setupsupplier1.nama AS nama_supplier, setupsupplier1.npwp AS npwp, lokasi1.nama_lokasi AS nama_lokasi, setupbuku1.nama_setupbuku AS nama_setupbuku')
            ->join('setupsupplier1', 'pembelian1.id_setupsupplier = setupsupplier1.id_setupsupplier', 'left')
            ->join('lokasi1', 'pembelian1.id_lokasi = lokasi1.id_lokasi', 'left')
            ->join('setupbuku1', 'pembelian1.id_setupbuku = setupbuku1.id_setupbuku', 'left')
            ->where('id_pembelian', $id)
            ->first(); // Mengambil data berdasarkan id_pembelian
    }

    public function get_laporan($tglawal, $tglakhir, $supplier = null)
    {
        $builder = $this->db->table('pembelian1 p');
        $builder->select('
        p.*,
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
        sp.nama AS nama_supplier,
        sp.kode AS kode_supplier
    ');

        // Join dengan tabel detail
        $builder->join('pembelian1_detail pd', 'p.id_pembelian = pd.id_pembelian', 'left');

        // Join dengan tabel supplier
        $builder->join('setupsupplier1 sp', 'p.id_setupsupplier = sp.id_setupsupplier', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Filter supplier (jika ada)
        if (!empty($supplier)) {
            $builder->where('p.id_setupsupplier', $supplier);
        }

        // Urutkan berdasarkan id_pembelian
        $builder->orderBy('p.id_pembelian, pd.id');

        return $builder->get()->getResult();
    }

    public function get_laporan_summary($tglawal, $tglakhir, $supplier = null)
    {
        $builder = $this->db->table('pembelian1 p');
        $builder->select('
        p.id_pembelian,
        p.grand_total,
        p.dpp,
        p.ppn,
        p.sub_total,
        sp.nama AS nama_supplier,
        sp.kode AS kode_supplier
    ');

        // Join dengan tabel supplier
        $builder->join('setupsupplier1 sp', 'p.id_setupsupplier = sp.id_setupsupplier', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Filter supplier (jika ada)
        if (!empty($supplier)) {
            $builder->where('p.id_setupsupplier', $supplier);
        }

        $builder->orderBy('p.tanggal');

        return $builder->get()->getResult();
    }

    public function searchAndDisplay($keyword = null, $start = 0, $length = 0)
    {
        $builder = $this->select('
            pembelian1.tanggal, 
            pembelian1.nota, 
            pembelian1.tgl_jatuhtempo, 
            pembelian1.tgl_invoice,
            no_invoice,
            setupsupplier1.nama AS nama_supplier')
            ->join('setupsupplier1', 'pembelian1.id_setupsupplier = setupsupplier1.id_setupsupplier', 'left');


        if ($keyword) {
            $builder->groupStart();
            $arr_keywords = explode(" ", $keyword);
            for ($i = 0; $i < count($arr_keywords); $i++) {
                $builder->orlike('pembelian1.nota', $arr_keywords[$i]);
                $builder->orlike('pembelian1.tgl_jatuhtempo', $arr_keywords[$i]);
                $builder->orlike('pembelian1.tgl_invoice', $arr_keywords[$i]);
                $builder->orlike('pembelian1.no_invoice', $arr_keywords[$i]);
                $builder->orlike('setupsupplier1.nama', $arr_keywords[$i]);
            }
            $builder->groupEnd();
        }

        if ($start != 0 or $length != 0) {
            $builder->limit($length, $start);
        }

        return $builder->orderBy('pembelian1.tanggal', 'DESC')->get()->getResult();
    }

    public function searchAndDisplayPembelianHutang($keyword = null, $start = 0, $length = 0, $supplier_id = null)
    {
        $builder = $this->select('
        pembelian1.id_pembelian,
        pembelian1.nota,
        pembelian1.tanggal,
        setupsupplier1.nama AS nama_supplier,
        pembelian1.tgl_jatuhtempo,
        pembelian1.no_invoice,
        hutang.saldo AS hutang,
        ')
            ->join('setupsupplier1', 'pembelian1.id_setupsupplier = setupsupplier1.id_setupsupplier', 'left')
            ->join('hutang', 'pembelian1.id_pembelian = hutang.id_pembelian', 'left')
            ->whereIn('status_lunas', ['sebagian', 'belum']);

        if ($supplier_id) {
            $builder->where('pembelian1.id_setupsupplier', $supplier_id);
        }


        if ($keyword) {
            $builder->groupStart();
            $arr_keywords = explode(" ", $keyword);
            for ($i = 0; $i < count($arr_keywords); $i++) {
                $builder->orlike('pembelian1.nota', $arr_keywords[$i]);
                $builder->orlike('pembelian1.tgl_jatuhtempo', $arr_keywords[$i]);
                $builder->orlike('pembelian1.tgl_invoice', $arr_keywords[$i]);
                $builder->orlike('pembelian1.no_invoice', $arr_keywords[$i]);
                $builder->orlike('setupsupplier1.nama', $arr_keywords[$i]);
            }
            $builder->groupEnd();
        }

        if ($start != 0 or $length != 0) {
            $builder->limit($length, $start);
        }

        return $builder->orderBy('pembelian1.tanggal', 'DESC')->get()->getResult();
    }
}

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
