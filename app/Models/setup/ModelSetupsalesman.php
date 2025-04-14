<?php

namespace App\Models\setup;

use CodeIgniter\Model;

class ModelSetupsalesman extends Model
{
    protected $table            = 'setupsalesman1';
    protected $primaryKey       = 'id_salesman';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['kode_salesman', 'nama_salesman', 'id_lokasi', 'saldo'];


    public function getSalesmanwithLokasi()
    {
        return $this->select('setupsalesman1.*, lokasi1.nama_lokasi')
            ->join('lokasi1', 'lokasi1.id_lokasi = setupsalesman1.id_lokasi', 'left')
            ->findAll();
    }

    public function getSalesmanById($id)
    {
        return $this->select('setupsalesman1.*, lokasi1.nama_lokasi')
            ->join('lokasi1', 'lokasi1.id_lokasi = setupsalesman1.id_lokasi', 'left')
            ->where('id_salesman', $id)
            ->first();
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
