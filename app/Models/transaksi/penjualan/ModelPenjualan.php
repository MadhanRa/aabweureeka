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

        // Tambahkan kondisi where untuk id
        $builder->where('p.id_penjualan', $id);

        return $builder->get()->getRow();
    }

    public function get_laporan($tglawal, $tglakhir, $salesman, $lokasi = null)
    {
        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom yang dibutuhkan
        $builder->select('
            p.*, 
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama_salesman AS nama_salesman, 
            s.kode_satuan AS kode_satuan, 
            plg.nama_pelanggan AS nama_pelanggan
        ');

        // Join dengan tabel terkait
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');
        $builder->join('setupsalesman1 sp', 'p.id_salesman = sp.id_salesman', 'left');
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');
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

        // Eksekusi query dan kembalikan hasil
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
