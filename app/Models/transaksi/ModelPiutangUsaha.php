<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPiutangUsaha extends Model
{
    protected $table            = 'tutangusaha1';
    protected $primaryKey       = 'id_lunashusaha';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['nota', 'id_pelanggan', 'tanggal', 'id_setupbank', 'saldo', 'nilai_pelunasan', 'diskon', 'pdpt', 'sisa', 'keterangan'];

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
        // Memulai builder untuk tabel 'tutangusaha1' dengan alias 'p'
        $builder = $this->db->table('tutangusaha1 p');

        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            sp.nama_pelanggan AS nama_pelanggan, 
            sb.nama_setupbank AS nama_setupbank
        ');

        // Melakukan JOIN dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
        $builder->join('setuppelanggan1 sp', 'p.id_pelanggan = sp.id_pelanggan', 'left');

        // Melakukan JOIN dengan tabel 'setupbank1' untuk mendapatkan nama bank
        $builder->join('setupbank1 sb', 'p.id_setupbank = sb.id_setupbank', 'left');

        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();

        return $this->findAll();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
         // Memulai builder untuk tabel 'tutangusaha1' dengan alias 'p'
         $builder = $this->db->table('tutangusaha1 p');

         // Memilih kolom yang diperlukan dengan alias yang sesuai
         $builder->select('
             p.*, 
             sp.nama_pelanggan AS nama_pelanggan, 
             sb.nama_setupbank AS nama_setupbank
         ');
 
         // Melakukan JOIN dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
         $builder->join('setuppelanggan1 sp', 'p.id_pelanggan = sp.id_pelanggan', 'left');
 
         // Melakukan JOIN dengan tabel 'setupbank1' untuk mendapatkan nama bank
         $builder->join('setupbank1 sb', 'p.id_setupbank = sb.id_setupbank', 'left');
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
        // Memulai builder untuk tabel 'tutangusaha1' dengan alias 'p'
        $builder = $this->db->table('tutangusaha1 p');

        // Pilih kolom yang diperlukan, dengan join yang sesuai
        $builder->select('p.*, sp.nama_pelanggan AS nama_pelanggan, sb.nama_setupbank AS nama_setupbank');

        // Melakukan JOIN dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
        $builder->join('setuppelanggan1 sp', 'p.id_pelanggan = sp.id_pelanggan', 'left');

        // Melakukan JOIN dengan tabel 'setupbank1' untuk mendapatkan nama bank
        $builder->join('setupbank1 sb', 'p.id_setupbank = sb.id_setupbank', 'left');

        // Tambahkan kondisi where untuk id_lunashusaha
        $builder->where('p.id_lunashusaha', $id);

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
