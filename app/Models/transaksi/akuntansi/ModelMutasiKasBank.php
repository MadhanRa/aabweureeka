<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMutasiKasBank extends Model
{
    protected $table            = 'mutasikasbank1';
    protected $primaryKey       = 'id_mutasikasbank';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'nota', 'id_klasifikasi', 'rekening', 'b_pembantu', 'nama_rekening', 'nama_bpembantu', 'no_ref', 'debet', 'kredit', 'mutasi', 'tgl_nota', 'keterangan'];

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
        $builder = $this->db->table('mutasikasbank1 p');

        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            sp.kas_interface AS kas_interface 
        ');

        // Melakukan JOIN dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
        $builder->join('interface1 sp', 'p.id_interface = sp.id_interface', 'left');


        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();

        return $this->findAll();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        $builder = $this->db->table('mutasikasbank1 p');
        $builder->select('
             p.*, 
             sp.kas_interface AS kas_interface 
         ');
        $builder->join('interface1 sp', 'p.id_interface = sp.id_interface', 'left');
        $builder->where('MONTH(p.tanggal)', $bulan);
        $builder->where('YEAR(p.tanggal)', $tahun);
        $query = $builder->get();
        $data = $query->getResult();

        return [
            'data' => $data,           // Semua data
        ];
    }
    function getById($id)
    {
        // Memulai builder untuk tabel 'tutangusaha1' dengan alias 'p'
        $builder = $this->db->table('mutasikasbank1 p');

        // Pilih kolom yang diperlukan, dengan join yang sesuai
        $builder->select('p.*, sp.kas_interface AS kas_interface, sp.nama AS nama, sb.nama_setupbank AS nama_setupbank');

        // Melakukan JOIN dengan tabel 'interface1' untuk mendapatkan kas_interface
        $builder->join('interface1 sp', 'p.id_interface = sp.id_interface', 'left');

        // Tambahkan kondisi where untuk id_lunashusaha
        $builder->where('p.id_mutasikasbank', $id);

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
