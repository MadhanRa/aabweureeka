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
    protected $allowedFields    = ['id_hutang', 'tanggal', 'jenis_transaksi', 'nota', 'nominal', 'saldo_setelah', 'deskripsi'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function get_laporan($tglawal = '', $tglakhir = '', $supplier = '')
    {
        $builder = $this->db->table('riwayat_transaksi_hutang rh');

        $builder->select('rh.*, sp.nama, sp.saldo_awal');
        $builder->join('setupsupplier1 AS sp', 'rh.id_setupsupplier = sp.id_setupsupplier', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rh.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rh.tanggal <=', $tglakhir);
        }

        // Filter hanya untuk supplier
        if (!empty($supplier)) {
            $builder->where('sp.id_setupsupplier', $supplier);
        }

        $builder->orderBy('rh.tanggal', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_summary($tglawal = '', $tglakhir = '', $supplier = '')
    {
        $builder = $this->db->table('riwayat_transaksi_hutang rh');

        $builder->select('sp.nama,
                          sp.saldo_awal,
                          sp.saldo,
                          SUM(rh.debit) AS debit, 
                          SUM(rh.kredit) AS kredit');

        $builder->join('setupsupplier1 AS sp', 'rh.id_setupsupplier = sp.id_setupsupplier', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rh.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rh.tanggal <=', $tglakhir);
        }

        // Filter supplier
        if (!empty($supplier)) {
            $builder->where('sp.id_setupsupplier', $supplier);
        }

        $builder->groupBy(
            'sp.nama, sp.saldo_awal, sp.saldo'
        );

        return $builder->get()->getResult();
    }

    public function get_laporan_daftar($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('setupsupplier1 ss');

        $builder->select('
            ss.kode, 
            ss.nama,
            ss.saldo_awal,
            SUM(rh.debit) AS debit,
            SUM(rh.kredit) AS kredit,
            ss.saldo
        ');
        $builder->join('riwayat_transaksi_hutang AS rh', 'ss.id_setupsupplier = rh.id_setupsupplier', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rh.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rh.tanggal <=', $tglakhir);
        }

        $builder->groupBy('ss.id_setupsupplier, ss.kode, ss.nama, ss.saldo_awal, ss.saldo');

        $builder->orderBy('ss.id_setupsupplier', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_summary_daftar($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('setupsupplier1 ss');

        $builder->select('ss.nama,
                          ss.saldo_awal,
                          ss.saldo,
                          SUM(rh.debit) AS debit, 
                          SUM(rh.kredit) AS kredit');

        $builder->join('riwayat_transaksi_hutang AS rh', 'ss.id_setupsupplier =rh.id_setupsupplier', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rh.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rh.tanggal <=', $tglakhir);
        }


        $builder->groupBy('ss.nama, ss.saldo_awal, ss.saldo');

        return $builder->get()->getResult();
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
