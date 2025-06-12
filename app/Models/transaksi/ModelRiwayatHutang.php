<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelRiwayatHutang extends Model
{
    protected $table            = 'riwayat_transaksi_hutang';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'id_transaksi', 'jenis_transaksi', 'nota', 'id_setupsupplier', 'deskripsi', 'debit', 'kredit', 'saldo_setelah'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
