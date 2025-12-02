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

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

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
            $builder->join('riwayat_transaksi_piutang AS rh', "ss.id_pelanggan = rh.id_pelaku AND rh.jenis_pelaku = 'pelanggan'", 'left');

            $builder->groupBy('ss.id_pelanggan');

            $builder->orderBy('ss.nama', 'ASC');
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
        $builder = $this->db->table('penjualan1 p');

        $builder->select('
            sp.kode_pelanggan, 
            sp.nama_pelanggan,
            ss.kode_salesman, 
            ss.nama_salesman,
            p.tanggal,
            p.nota,
            p.tgl_jatuhtempo,
            sp.saldo_awal,
            rw.debit,
            rw.kredit,
            sp.saldo,
            pt.tanggal AS tgl_bayar
        ');
        $builder->join('setuppelanggan1 AS sp', 'p.id_pelanggan = sp.id_pelanggan', 'left');
        $builder->join('setupsalesman1 AS ss', 'p.id_salesman = ss.id_salesman', 'left');
        $builder->join('riwayat_transaksi_piutang AS rw', 'sp.id_pelanggan = rw.id_pelaku', 'left');
        $builder->join('tutangusaha1 pt', 'p.id_penjualan = pt.id_penjualan', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Filter salesman
        if (!empty($salesman)) {
            $builder->where('p.id_salesman', $salesman);
        }

        // Filter pelanggan
        if (!empty($pelanggan)) {
            $builder->where('p.id_pelanggan', $pelanggan);
        }

        $builder->where('rw.jenis_transaksi', 'penjualan');
        $builder->where('p.opsi_pembayaran', 'kredit');

        $builder->orderBy('p.id_penjualan', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_daftar_nota_salesman($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('penjualan1 p');

        $builder->select('
            ss.kode_salesman, 
            ss.nama_salesman,
            p.tanggal,
            p.nota,
            p.tgl_jatuhtempo,
            ss.saldo_awal,
            rw.debit,
            rw.kredit,
            ss.saldo,
        ');
        $builder->join('setupsalesman1 AS ss', 'p.id_salesman = ss.id_salesman', 'left');
        $builder->join('riwayat_transaksi_piutang AS rw', 'ss.id_salesman = rw.id_pelaku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        $builder->where('rw.jenis_transaksi', 'penjualan');
        $builder->where('p.opsi_pembayaran', 'kredit');

        $builder->orderBy('p.id_penjualan', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_daftar_umur($tglawal = '', $tglakhir = '', $salesman = '')
    {
        $builder = $this->db->table('penjualan1 p');

        $builder->select('
        sp.kode_pelanggan, 
        sp.nama_pelanggan,
        ss.nama_salesman,
        p.tanggal,
        p.nota,
        p.tgl_jatuhtempo,
        p.grand_total,
        (SELECT COALESCE(SUM(nilai_pelunasan), 0) FROM tutangusaha1 tu WHERE tu.id_penjualan = p.id_penjualan) as total_pelunasan
    ');
        $builder->join('setuppelanggan1 AS sp', 'p.id_pelanggan = sp.id_pelanggan', 'left');
        $builder->join('setupsalesman1 AS ss', 'p.id_salesman = ss.id_salesman', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Filter salesman
        if (!empty($salesman)) {
            $builder->where('p.id_salesman', $salesman);
        }

        // Hanya ambil yang masih memiliki sisa piutang
        $builder->having('p.grand_total > COALESCE(total_pelunasan, 0)');
        $builder->where('p.opsi_pembayaran', 'kredit');

        $builder->orderBy('p.tgl_jatuhtempo', 'ASC');
        return $builder->get()->getResult();
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

        // Filter supplier
        if (!empty($id)) {
            $builder->where('id_pelaku', $id);
            $builder->where('pelaku', $tipe);
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

    public function get_laporan_summary_daftar($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('setuppelanggan1 sp');

        $builder->select('sp.nama_pelanggan,
                          sp.saldo_awal,
                          sp.saldo,
                          SUM(rw.debit) AS debit, 
                          SUM(rw.kredit) AS kredit');

        $builder->join('riwayat_transaksi_piutang AS rw', 'sp.id_pelanggan =rw.id_pelaku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }


        $builder->groupBy('sp.nama_pelanggan, sp.saldo_awal, sp.saldo');

        return $builder->get()->getResult();
    }


    public function get_laporan_summary_daftar_nota($tglawal = '', $tglakhir = '', $salesman = '', $pelanggan = '')
    {
        $builder = $this->db->table('setuppelanggan1 sp');

        $builder->select('sp.nama_pelanggan,
                          sp.saldo_awal,
                          sp.saldo,
                          SUM(rw.debit) AS debit, 
                          SUM(rw.kredit) AS kredit');

        $builder->join('riwayat_transaksi_piutang AS rw', 'sp.id_pelanggan =rw.id_pelaku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        // Filter salesman
        if (!empty($salesman)) {
            $builder->where('rw.id_salesman', $salesman);
        }
        // Filter pelanggan
        if (!empty($pelanggan)) {
            $builder->where('rw.id_pelanggan', $pelanggan);
        }


        $builder->where('rw.jenis_transaksi', 'penjualan');


        $builder->groupBy('sp.nama_pelanggan, sp.saldo_awal, sp.saldo');

        return $builder->get()->getResult();
    }

    public function get_laporan_summary_daftar_nota_salesman($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('setupsalesman1 ss');

        $builder->select('ss.nama_salesman,
                          ss.saldo_awal,
                          ss.saldo,
                          SUM(rw.debit) AS debit, 
                          SUM(rw.kredit) AS kredit');

        $builder->join('riwayat_transaksi_piutang AS rw', 'ss.id_salesman =rw.id_pelaku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        $builder->where('rw.jenis_transaksi', 'penjualan');


        $builder->groupBy('ss.nama_salesman, ss.saldo_awal, ss.saldo');

        return $builder->get()->getResult();
    }
}
