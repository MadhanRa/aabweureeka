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
    protected $allowedFields    = ['tanggal', 'jenis_transaksi', 'id_rekening', 'nama_rekening', 'debit', 'kredit', 'saldo', 'keterangan'];

    protected $useTimestamps    = true;
    protected $createdField     = 'waktu_input';
}
