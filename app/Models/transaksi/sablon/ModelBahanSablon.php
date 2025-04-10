<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelBahanSablon extends Model
{
    protected $table            = 'bahansablon1';
    protected $primaryKey       = 'id_bahan';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['nota_pindah',
        'lokasi_asal',
        'lokasi_tujuan',
        'nama_stock',
        'satuan',
        'qty_1',
        'qty_2',
        'tanggal',];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    function getAll() {
        $builder = $this->db->table('bahansablon1 p'); // Ganti dengan nama tabel yang benar
        
        // Pilih kolom yang diperlukan, gunakan alias dengan benar
        $builder->select('p.*, l1.nama_lokasi AS lokasi_asal, l2.nama_lokasi AS lokasi_tujuan,  s.kode_satuan');
        
        // Join yang benar, pastikan nama tabel dan kolomnya sesuai
        $builder->join('lokasi1 l1', 'p.id_lokasi_asal = l1.id_lokasi'); // Lokasi asal
        $builder->join('lokasi1 l2', 'p.id_lokasi_tujuan = l2.id_lokasi'); // Lokasi tujuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan'); // Satuan

        $query = $builder->get(); // Eksekusi query
        return $query->getResult(); // Kembalikan hasil query

        return $this->findAll();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        $builder = $this->db->table('bahansablon1 p'); // Ganti dengan nama tabel yang benar
        
        // Pilih kolom yang diperlukan, gunakan alias dengan benar
        $builder->select('p.*, l1.nama_lokasi AS lokasi_asal, l2.nama_lokasi AS lokasi_tujuan,  s.kode_satuan');
        
        // Join yang benar, pastikan nama tabel dan kolomnya sesuai
        $builder->join('lokasi1 l1', 'p.id_lokasi_asal = l1.id_lokasi'); // Lokasi asal
        $builder->join('lokasi1 l2', 'p.id_lokasi_tujuan = l2.id_lokasi'); // Lokasi tujuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan'); // Satuan
        $builder->where('MONTH(p.tanggal)', $bulan);
        $builder->where('YEAR(p.tanggal)', $tahun);
        $query = $builder->get(); // Eksekusi query
        $data = $query->getResult(); // Kembalikan hasil query

    
            return [
                'data' => $data,           // Semua data
            ];
    }

    function getById($id) {
        $builder = $this->db->table('bahansablon1 p');
        
        // Pilih kolom yang diperlukan, dengan join yang sesuai
        $builder->select('p.*, l1.nama_lokasi AS lokasi_asal, l2.nama_lokasi AS lokasi_tujuan, s.kode_satuan');
        $builder->join('lokasi1 l1', 'p.id_lokasi_asal = l1.id_lokasi');
        $builder->join('lokasi1 l2', 'p.id_lokasi_tujuan = l2.id_lokasi');
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan');
        
        // Tambahkan kondisi where untuk id_bahan
        $builder->where('p.id_bahan', $id);
        
        $query = $builder->get();
        return $query->getRow(); // Mengembalikan satu baris sebagai objek
    }

    public function get_laporan($tglawal, $tglakhir = null)
    {
        $builder = $this->db->table('bahansablon1 p');
       // Pilih kolom yang diperlukan, dengan join yang sesuai
       $builder->select('p.*, l1.nama_lokasi AS lokasi_asal, l2.nama_lokasi AS lokasi_tujuan, s.kode_satuan');
       $builder->join('lokasi1 l1', 'p.id_lokasi_asal = l1.id_lokasi');
       $builder->join('lokasi1 l2', 'p.id_lokasi_tujuan = l2.id_lokasi');
       $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan');


        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }


        return $builder->get()->getResult();
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
}
