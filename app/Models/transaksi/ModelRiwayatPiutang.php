<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelRiwayatPiutang extends Model
{
    protected $table            = 'riwayat_transaksi_piutang';
    protected $primaryKey       = 'id_riwayat_piutang';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'id_piutang',
        'id_pelaku',
        'jenis_pelaku',
        'tanggal',
        'jenis_transaksi',
        'nota',
        'debit',
        'kredit',
        'deskripsi'
    ];

    public function get_laporan($tipe = 'pelanggan', $tglawal = '', $tglakhir = '', $id = '')
    {
        $builder = $this->db->table('riwayat_transaksi_piutang rp');

        $builder->select('rp.tanggal, rp.nota, rp.deskripsi, rp.debit, rp.kredit');

        if ($tipe === 'pelanggan') {
            $builder->where('rp.jenis_pelaku', 'pelanggan');
        } else if ($tipe === 'salesman') {
            $builder->where('rp.jenis_pelaku', 'salesman');
        }

        // Filter tanggal
        if ($tglawal !== '') {
            $builder->where('rp.tanggal >=', $tglawal);
        }
        if ($tglakhir !== '') {
            $builder->where('rp.tanggal <=', $tglakhir);
        }

        // Filter ID (pelanggan atau salesman)
        if ($id !== '') {
            $builder->where('rp.id_pelaku', $id);
        }

        $builder->orderBy('rp.tanggal', 'ASC');
        return $builder->get()->getResult();
    }


    public function get_laporan_daftar($tipe = 'pelanggan', $tglawal = '', $tglakhir = '')
    {
        if ($tipe === 'pelanggan') {
            $builder = $this->db->table('setuppelanggan1 sp');
            $builder->select("
                sp.id_pelanggan,
                sp.kode_pelanggan, 
                sp.nama_pelanggan,
                SUM(CASE WHEN rh.tanggal < '$tglawal' THEN rh.kredit - rh.debit ELSE 0 END) AS saldo_awal,
                SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.debit ELSE 0 END) AS debit,
                SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.kredit ELSE 0 END) AS kredit,
                (SUM(CASE WHEN rh.tanggal < '$tglawal' THEN rh.kredit - rh.debit ELSE 0 END)
                + SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.kredit ELSE 0 END)
                - SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.debit ELSE 0 END)
                ) AS saldo
            ");
            $builder->join('riwayat_transaksi_piutang AS rh', "sp.id_pelanggan = rh.id_pelaku AND rh.jenis_pelaku = 'pelanggan'", 'left');

            $builder->groupBy('sp.id_pelanggan');

            $builder->orderBy('sp.nama_pelanggan', 'ASC');
        } else if ($tipe === 'salesman') {
            $builder = $this->db->table('setupsalesman1 ss');
            $builder->select("
                ss.id_salesman,
                ss.kode_salesman, 
                ss.nama_salesman,
                SUM(CASE WHEN rh.tanggal < '$tglawal' THEN rh.kredit - rh.debit ELSE 0 END) AS saldo_awal,
                SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.debit ELSE 0 END) AS debit,
                SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.kredit ELSE 0 END) AS kredit,
                (SUM(CASE WHEN rh.tanggal < '$tglawal' THEN rh.kredit - rh.debit ELSE 0 END)
                + SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.kredit ELSE 0 END)
                - SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.debit ELSE 0 END)
                ) AS saldo
            ");
            $builder->join('riwayat_transaksi_piutang AS rh', "ss.id_salesman = rh.id_pelaku AND rh.jenis_pelaku = 'salesman'", 'left');


            $builder->groupBy('ss.id_salesman');

            $builder->orderBy('ss.nama_salesman', 'ASC');
        }


        return $builder->get()->getResult();
    }


    public function get_laporan_daftar_nota($tglawal = '', $tglakhir = '', $salesman = '', $pelanggan = '')
    {
        $builder = $this->db->table('piutang p');

        $builder->select("
            pel.kode_pelanggan,
            pel.nama_pelanggan,
            s.nama_salesman,
            s.kode_salesman,
            p.tanggal,
            p.nota,
            p.tgl_jatuhtempo,
            p.total_piutang AS saldo,
            MAX(CASE WHEN rtp.debit > 0 THEN rtp.tanggal END) AS tgl_bayar
        ", false);

        $builder->join(
            'setuppelanggan1 pel',
            "p.relasi_tipe = 'pelanggan' AND p.id_relasional = pel.id_pelanggan",
            'inner'
        );
        $builder->join('setupsalesman1 s', "p.relasi_tipe = 'salesman' AND p.id_relasional = s.id_salesman", 'left');
        $builder->join('riwayat_transaksi_piutang rtp', 'p.id_piutang = rtp.id_piutang', 'left');

        // contoh filter:
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }
        if (!empty($pelanggan)) {
            $builder->where('pel.id_pelanggan', $pelanggan);
        }

        if (!empty($salesman)) {
            $builder->where('s.id_salesman', $salesman);
        }

        $builder->groupBy("
            p.id_piutang,
            pel.kode_pelanggan,
            pel.nama_pelanggan,
            s.kode_salesman,
            s.nama_salesman,
            p.tanggal,
            p.nota,
            p.tgl_jatuhtempo,
            p.total_piutang
        ");
        $builder->orderBy('p.tanggal', 'ASC');
        $builder->orderBy('p.nota', 'ASC');

        return $builder->get()->getResult();
    }

    public function get_laporan_daftar_umur($per_tanggal = '', $salesman = '')
    {
        $builder = $this->db->table('piutang as p');

        $builder->select('
            pl.kode_pelanggan,
            pl.nama_pelanggan,
            p.tanggal,
            p.nota,
            p.tgl_jatuhtempo,
            p.total_piutang AS total_hutang
        ');
        $builder->join(
            'setuppelanggan1 AS pl',
            "pl.id_pelanggan = p.id_relasional 
            AND p.relasi_tipe = 'pelanggan'",
            'inner'
        );

        // Join for Salesman
        $builder->join(
            'piutang AS p_sales',
            "p_sales.ref_transaksi = p.ref_transaksi 
            AND p.relasi_tipe = 'salesman'",
            'left'
        );

        // Filter tanggal
        if (!empty($per_tanggal)) {
            $builder->where('p.tanggal <=', $per_tanggal);
        }

        // WHERE
        $builder->where('p.status', 'open');
        // Filter salesman kalau diisi
        if (!empty($salesman)) {
            $builder->where('p_sales.id_relasional', $salesman);
        }

        // Eksekusi
        $query  = $builder->get();
        return $query->getResult();
    }

    public function get_laporan_summary($tipe = 'salesman', $tglawal = '', $tglakhir = '', $id = '')
    {
        $builder = $this->db->table($this->table);

        $select = "
        COALESCE(SUM(
            CASE 
                WHEN " . ($tglawal ? "tanggal < '{$tglawal}'" : "0") . " 
                THEN kredit - debit 
                ELSE 0
            END
        ),0) AS saldo_awal,

        COALESCE(SUM(
            CASE 
                WHEN " . ($tglawal ? "tanggal BETWEEN '{$tglawal}' AND '{$tglakhir}'" : "tanggal <= '{$tglakhir}'") . "
                THEN debit 
                ELSE 0
            END
        ),0) AS total_debit,

        COALESCE(SUM(
            CASE 
                WHEN " . ($tglawal ? "tanggal BETWEEN '{$tglawal}' AND '{$tglakhir}'" : "tanggal <= '{$tglakhir}'") . "
                THEN kredit 
                ELSE 0
            END
        ),0) AS total_kredit
    ";

        $builder->select($select, false);

        // Filter pelaku
        if (!empty($id)) {
            $builder->where('id_pelaku', $id);
        }

        if (!empty($tipe)) {
            $builder->where('jenis_pelaku', $tipe);
        }

        $row = $builder->get()->getRow();

        // hitung saldo akhir
        $saldo_awal = (float) ($row->saldo_awal ?? 0);
        $total_debit = (float) ($row->total_debit ?? 0);
        $total_kredit = (float) ($row->total_kredit ?? 0);
        $saldo_akhir = $saldo_awal + $total_kredit - $total_debit;

        return (object) [
            'saldo_awal' => $saldo_awal,
            'debit' => $total_debit,
            'kredit' => $total_kredit,
            'saldo_akhir' => $saldo_akhir,
        ];
    }


    public function get_laporan_summary_daftar_nota($tgl_awal = '', $tgl_akhir = '', $pelanggan = '')
    {
        $builder = $this->db->table('piutang AS p');
        $builder->select("
            COALESCE(SUM(p.total_piutang), 0) AS total_awal,
            COALESCE(SUM(p.total_piutang), 0) AS total_saldo
        ", false);

        $builder->join('riwayat_transaksi_piutang rtp', 'p.id_piutang = rtp.id_piutang', 'left');

        if (!empty($tgl_awal)) {
            $builder->where('p.tanggal >=', $tgl_awal);
        }

        if (!empty($tgl_akhir)) {
            $builder->where('p.tanggal <=', $tgl_akhir);
        }

        if (!empty($pelanggan)) {
            $builder->where('p.id_relasional', $pelanggan);
        }


        $builder->where('p.relasi_tipe', 'pelanggan');


        $row = $builder->get()->getRow();

        return (object)[
            'saldo_awal'       => (float) ($row->total_awal ?? 0),
            'debit'      => (float) ($row->total_debit ?? 0),
            'kredit'     => (float) ($row->total_kredit ?? 0),
            'saldo_akhir'      => (float) ($row->total_saldo ?? 0),
        ];
    }
}
