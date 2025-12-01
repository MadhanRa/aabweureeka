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
        'nota',
        'ref_transaksi',
        'tanggal',
        'tgl_jatuhtempo',
        'total_piutang',
        'status'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    public function getRiwayatPiutangById($id_relasional, $tipe)
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select("
            p.id_piutang,
            p.nota,
            p.tanggal,
            p.tgl_jatuhtempo,
            p.total_piutang + COALESCE(SUM(rtp.kredit - rtp.debit), 0) AS saldo
        ")
            ->join('riwayat_transaksi_piutang rtp', 'p.id_piutang = rtp.id_piutang', 'left')
            ->where('p.id_relasional', $id_relasional)
            ->where('p.relasi_tipe', $tipe)
            ->groupBy('p.id_piutang, p.nota, p.tanggal, p.tgl_jatuhtempo, p.total_piutang');
        return $builder->get()->getResult();
    }

    public function getSaldoPiutangById($id_relasional, $tipe)
    {
        // Menghitung total saldo hutang berdasarkan ID dan tipe
        $builder = $this->db->table('riwayat_transaksi_piutang rtp');
        $builder->select("COALESCE(SUM(rtp.kredit - rtp.debit), 0) AS saldo", false)
            ->where('rtp.id_pelaku', $id_relasional)
            ->where('rtp.jenis_pelaku', $tipe);

        $row = $builder->get()->getRow();
        return $row ? (float) $row->saldo : 0;
    }
}
