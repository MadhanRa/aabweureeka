<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ClosedPeriodsModel extends Model
{
    protected $table            = 'closed_periods';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['periode_start', 'periode_end', 'is_closed'];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];




    // Method untuk mengecek apakah periode tertentu sudah ditutup
    public function isPeriodClosed($date)
    {
        if (empty($date)) {
            return false;
        }

        $builder = $this->db->table($this->table);
        $builder->where('periode_start <=', $date);
        $builder->where('periode_end >=', $date);
        $builder->where('is_closed', 1); // Cek hanya periode yang sudah ditutup

        $query = $builder->get();
        return $query->getNumRows() > 0;
    }


    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
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
