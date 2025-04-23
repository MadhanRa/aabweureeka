<?php

namespace App\Models\setup;

use CodeIgniter\Model;

class ModelKelompokproduksi extends Model
{
    protected $table            = 'kelompokproduksi1';
    protected $primaryKey       = 'id_kelproduksi';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['kode_kelproduksi', 'nama_kelproduksi', 'id_setupbuku'];


    public function getKelProduksiWithBukuBesar()
    {
        return $this->select('kelompokproduksi1.*, setupbuku1.nama_setupbuku')
            ->join('setupbuku1', 'kelompokproduksi1.id_setupbuku = setupbuku1.id_setupbuku')
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
