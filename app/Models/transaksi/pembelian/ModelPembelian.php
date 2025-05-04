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
    protected $allowedFields    = ['tanggal', 'nota', 'id_setupsupplier', 'TOP', 'tgl_jatuhtempo', 'tgl_invoice', 'no_invoice', 'id_lokasi', 'id_setupbuku', 'sub_total', 'disc_cash', 'dpp', 'ppn_option', 'ppn', 'tunai', 'hutang', 'grand_total'];

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
            s.kode_satuan AS kode_satuan,
            b.nama_setupbank AS nama_setupbank
        ');

        // Join dengan tabel 'lokasi1' untuk mendapatkan nama lokasi asal
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        // Join dengan tabel 'setupsupplier1' untuk mendapatkan nama supplier
        $builder->join('setupsupplier1 sp', 'p.id_setupsupplier = sp.id_setupsupplier', 'left');

        // Join dengan tabel 'satuan1' untuk mendapatkan kode satuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');

        // Join dengan tabel 'setupbank1' untuk mendapatkan nama bank
        $builder->join('setupbank1 b', 'p.id_setupbank = b.id_setupbank', 'left');
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
            ->orderBy('pembelian1.tanggal', 'DESC')
            ->findAll(); // Mengambil semua data dari tabel pembelian1
    }


    function getById($id)
    {

        $builder = $this->db->table('pembelian1 p');

        // Pilih kolom dari tabel utama dan tabel terkait
        $builder->select('
            p.*, 
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama AS nama_supplier, 
            s.kode_satuan AS kode_satuan,
            b.nama_setupbank AS nama_setupbank
        ');

        // Join dengan tabel 'lokasi1' untuk lokasi asal
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        // Join dengan tabel 'setupsupplier1' untuk nama supplier
        $builder->join('setupsupplier1 sp', 'p.id_setupsupplier = sp.id_setupsupplier', 'left');

        // Join dengan tabel 'satuan1' untuk kode satuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');

        // Join dengan tabel 'setupbank1' untuk nama bank
        $builder->join('setupbank1 b', 'p.id_setupbank = b.id_setupbank', 'left');

        // Tambahkan kondisi where untuk id
        $builder->where('p.id_pembelian', $id);

        return $builder->get()->getRow();
    }

    public function get_laporan($tglawal, $tglakhir, $supplier = null)
    {
        $builder = $this->db->table('pembelian1 p');
        $builder->select('
            p.*, 
            sp.nama AS nama_supplier, 
            s.kode_satuan AS kode_satuan
        ');

        // Join dengan tabel supplier
        $builder->join('setupsupplier1 sp', 'p.id_setupsupplier = sp.id_setupsupplier', 'left');

        // Join dengan tabel satuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');

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

        return $builder->get()->getResult();
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
