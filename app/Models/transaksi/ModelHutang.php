<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelHutang extends Model
{
    protected $table            = 'hutang';
    protected $primaryKey       = 'id_hutang';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'id_setupsupplier',
        'nota',
        'sumber',
        'tanggal',
        'tgl_jatuhtempo',
        'total_hutang',
        'status',
        'ref_transaksi'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    public function getSaldoHutangBySupplier($id_supplier)
    {
        // Menghitung total saldo hutang berdasarkan ID supplier
        $builder = $this->db->table('riwayat_transaksi_hutang rth');
        $builder->select("COALESCE(SUM(rth.kredit - rth.debit), 0) AS saldo", false)
            ->where('rth.id_setupsupplier', $id_supplier);

        $row = $builder->get()->getRow();
        return $row ? (float) $row->saldo : 0;
    }

    public function getRiwayatHutangBySupplier($id_supplier)
    {
        $builder = $this->db->table($this->table . ' h');
        $builder->select("
            h.id_hutang,
            h.nota,
            h.tanggal,
            h.tgl_jatuhtempo,
            h.total_hutang + COALESCE(SUM(rth.kredit - rth.debit), 0) AS saldo
        ")
            ->join('riwayat_transaksi_hutang rth', 'h.id_hutang = rth.id_hutang', 'left')
            ->where('h.id_setupsupplier', $id_supplier)
            ->groupBy('h.id_hutang, h.nota, h.tanggal, h.tgl_jatuhtempo, h.total_hutang');
        return $builder->get()->getResult();
    }

    public function getDaftarHutangPerNota($tgl_awal = '', $tgl_akhir = '', $supplier = '')
    {
        $builder = $this->db->table($this->table . ' h');
        $builder->select("
            ss.kode,
            ss.nama,
            h.id_hutang,
            h.nota,
            h.tanggal,
            h.tgl_jatuhtempo,
            h.total_hutang AS awal,
            h.total_hutang AS saldo
        ", false);

        $builder->join('riwayat_transaksi_hutang rth', 'h.id_hutang = rth.id_hutang', 'left');

        $builder->join('setupsupplier1 ss', 'h.id_setupsupplier = ss.id_setupsupplier', 'left');

        // Filter tanggal
        if (!empty($tgl_awal)) {
            $builder->where('h.tanggal >=', $tgl_awal);
        }

        if (!empty($tgl_akhir)) {
            $builder->where('h.tanggal <=', $tgl_akhir);
        }

        // Filter supplier
        if (!empty($supplier)) {
            $builder->where('h.id_setupsupplier', $supplier);
        }

        $builder->groupBy('h.id_hutang, h.nota, h.tanggal, h.tgl_jatuhtempo, h.total_hutang');
        $builder->orderBy('h.tanggal', 'ASC');
        return $builder->get()->getResult();
    }

    public function getTotalHutangPerNota($tgl_awal = '', $tgl_akhir = '', $supplier = '')
    {
        $builder = $this->db->table('hutang h');
        $builder->select("
        COALESCE(SUM(h.total_hutang), 0) AS total_awal,
        COALESCE(SUM(rth.debit), 0) AS total_debit,
        COALESCE(SUM(rth.kredit), 0) AS total_kredit,
        COALESCE(SUM(h.total_hutang), 0) AS total_saldo
    ", false);

        $builder->join('riwayat_transaksi_hutang rth', 'h.id_hutang = rth.id_hutang', 'left');

        if (!empty($tgl_awal)) {
            $builder->where('h.tanggal >=', $tgl_awal);
        }

        if (!empty($tgl_akhir)) {
            $builder->where('h.tanggal <=', $tgl_akhir);
        }

        if (!empty($supplier)) {
            $builder->where('h.id_setupsupplier', $supplier);
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
