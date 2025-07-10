<?php

namespace App\Models\setup;

use CodeIgniter\Model;

class ModelSetupBank extends Model
{
    protected $table            = 'setupbank1';
    protected $primaryKey       = 'id_setupbank';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['kode_setupbank', 'nama_setupbank', 'id_setupbuku'];

    public function getAll()
    {
        return $this->findAll(); // Mengambil semua data dari tabel lokasi1
    }

    public function getGroupWithBukuBesar()
    {
        return $this->select('setupbank1.*, setupbuku1.nama_setupbuku')
            ->join('setupbuku1', 'setupbuku1.id_setupbuku = setupbank1.id_setupbuku', 'left')
            ->findAll();
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
