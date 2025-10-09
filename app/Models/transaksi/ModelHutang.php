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
        'id_pembelian',
        'nota',
        'sumber',
        'tanggal',
        'tgl_jatuhtempo',
        'nominal',
        'saldo'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getHutangBySupplier($id_supplier)
    {
        // Mengambil semua data riwayat hutang berdasarkan id supplier dengan jatuh tempo
        return $this->db->table('riwayat_transaksi_hutang AS rwh')
            ->select('rwh.*, hutang.tgl_jatuhtempo')
            ->join('hutang', 'rwh.id_hutang = hutang.id_hutang', 'left')
            ->where('hutang.id_setupsupplier', $id_supplier)
            ->get()
            ->getResult();
    }

    public function getSaldoHutangBySupplier($id_supplier)
    {
        // Menghitung total saldo hutang berdasarkan ID supplier
        $builder = $this->db->table($this->table);
        $builder->select('IFNULL(SUM(saldo), 0) AS total_saldo');
        $builder->where('id_setupsupplier', $id_supplier);
        $query = $builder->get();
        $result = $query->getRow();
        return $result ? $result->total_saldo : 0;
    }
}
