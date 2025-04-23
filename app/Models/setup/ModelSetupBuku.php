<?php

namespace App\Models\setup;

use CodeIgniter\Model;

class ModelSetupBuku extends Model
{
    protected $table            = 'setupbuku1';
    protected $primaryKey       = 'id_setupbuku';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['kode_setupbuku', 'nama_setupbuku', 'id_posneraca', 'nama_posneraca', 'tanggal_awal_saldo', 'saldo_awal', 'saldo_berjalan'];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // // Dates
    protected $useTimestamps = true;
    // protected $dateFormat    = 'datetime';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // // Validation
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // // Callbacks
    // protected $allowCallbacks = true;
    // protected $beforeInsert = ['addPosNeraca'];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];

    public function insert($data = null, bool $returnID = true)
    {
        if (isset($data['id_posneraca'])) {
            $posneracaModel = new ModelPosNeraca();
            $data['nama_posneraca'] = $posneracaModel->find($data['id_posneraca'])->nama_posneraca;
        }

        return parent::insert($data, $returnID);
    }

    public function save($data): bool
    {
        if (isset($data['id_posneraca'])) {
            $posneracaModel = new ModelPosNeraca();
            $data['nama_posneraca'] = $posneracaModel->find($data['id_posneraca'])->nama_posneraca;
        }

        return parent::save($data);
    }
}
