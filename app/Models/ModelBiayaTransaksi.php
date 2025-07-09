<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelBiayaTransaksi extends Model
{
    protected $table            = 'setupbiaya1_transaksi';
    protected $primaryKey       = 'id';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['id_setupbiaya', 'tanggal', 'nota', 'keterangan', 'debit', 'kredit', 'saldo_berjalan'];

    public function getDaftarBiaya($tglawal, $tglakhir, $rekening = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
        setupbiaya1.*, 
        setupbuku1.nama_setupbuku, 
        SUM(CASE WHEN keterangan LIKE "%saldo awal%" THEN debit ELSE 0 END) as debit_saldo_awal,
        SUM(CASE WHEN keterangan NOT LIKE "%saldo awal%" THEN debit ELSE 0 END) as debit_biasa,
        SUM(debit) as debit_total,
        SUM(kredit) as kredit
        ');
        $builder->join('setupbiaya1', 'setupbiaya1_transaksi.id_setupbiaya = setupbiaya1.id_setupbiaya', 'left');
        $builder->join('setupbuku1', 'setupbiaya1.id_setupbuku = setupbuku1.id_setupbuku', 'left');

        if ($tglawal) {
            $builder->where('tanggal >=', $tglawal);
        }

        if ($tglakhir) {
            $builder->where('tanggal <=', $tglakhir);
        }

        if ($rekening) {
            $builder->where('setupbiaya1.id_setupbuku', $rekening);
        }

        $builder->groupBy('setupbiaya1.id_setupbiaya');
        return $builder->get()->getResult();
    }

    public function getKartuBiaya($tglawal, $tglakhir, $rekening = null, $id_setupbiaya = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
        setupbiaya1_transaksi.tanggal, 
        setupbiaya1_transaksi.nota, 
        setupbiaya1_transaksi.keterangan, 
        setupbiaya1_transaksi.debit,
        setupbiaya1_transaksi.kredit,
        setupbiaya1_transaksi.saldo_berjalan');
        $builder->join('setupbiaya1', 'setupbiaya1_transaksi.id_setupbiaya = setupbiaya1.id_setupbiaya', 'left');
        $builder->join('setupbuku1', 'setupbiaya1.id_setupbuku = setupbuku1.id_setupbuku', 'left');

        if ($tglawal) {
            $builder->where('setupbiaya1_transaksi.tanggal >=', $tglawal);
        }
        if ($tglakhir) {
            $builder->where('setupbiaya1_transaksi.tanggal <=', $tglakhir);
        }
        if ($rekening) {
            $builder->where('setupbiaya1.id_setupbuku', $rekening);
        }
        if ($id_setupbiaya) {
            $builder->where('setupbiaya1_transaksi.id_setupbiaya', $id_setupbiaya);
        }
        $builder->orderBy('setupbiaya1_transaksi.tanggal', 'ASC');

        return $builder->get()->getResult();
    }

    public function getKartuBiayaSummary($tglawal, $tglakhir, $rekening = null, $id_setupbiaya = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
        SUM(CASE WHEN keterangan LIKE "%saldo awal%" THEN debit ELSE 0 END) as debit_saldo_awal,
        SUM(CASE WHEN keterangan NOT LIKE "%saldo awal%" THEN debit ELSE 0 END) as debit_biasa,
        SUM(debit) as debit_total,
        SUM(kredit) as kredit,
        SUM(saldo_berjalan) as saldo_berjalan');

        if ($tglawal) {
            $builder->where('tanggal >=', $tglawal);
        }
        if ($tglakhir) {
            $builder->where('tanggal <=', $tglakhir);
        }
        if ($rekening) {
            $builder->where('id_setupbuku', $rekening);
        }
        if ($id_setupbiaya) {
            $builder->where('id_setupbiaya', $id_setupbiaya);
        }

        return $builder->get()->getRow();
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
