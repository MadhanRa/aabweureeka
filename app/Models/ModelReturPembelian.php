<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelReturPembelian extends Model
{
    protected $table            = 'returpembelian1';
    protected $primaryKey       = 'id_returpembelian';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['tanggal',
        'nota',
        'id_setupsupplier',
        'id_lokasi',
        'nama_stock',
        'id_satuan',
        'qty_1',
        'qty_2',
        'harga_satuan',
        'jml_harga',
        'disc_1',
        'disc_2',
        'total',
        'id_pembelian',
        'tgl_pembelian',
        'pembayaran',
        'tipe',
        'sub_total',
        'disc_cash',
        'ppn',
        'grand_total',
        'npwp',
        'terbilang'];

        function getAll()
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
            
            // Join dengan tabel 'lokasi1' untuk mendapatkan nama lokasi asal
            $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');
            
            // Join dengan tabel 'setupsupplier1' untuk mendapatkan nama supplier
            $builder->join('setupsupplier1 sp', 'p.id_setupsupplier = sp.id_setupsupplier', 'left');
            
            // Join dengan tabel 'satuan1' untuk mendapatkan kode satuan
            $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');
        
            // Join dengan tabel 'pembelian1' untuk mendapatkan tanggal dan nota pembelian
            $builder->join('pembelian1 pb_tgl', 'p.id_pembelian_tgl = pb_tgl.id_pembelian', 'left');
            $builder->join('pembelian1 pb_nota', 'p.id_pembelian_nota = pb_nota.id_pembelian', 'left');
            
            return $builder->get()->getResult();
        
            return $this->findAll();
        }
        
        
            function getById($id) {
                
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
