<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelPiutang extends Model
{
    protected $table            = 'piutang';
    protected $primaryKey       = 'id_piutang';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'id_relasional',
        'relasi_tipe',
        'sumber',
        'tanggal',
        'tgl_jatuhtempo',
        'nominal',
        'saldo'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getRiwayatPiutangById($id_relasional, $tipe)
    {
        // Mengambil semua data riwayat piutang berdasarkan id relasional dengan jatuh tempo
        return $this->db->table('riwayat_transaksi_piutang AS rwp')
            ->select('rwp.*, piutang.tgl_jatuhtempo')
            ->join('piutang', 'rwp.id_piutang = piutang.id_piutang', 'left')
            ->where('piutang.id_relasional', $id_relasional)
            ->where('piutang.relasi_tipe', $tipe)
            ->get()
            ->getResult();
    }

    public function getSaldoPiutangById($id_relasional, $tipe)
    {
        // Menghitung total saldo hutang berdasarkan ID supplier
        $builder = $this->db->table($this->table);
        $builder->select('IFNULL(SUM(saldo), 0) AS total_saldo');
        $builder->where('id_relasional', $id_relasional);
        $builder->where('relasi_tipe', $tipe);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->total_saldo : 0;
    }
}
