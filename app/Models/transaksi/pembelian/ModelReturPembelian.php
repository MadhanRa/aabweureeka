<?php

namespace App\Models\transaksi\pembelian;

use CodeIgniter\Model;

class ModelReturPembelian extends Model
{
    protected $table            = 'returpembelian1';
    protected $primaryKey       = 'id_returpembelian';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'nota',
        'id_setupsupplier',
        'id_lokasi',
        'id_pembelian',
        'opsi_return',
        'sub_total',
        'disc_cash',
        'dpp',
        'ppn_option',
        'ppn',
        'grand_total',
    ];

    function getAll()
    {
        return $this->select('
                returpembelian1.*, 
                l1.nama_lokasi AS lokasi_asal, 
                sp.nama AS nama_supplier,
                pb.tanggal AS tgl_pembelian,
                pb.nota AS nota_pembelian
            ')
            ->join('lokasi1 l1', 'returpembelian1.id_lokasi = l1.id_lokasi', 'left')
            ->join('setupsupplier1 sp', 'returpembelian1.id_setupsupplier = sp.id_setupsupplier', 'left')
            ->join('pembelian1 pb', 'returpembelian1.id_pembelian = pb.id_pembelian', 'left')
            ->findAll();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        $builder = $this->db->table('returpembelian1 p');

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

        $builder = $this->db->table('returpembelian1 p');

        // Pilih kolom dari tabel utama dan tabel terkait
        $builder->select('
                    p.*, 
                    l1.nama_lokasi AS lokasi_asal, 
                    sp.nama AS nama_supplier, 
                    s.kode_satuan AS kode_satuan,
                    pb_tgl.tanggal AS tgl_pembelian, 
                    pb_nota.nota AS nota_pembelian
                ');

        // Join dengan tabel 'lokasi1' untuk lokasi asal
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        // Join dengan tabel 'setupsupplier1' untuk nama supplier
        $builder->join('setupsupplier1 sp', 'p.id_setupsupplier = sp.id_setupsupplier', 'left');

        // Join dengan tabel 'satuan1' untuk kode satuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');

        // Join dengan tabel 'pembelian1' untuk mendapatkan tanggal dan nota pembelian
        $builder->join('pembelian1 pb_tgl', 'p.id_pembelian_tgl = pb_tgl.id_pembelian', 'left');
        $builder->join('pembelian1 pb_nota', 'p.id_pembelian_nota = pb_nota.id_pembelian', 'left');

        // Tambahkan kondisi where untuk id
        $builder->where('p.id_returpembelian', $id);

        return $builder->get()->getRow();
    }

    public function get_laporan($tglawal, $tglakhir, $supplier = null)
    {
        $builder = $this->db->table('returpembelian1 p');
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
