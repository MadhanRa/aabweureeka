<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelMutasiStock extends Model
{
    protected $table            = 'mutasi_stock';
    protected $primaryKey       = 'id_mutasi';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'id_stock',
        'id_lokasi',
        'tanggal',
        'jenis',
        'qty1',
        'qty2',
        'nilai',
        'sumber_transaksi', // Pembelian, Penjualan, Retur, Penyesuaian, etc.
        'id_transaksi' // ID dari transaksi terkait
    ];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // // Dates
    // protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';
}
