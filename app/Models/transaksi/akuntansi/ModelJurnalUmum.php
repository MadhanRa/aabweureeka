<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelJurnalUmum extends Model
{
    protected $table            = 'jurnalumum1';
    protected $primaryKey       = 'id_jurnalumum';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['tanggal','nota','rekening','b_pembantu','nama_rekening', 'nama_bpembantu','no_ref','debet','kredit','tgl_nota','keterangan'];

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
        $builder = $this->db->table('jurnalumum1 p');
        
        // Eksekusi query
        $query = $builder->get();
        
        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();

        return $this->findAll();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        $data = $this->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->findAll();


            return [
                'data' => $data,           // Semua data
            ];
    }
    function getById($id)
    {
        // Memulai builder untuk tabel 'tutangusaha1' dengan alias 'p'
        $builder = $this->db->table('jurnalumum1 p');
        
        // Tambahkan kondisi where untuk id_lunashusaha
        $builder->where('p.id_jurnalumum', $id);
        
        // Eksekusi query
        $query = $builder->get();
        
        // Mengembalikan satu baris sebagai objek
        return $query->getRow();
    }

    public function get_laporan($tglawal, $tglakhir = null)
{
    $builder = $this->db->table('jurnalumum1 p');

    // Filter berdasarkan tanggal awal dan akhir
    if (!empty($tglawal)) {
        $builder->where('p.tanggal >=', $tglawal);
    }
    if (!empty($tglakhir)) {
        $builder->where('p.tanggal <=', $tglakhir);
    }

    $data = $builder->get()->getResult();

    // Hitung total debit dan kredit
    $totalDebet = 0;
    $totalKredit = 0;
    foreach ($data as $row) {
        $totalDebet += floatval($row->debet);
        $totalKredit += floatval($row->kredit);
    }

    return [
        'data' => $data,                 // Data laporan
        'total_debet' => $totalDebet,    // Total debit
        'total_kredit' => $totalKredit,  // Total kredit
    ];
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
