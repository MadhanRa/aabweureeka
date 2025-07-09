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
    protected $allowedFields    = ['tanggal', 'jenis_transaksi', 'id_transaksi', 'nota', 'id_setupbuku', 'deskripsi', 'debit', 'kredit', 'saldo_setelah'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getLaporanNeraca($tglawal = '', $tglakhir = '')
    {
        $builder = $this->db->table('riwayat_transaksi_rekening rw');
        $builder->select('sb.kode_setupbuku, sb.nama_setupbuku, 
                          SUM(CASE WHEN rw.debit > 0 THEN rw.debit ELSE 0 END) AS awal_debit,
                          SUM(CASE WHEN rw.kredit > 0 THEN rw.kredit ELSE 0 END) AS awal_kredit,
                          SUM(rw.debit) AS debit, 
                          SUM(rw.kredit) AS kredit,
                          SUM(CASE WHEN rw.debit > 0 THEN rw.debit ELSE 0 END) - SUM(CASE WHEN rw.kredit > 0 THEN rw.kredit ELSE 0 END) AS saldo_debit,
                          SUM(CASE WHEN rw.kredit > 0 THEN rw.kredit ELSE 0 END) - SUM(CASE WHEN rw.debit > 0 THEN rw.debit ELSE 0 END) AS saldo_kredit');

        $builder->join('setupbuku1 AS sb', 'rw.id_setupbuku = sb.id_setupbuku', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        $builder->groupBy('sb.kode_setupbuku, sb.nama_setupbuku');
        return $builder->get()->getResult();
    }

    public function getLaporanBuku($tglawal = '', $tglakhir = '', $id_setupbuku = '')
    {
        $builder = $this->db->table('riwayat_transaksi_rekening rw');
        $builder->select('rw.id, rw.tanggal, rw.nota, rw.deskripsi, rw.debit, rw.kredit, rw.saldo_setelah, sb.nama_setupbuku');

        $builder->join('setupbuku1 AS sb', 'rw.id_setupbuku = sb.id_setupbuku', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }
        // Filter rekening
        if (!empty($id_setupbuku)) {
            $builder->where('rw.id_setupbuku', $id_setupbuku);
        }
        $builder->orderBy('rw.tanggal', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan($tglawal = '', $tglakhir = '', $bank = '')
    {
        $builder = $this->db->table('riwayat_transaksi_rekening rw');

        $builder->select('rw.*, sb.nama_setupbank');
        $builder->join('setupbank1 AS sb', 'rw.id_setupbuku = sb.id_setupbuku', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        // Filter bank
        if (!empty($bank)) {
            $builder->where('sb.id_setupbank', $bank);
        }

        $builder->orderBy('rw.tanggal', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_daftar($tglawal = '', $tglakhir = '', $bank = '')
    {
        $builder = $this->db->table('setupbank1 sb');

        $builder->select('
            sb.kode_setupbank, 
            sb.nama_setupbank,
            bk.saldo_awal,
            SUM(rw.debit) AS debit,
            SUM(rw.kredit) AS kredit,
            bk.saldo_berjalan
        ');
        $builder->join('riwayat_transaksi_rekening AS rw', 'sb.id_setupbuku = rw.id_setupbuku', 'left');
        $builder->join('setupbuku1 AS bk', 'sb.id_setupbuku = bk.id_setupbuku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        // Filter bank
        if (!empty($bank)) {
            $builder->where('sb.id_setupbank', $bank);
        }

        $builder->groupBy('sb.id_setupbank, sb.kode_setupbank, sb.nama_setupbank, bk.saldo_awal, bk.saldo_berjalan');

        $builder->orderBy('sb.id_setupbank', 'ASC');
        return $builder->get()->getResult();
    }

    public function get_laporan_summary($tglawal = '', $tglakhir = '', $bank = '')
    {
        $builder = $this->db->table('riwayat_transaksi_rekening rw');

        $builder->select('sb.nama_setupbank,
                          bk.saldo_awal,
                          bk.saldo_berjalan,
                          SUM(rw.debit) AS debit, 
                          SUM(rw.kredit) AS kredit');

        $builder->join('setupbank1 AS sb', 'rw.id_setupbuku = sb.id_setupbuku', 'inner');
        $builder->join('setupbuku1 AS bk', 'sb.id_setupbuku = bk.id_setupbuku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        // Filter bank
        if (!empty($bank)) {
            $builder->where('sb.id_setupbank', $bank);
        }

        $builder->groupBy('sb.nama_setupbank, bk.saldo_awal, bk.saldo_berjalan');

        return $builder->get()->getResult();
    }

    public function get_laporan_summary_daftar($tglawal = '', $tglakhir = '', $bank = '')
    {
        $builder = $this->db->table('setupbank1 sb');

        $builder->select('sb.nama_setupbank,
                          bk.saldo_awal,
                          bk.saldo_berjalan,
                          SUM(rw.debit) AS debit, 
                          SUM(rw.kredit) AS kredit');

        $builder->join('riwayat_transaksi_rekening AS rw', 'sb.id_setupbuku =rw.id_setupbuku', 'left');
        $builder->join('setupbuku1 AS bk', 'sb.id_setupbuku = bk.id_setupbuku', 'left');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rw.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rw.tanggal <=', $tglakhir);
        }

        // Filter bank
        if (!empty($bank)) {
            $builder->where('sb.id_setupbank', $bank);
        }

        $builder->groupBy('sb.nama_setupbank, bk.saldo_awal, bk.saldo_berjalan');

        return $builder->get()->getResult();
    }
}
