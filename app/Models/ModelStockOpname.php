<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelStockOpname extends Model
{
    protected $table            = 'stockopname1';
    protected $primaryKey       = 'id_stockopname';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['id_stockopname', 'id_lokasi', 'id_setupuser', 'tanggal', 'nota', 'nama_stock', 'id_satuan', 'qty_1', 'qty_2'];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // // Dates
    // protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    function getAll()
    {
        // Memulai builder untuk tabel 'stockopname1' dengan alias 'p'
        $builder = $this->db->table('stockopname1 p');

        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            sp.nama_lokasi AS nama_lokasi, 
            sb.nama_setupuser AS nama_setupuser, 
            su.kode_satuan AS kode_satuan
        ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 sp', 'p.id_lokasi = sp.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupuser1' untuk mendapatkan nama user
        $builder->join('setupuser1 sb', 'p.id_setupuser = sb.id_setupuser', 'left');

        // Melakukan JOIN dengan tabel 'satuan1' untuk mendapatkan kode satuan
        $builder->join('satuan1 su', 'p.id_satuan = su.id_satuan', 'left');

        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
         // Memulai builder untuk tabel 'stockopname1' dengan alias 'p'
         $builder = $this->db->table('stockopname1 p');

         // Memilih kolom yang diperlukan dengan alias yang sesuai
         $builder->select('
             p.*, 
             sp.nama_lokasi AS nama_lokasi, 
             sb.nama_setupuser AS nama_setupuser, 
             su.kode_satuan AS kode_satuan
         ');
 
         // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
         $builder->join('lokasi1 sp', 'p.id_lokasi = sp.id_lokasi', 'left');
 
         // Melakukan JOIN dengan tabel 'setupuser1' untuk mendapatkan nama user
         $builder->join('setupuser1 sb', 'p.id_setupuser = sb.id_setupuser', 'left');
 
         // Melakukan JOIN dengan tabel 'satuan1' untuk mendapatkan kode satuan
         $builder->join('satuan1 su', 'p.id_satuan = su.id_satuan', 'left');
         $builder->where('MONTH(p.tanggal)', $bulan);
         $builder->where('YEAR(p.tanggal)', $tahun);
         // Eksekusi query
         $query = $builder->get();
 
         // Mengembalikan hasil query sebagai array objek
         $data = $query->getResult();

        return [
            'data' => $data,           // Semua data
        ];
    }
    function getById($id)
    {
        // Memulai builder untuk tabel 'stockopname1' dengan alias 'p'
        $builder = $this->db->table('stockopname1 p');

        // Pilih kolom yang diperlukan, dengan join yang sesuai
        $builder->select('
            p.*, 
            sp.nama_lokasi AS nama_lokasi, 
            sb.nama_setupuser AS nama_setupuser, 
            su.kode_satuan AS kode_satuan
        ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 sp', 'p.id_lokasi = sp.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupuser1' untuk mendapatkan nama user
        $builder->join('setupuser1 sb', 'p.id_setupuser = sb.id_setupuser', 'left');

        // Melakukan JOIN dengan tabel 'satuan1' untuk mendapatkan kode satuan
        $builder->join('satuan1 su', 'p.id_satuan = su.id_satuan', 'left');

        // Tambahkan kondisi where untuk id_stockopname
        $builder->where('p.id_stockopname', $id);

        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan satu baris sebagai objek
        return $query->getRow();
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
