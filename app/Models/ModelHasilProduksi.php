<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelHasilProduksi extends Model
{
    protected $table            = 'hasilproduksi1';
    protected $primaryKey       = 'id_produksi';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['nota_produksi','id_lokasi','id_kelproduksi','nama_stock','id_satuan', 'qty_1', 'qty_2', 'harga_satuan', 'jml_harga','tanggal'];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // Dates
    // protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    function getAll()
{
        // Memulai builder untuk tabel 'hasilsablon1' dengan alias 'p'
        $builder = $this->db->table('hasilproduksi1 p');
        
        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama_kelproduksi, 
            s.kode_satuan
        ');
        
        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi asal
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');
        
        // Melakukan JOIN dengan tabel 'kelompokproduksi1' untuk mendapatkan nama supplier
        $builder->join('kelompokproduksi1 sp', 'p.id_kelproduksi = sp.id_kelproduksi', 'left');
        
        // Melakukan JOIN dengan tabel 'satuan1' untuk mendapatkan kode satuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');
        
        // Eksekusi query
        $query = $builder->get();
        
        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();

        return $this->findAll();
}
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
