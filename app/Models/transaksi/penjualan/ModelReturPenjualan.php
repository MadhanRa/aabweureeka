<?php

namespace App\Models\transaksi\penjualan;

use CodeIgniter\Model;

class ModelReturPenjualan extends Model
{
    protected $table            = 'returpenjualan1';
    protected $primaryKey       = 'id_returpenjualan';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'nota',
        'id_pelanggan',
        'id_salesman',
        'id_lokasi',
        'ppn_option',
        'opsi_return',
        'id_penjualan',
        'sub_total',
        'disc_cash',
        'netto',
        'ppn',
        'grand_total',
    ];

    function getAll()
    {
        $builder = $this->db->table('returpenjualan1 rp');

        // Pilih kolom dari tabel utama dan tabel terkait
        $builder->select('
                 rp.*, 
                l1.nama_lokasi AS lokasi_asal, 
                sp.nama_salesman AS nama_salesman, 
                pj.tanggal AS tgl_penjualan, 
                pj.nota AS nota_penjualan, 
                plg.nama_pelanggan AS nama_pelanggan
        ');

        $builder->join('lokasi1 l1', 'rp.id_lokasi = l1.id_lokasi', 'left');
        $builder->join('setupsalesman1 sp', 'rp.id_salesman = sp.id_salesman', 'left');
        $builder->join('setuppelanggan1 plg', 'rp.id_pelanggan = plg.id_pelanggan', 'left');
        $builder->join('penjualan1 pj', 'rp.id_penjualan = pj.id_penjualan', 'left');

        return $builder->get()->getResult();

        return $this->findAll();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        $data = $this->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->findAll();

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

        $builder = $this->db->table('returpenjualan1 rp');

        // Pilih kolom dari tabel utama dan tabel terkait
        $builder->select('
                  rp.*, 
                l1.nama_lokasi AS lokasi_asal, 
                s.nama_salesman , 
                p.tanggal AS tgl_penjualan, 
                p.nota AS nota_penjualan, 
                plg.nama_pelanggan
        ');

        $builder->join('lokasi1 l1', 'rp.id_lokasi = l1.id_lokasi', 'left');
        $builder->join('setupsalesman1 s', 'rp.id_salesman = s.id_salesman', 'left');
        $builder->join('setuppelanggan1 plg', 'rp.id_pelanggan = plg.id_pelanggan', 'left');
        $builder->join('penjualan1 p', 'rp.id_penjualan = p.id_penjualan', 'left');

        // Tambahkan kondisi where untuk id
        $builder->where('rp.id_returpenjualan', $id);

        return $builder->get()->getRow();
    }

    public function get_laporan($tglawal, $tglakhir, $lokasi, $salesman = null)
    {
        $builder = $this->db->table('penjualan1 p');

        // Pilih kolom dari tabel utama dan tabel terkait
        $builder->select('
        p.*, 
        l1.nama_lokasi AS lokasi_asal, 
        sp.nama_setupsalesman AS nama_setupsalesman, 
        s.kode_satuan AS kode_satuan,
        plg.nama_pelanggan AS nama_pelanggan
    ');

        // Join tabel terkait
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');
        $builder->join('setupsalesman1 sp', 'p.id_setupsalesman = sp.id_setupsalesman', 'left');
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');
        $builder->join('setuppelanggan1 plg', 'p.id_pelanggan = plg.id_pelanggan', 'left');

        // Filter berdasarkan tanggal
        $builder->where('p.tanggal >=', $tglawal);
        $builder->where('p.tanggal <=', $tglakhir);

        // Filter berdasarkan lokasi jika diberikan
        if (!empty($lokasi)) {
            $builder->where('p.id_lokasi', $lokasi);
        }

        // Filter berdasarkan salesman jika diberikan
        if (!empty($salesman)) {
            $builder->where('p.id_setupsalesman', $salesman);
        }

        // Ambil hasil query
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
