<?php

namespace App\Models\setup;

use CodeIgniter\Model;

class ModelHutangPiutang extends Model
{
    protected $table            = 'setup_hutang_piutang';
    protected $primaryKey       = 'id_hutang_piutang';
    protected $returnType       = 'object';
    protected $allowedFields    = ['tanggal', 'id_transaksi', 'nota', 'tanggal_jt', 'saldo', 'relasi_id', 'relasi_tipe', 'jenis'];


    public function getHutangPiutang($id, $relasi_tipe)
    {
        return $this->where(['relasi_id' => $id, 'relasi_tipe' => $relasi_tipe])->findAll();
    }
}
