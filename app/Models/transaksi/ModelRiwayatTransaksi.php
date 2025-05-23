<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelRiwayatTransaksi extends Model
{
    protected $table            = 'riwayat_transaksi_rekening';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'jenis_transaksi', 'id_transaksi', 'nota', 'id_rekening', 'deskripsi', 'debit', 'kredit', 'saldo_setelah'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
