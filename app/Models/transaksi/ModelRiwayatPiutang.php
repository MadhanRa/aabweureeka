<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelRiwayatPiutang extends Model
{
    protected $table            = 'riwayat_transaksi_piutang';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'pelaku', 'id_transaksi', 'jenis_transaksi', 'nota', 'id_pelaku', 'deskripsi', 'debit', 'kredit', 'saldo_setelah'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function get_laporan($tglawal = '', $tglakhir = '', $pelanggan = '')
    {
        $builder = $this->db->table('riwayat_transaksi_piutang rp');

        $builder->select('rp.*, sp.nama_pelanggan, sp.saldo_awal');
        $builder->join('setuppelanggan1 AS sp', 'rp.id_pelaku = sp.id_pelanggan', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rp.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rp.tanggal <=', $tglakhir);
        }

        // Filter hanya untuk pelanggan
        if (!empty($pelanggan)) {
            $builder->where('sp.id_pelanggan', $pelanggan);
        }

        $builder->where('rp.pelaku', 'pelanggan');

        $builder->orderBy('rp.tanggal', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_salesman($tglawal = '', $tglakhir = '', $salesman = '')
    {
        $builder = $this->db->table('riwayat_transaksi_piutang rp');

        $builder->select('rp.*, ss.nama_salesman, ss.saldo_awal');
        $builder->join('setupsalesman1 AS ss', 'rp.id_pelaku = ss.id_salesman', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rp.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rp.tanggal <=', $tglakhir);
        }

        // Filter hanya untuk salesman
        if (!empty($salesman)) {
            $builder->where('ss.id_salesman', $salesman);
        }

        $builder->where('rp.pelaku', 'salesman');

        $builder->orderBy('rp.tanggal', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_daftar($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('setuppelanggan1 sp');

        $builder->select('
            sp.kode_pelanggan, 
            sp.nama_pelanggan,
            sp.saldo_awal,
            SUM(rw.debit) AS debit,
            SUM(rw.kredit) AS kredit,
            sp.saldo
        ');
        $builder->join('riwayat_transaksi_piutang AS rw', 'sp.id_pelanggan = rw.id_pelaku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        $builder->groupBy('sp.id_pelanggan, sp.kode_pelanggan, sp.nama_pelanggan, sp.saldo_awal, sp.saldo');

        $builder->orderBy('sp.id_pelanggan', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_daftar_salesman($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('setupsalesman1 ss');

        $builder->select('
            ss.kode_salesman, 
            ss.nama_salesman,
            ss.saldo_awal,
            SUM(rw.debit) AS debit,
            SUM(rw.kredit) AS kredit,
            ss.saldo
        ');
        $builder->join('riwayat_transaksi_piutang AS rw', 'ss.id_salesman = rw.id_pelaku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        $builder->groupBy('ss.id_salesman, ss.kode_salesman, ss.nama_salesman, ss.saldo_awal, ss.saldo');

        $builder->orderBy('ss.id_salesman', 'ASC');
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

    public function get_laporan_summary($tglawal = '', $tglakhir = '', $pelanggan = '')
    {
        $builder = $this->db->table('riwayat_transaksi_piutang rp');

        $builder->select('sp.nama_pelanggan,
                          sp.saldo_awal,
                          sp.saldo,
                          SUM(rp.debit) AS debit, 
                          SUM(rp.kredit) AS kredit');

        $builder->join('setuppelanggan1 AS sp', 'rp.id_pelaku = sp.id_pelanggan', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rp.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rp.tanggal <=', $tglakhir);
        }

        // Filter pelanggan
        if (!empty($pelanggan)) {
            $builder->where('sp.id_pelanggan', $pelanggan);
        }

        $builder->where('rp.pelaku', 'pelanggan');

        $builder->groupBy(
            'sp.nama_pelanggan, sp.saldo_awal, sp.saldo'
        );

        return $builder->get()->getResult();
    }

    public function get_laporan_summary_salesman($tglawal = '', $tglakhir = '', $salesman = '')
    {
        $builder = $this->db->table('riwayat_transaksi_piutang rp');

        $builder->select('ss.nama_salesman,
                          ss.saldo_awal,
                          ss.saldo,
                          SUM(rp.debit) AS debit, 
                          SUM(rp.kredit) AS kredit');

        $builder->join('setupsalesman1 AS ss', 'rp.id_pelaku = ss.id_salesman', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rp.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rp.tanggal <=', $tglakhir);
        }

        //filter salesman
        if (!empty($salesman)) {
            $builder->where('ss.id_salesman', $salesman);
        }

        $builder->where('rp.pelaku', 'salesman');

        $builder->groupBy(
            'ss.nama_salesman, ss.saldo_awal, ss.saldo'
        );

        return $builder->get()->getResult();
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

    public function get_laporan_summary_daftar_salesman($tglawal = '', $tglakhir = '')
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


        $builder->groupBy('ss.nama_salesman, ss.saldo_awal, ss.saldo');

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
