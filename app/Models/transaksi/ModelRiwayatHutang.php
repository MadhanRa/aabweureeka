<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelRiwayatHutang extends Model
{
    protected $table            = 'riwayat_transaksi_hutang';
    protected $primaryKey       = 'id_riwayat_hutang';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'id_hutang',
        'id_setupsupplier',
        'tanggal',
        'jenis_transaksi',
        'nota',
        'debit',
        'kredit',
        'deskripsi'
    ];

    public function get_laporan($tglawal = '', $tglakhir = '', $supplier = '')
    {
        $builder = $this->db->table('riwayat_transaksi_hutang');

        $builder->select('tanggal, nota, deskripsi, debit, kredit');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('tanggal <=', $tglakhir);
        }

        // Filter hanya untuk supplier
        if (!empty($supplier)) {
            $builder->where('id_setupsupplier', $supplier);
        }

        $builder->orderBy('tanggal', 'ASC');
        $builder->orderBy('id_riwayat_hutang', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_summary($tglawal = '', $tglakhir = '', $supplier = '')
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

        // Filter supplier
        if (!empty($supplier)) {
            $builder->where('id_setupsupplier', $supplier);
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

    public function get_laporan_daftar($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('setupsupplier1 ss');

        $builder->select("
            ss.id_setupsupplier,
            ss.kode, 
            ss.nama,
            SUM(CASE WHEN rh.tanggal < '$tglawal' THEN rh.kredit - rh.debit ELSE 0 END) AS saldo_awal,
            SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.debit ELSE 0 END) AS debit,
            SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.kredit ELSE 0 END) AS kredit,
            (SUM(CASE WHEN rh.tanggal < '$tglawal' THEN rh.kredit - rh.debit ELSE 0 END)
            + SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.kredit ELSE 0 END)
            - SUM(CASE WHEN rh.tanggal BETWEEN '$tglawal' AND '$tglakhir' THEN rh.debit ELSE 0 END)
            ) AS saldo
        ");
        $builder->join('riwayat_transaksi_hutang AS rh', 'ss.id_setupsupplier = rh.id_setupsupplier', 'left');


        $builder->groupBy('ss.id_setupsupplier');

        // Filter tanggal

        $builder->orderBy('ss.nama', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_summary_daftar($tglawal = '', $tglakhir = '')
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

    public function get_laporan_daftar_nota($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('pembelian1 p');

        $builder->select('
            ss.kode, 
            ss.nama,
            p.tanggal,
            p.nota,
            p.tgl_jatuhtempo,
            ss.saldo_awal,
            rh.debit,
            rh.kredit,
            ss.saldo,
        ');
        $builder->join('setupsupplier1 AS ss', 'p.id_setupsupplier = ss.id_setupsupplier', 'left');
        $builder->join('riwayat_transaksi_hutang AS rh', 'ss.id_setupsupplier = rh.id_setupsupplier', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        $builder->where('rh.jenis_transaksi', 'pembelian');
        // Hanya mengambil pembelian yang memiliki hutang > 0
        $builder->where('p.hutang >', 0);

        $builder->orderBy('p.id_pembelian', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_summary_daftar_nota($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('setupsupplier1 ss');

        $builder->select('ss.nama,
                          ss.saldo_awal,
                          ss.saldo,
                          SUM(rh.debit) AS debit, 
                          SUM(rh.kredit) AS kredit');

        $builder->join('riwayat_transaksi_hutang AS rh', 'ss.id_setupsupplier = rh.id_setupsupplier', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rh.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rh.tanggal <=', $tglakhir);
        }

        $builder->where('rh.jenis_transaksi', 'pembelian');


        $builder->groupBy('ss.nama, ss.saldo_awal, ss.saldo');

        return $builder->get()->getResult();
    }
}
