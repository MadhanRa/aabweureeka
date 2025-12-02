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

    public function getDaftarPiutangPerNota($tgl_awal = '', $tgl_akhir = '', $id = '', $tipe = 'pelanggan')
    {
        $builder = $this->db->table($this->table . ' p');

        if ($tipe === 'salesman') {
            $builder->select("
                ss.kode_salesman,
                ss.nama_salesman,
                p.id_piutang,
                p.nota,
                p.tanggal,
                p.tgl_jatuhtempo,
                p.total_piutang AS awal,
                p.total_piutang AS saldo
            ", false);
            $builder->join('setupsalesman1 ss', "p.id_relasional = ss.id_salesman AND p.relasi_tipe = 'salesman'", 'left');
        } elseif ($tipe === 'pelanggan') {
            $builder->select("
                sp.kode_pelanggan,
                sp.nama_pelanggan,
                p.id_piutang,
                p.nota,
                p.tanggal,
                p.tgl_jatuhtempo,
                p.total_piutang AS awal,
                p.total_piutang AS saldo
            ", false);
            $builder->join('setuppelanggan1 sp', "p.id_relasional = sp.id_pelanggan AND p.relasi_tipe = 'pelanggan'", 'left');
        }

        $builder->join('riwayat_transaksi_piutang rtp', 'p.id_piutang = rtp.id_piutang', 'left');

        // Filter tanggal
        if (!empty($tgl_awal)) {
            $builder->where('p.tanggal >=', $tgl_awal);
        }

        if (!empty($tgl_akhir)) {
            $builder->where('p.tanggal <=', $tgl_akhir);
        }

        // Filter supplier
        if (!empty($id)) {
            $builder->where('p.id_relasional', $id);
        }

        $builder->groupBy('p.id_piutang, p.nota, p.tanggal, p.tgl_jatuhtempo, p.total_piutang');
        $builder->orderBy('p.tanggal', 'ASC');
        return $builder->get()->getResult();
    }

    public function getTotalPiutangPerNota($tgl_awal = '', $tgl_akhir = '', $tipe = 'pelanggan', $id = '')
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select("
            COALESCE(SUM(p.total_piutang), 0) AS total_awal,
            COALESCE(SUM(rtp.debit), 0) AS total_debit,
            COALESCE(SUM(rtp.kredit), 0) AS total_kredit,
            COALESCE(SUM(p.total_piutang), 0) AS total_saldo
        ", false);

        $builder->join('riwayat_transaksi_piutang rtp', 'p.id_piutang = rtp.id_piutang', 'left');

        if (!empty($tgl_awal)) {
            $builder->where('p.tanggal >=', $tgl_awal);
        }

        if (!empty($tgl_akhir)) {
            $builder->where('p.tanggal <=', $tgl_akhir);
        }

        if (!empty($id)) {
            $builder->where('p.id_relasional', $id);
        }

        $row = $builder->get()->getRow();

        return (object)[
            'awal'       => (float) ($row->total_awal ?? 0),
            'debit'      => (float) ($row->total_debit ?? 0),
            'kredit'     => (float) ($row->total_kredit ?? 0),
            'saldo'      => (float) ($row->total_saldo ?? 0),
        ];
    }
}
