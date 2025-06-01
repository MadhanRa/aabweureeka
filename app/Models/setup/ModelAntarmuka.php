<?php

namespace App\Models\setup;

use CodeIgniter\Model;

class ModelAntarmuka extends Model
{
    protected $table            = 'interface1';
    protected $primaryKey       = 'id_interface';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['kas_setara', 'biaya', 'hutang', 'hpp',    'terima_mundur', 'kl_laba_ditahan',    'hutang_lancar', 'neraca_laba', 'piutang_salesman', 'rekening_biaya', 'piutang_dagang', 'penjualan',    'retur_penjualan', 'diskon_penjualan', 'laba_bulan', 'laba_tahun', 'laba_ditahan', 'potongan_pembelian', 'ppn_masukan', 'ppn_keluaran', 'potongan_penjualan',    'bank'];


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

    public function getKodeKas()
    {
        // Mengambil kode kas dari tabel interface1
        $kode = $this->findAll()[0]->kas_setara;

        return $kode ? $kode : null;
    }

    public function getKodeRekening($nama_kolom)
    {
        $kode = $this->findAll()[0]->$nama_kolom;
        return $kode ? $kode : null;
    }
}
