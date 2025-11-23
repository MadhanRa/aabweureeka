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
    protected $allowedFields    = ['kode_salesman', 'nama_salesman', 'id_lokasi'];

    public function getAllSalesman()
    {
        $builder = $this->db->table('setupsalesman1 s');
        $builder->select('s.*, IFNULL(SUM(p.saldo), 0) AS saldo, lokasi1.nama_lokasi');
        $builder->join(
            'piutang p',
            "s.id_salesman = p.id_relasional AND p.relasi_tipe = 'salesman'",
            'left'
        );
        $builder->join('lokasi1', 'lokasi1.id_lokasi = s.id_lokasi', 'left');
        $builder->groupBy('s.id_salesman');

        $query = $builder->get();
        return $query->getResult();
    }

    public function getSalesmanById($id)
    {
        // Mengambil data supplier berdasarkan ID dan jumlah saldo hutangnya
        $builder = $this->db->table('setupsalesman1 s');
        $builder->select('s.*, IFNULL(SUM(p.saldo), 0) AS saldo, lokasi1.nama_lokasi');
        $builder->join('piutang p', "s.id_salesman = p.id_relasional AND p.relasi_tipe = 'salesman'", 'left');
        $builder->join('lokasi1', 'lokasi1.id_lokasi = s.id_lokasi', 'left');
        $builder->where('s.id_salesman', $id);
        $builder->groupBy('s.id_salesman');
        $query = $builder->get();
        return $query->getRow();
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
