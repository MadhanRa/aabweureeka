<?php

namespace App\Models\setup;

use CodeIgniter\Model;

class ModelSetuppelanggan extends Model
{
    protected $table            = 'setuppelanggan1';
    protected $primaryKey       = 'id_pelanggan';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['kode_pelanggan', 'nama_pelanggan', 'alamat_pelanggan', 'kota_pelanggan', 'telp_pelanggan', 'plafond', 'npwp', 'class_pelanggan', 'tipe'];

    public function getAllPelanggan()
    {
        $builder = $this->db->table($this->table . ' s');
        $builder->select('
            s.id_pelanggan, s.kode_pelanggan, s.nama_pelanggan, s.alamat_pelanggan, s.kota_pelanggan, s.telp_pelanggan,
            COALESCE(SUM(rtp.kredit - rtp.debit), 0) AS saldo,
        ');
        $builder->join(
            'riwayat_transaksi_piutang rtp',
            "s.id_pelanggan = rtp.id_pelaku AND rtp.jenis_pelaku = 'pelanggan'",
            'left'
        );
        $builder->groupBy('s.id_pelanggan');
        $builder->orderBy('s.nama_pelanggan', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

    public function getPelangganById($id)
    {
        $builder = $this->db->table($this->table . ' p');
        $builder->select('
            p.*,
            COALESCE(SUM(rtp.kredit - rtp.debit), 0) AS saldo,
        ');
        $builder->join(
            'riwayat_transaksi_piutang rtp',
            "p.id_pelanggan = rtp.id_pelaku AND rtp.jenis_pelaku = 'pelanggan'",
            'left'
        );
        $builder->where('p.id_pelanggan', $id);
        $builder->groupBy('p.id_pelanggan');
        $query = $builder->get();
        return $query->getRow();
    }
}
