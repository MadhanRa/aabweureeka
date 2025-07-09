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
    protected $allowedFields    = ['kode_setupbuku', 'nama_setupbuku', 'id_posneraca', 'tanggal_awal_saldo', 'saldo_awal', 'saldo_berjalan'];

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

    public function getRekeningKas($kodeKas)
    {
        return $this->select('setupbuku1.id_setupbuku, setupbuku1.kode_setupbuku, setupbuku1.nama_setupbuku, setupbuku1.id_posneraca')
            ->join('pos_neraca', 'setupbuku1.id_posneraca = pos_neraca.id_posneraca')
            ->whereIn('pos_neraca.kode_posneraca', $kodeKas)
            ->orderBy('setupbuku1.kode_setupbuku', 'ASC')
            ->findAll();
    }

    public function getAll()
    {
        return $this->select('setupbuku1.id_setupbuku, setupbuku1.kode_setupbuku, setupbuku1.nama_setupbuku, setupbuku1.tanggal_awal_saldo, setupbuku1.saldo_awal, setupbuku1.saldo_berjalan, pos_neraca.nama_posneraca')
            ->join('pos_neraca', 'setupbuku1.id_posneraca = pos_neraca.id_posneraca')
            ->orderBy('setupbuku1.kode_setupbuku', 'ASC')
            ->findAll();
    }

    public function getBukuById($id_setupbuku)
    {
        return $this->select('setupbuku1.id_setupbuku, setupbuku1.kode_setupbuku, setupbuku1.nama_setupbuku, pos_neraca.nama_posneraca')
            ->join('pos_neraca', 'setupbuku1.id_posneraca = pos_neraca.id_posneraca')
            ->where('setupbuku1.id_setupbuku', $id_setupbuku)
            ->first();
    }

    public function getNeraca($pertanggal)
    {
        $builder = $this->db->table('setupbuku1 sb')
            ->select('
                pn.nama_posneraca, 
                pn.kode_posneraca, 
                SUM(sb.saldo_awal) as total_saldo_awal, 
                SUM(sb.saldo_berjalan) as total_saldo_berjalan')
            ->join('pos_neraca pn', 'sb.id_posneraca = pn.id_posneraca')
            ->groupBy('pn.nama_posneraca, pn.kode_posneraca')
            ->orderBy('pn.kode_posneraca', 'ASC');

        if ($pertanggal) {
            $builder->where('sb.tanggal_awal_saldo <=', $pertanggal);
        }

        $query = $builder->get();

        return $query->getResult();
    }
}
