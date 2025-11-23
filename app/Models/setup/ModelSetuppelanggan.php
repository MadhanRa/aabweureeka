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
        $builder = $this->db->table('setuppelanggan1 s');
        $builder->select('s.*, IFNULL(SUM(p.saldo), 0) AS saldo');
        $builder->join(
            'piutang p',
            "s.id_pelanggan = p.id_relasional AND p.relasi_tipe = 'pelanggan'",
            'left'
        );
        $builder->groupBy('s.id_pelanggan');

        $query = $builder->get();
        return $query->getResult();
    }

    public function getPelangganById($id)
    {
        // Mengambil data supplier berdasarkan ID dan jumlah saldo hutangnya
        $builder = $this->db->table('setuppelanggan1 s');
        $builder->select('s.*, IFNULL(SUM(p.saldo), 0) AS saldo');
        $builder->join('piutang p', "s.id_pelanggan = p.id_relasional AND p.relasi_tipe = 'pelanggan'", 'left');
        $builder->where('s.id_pelanggan', $id);
        $builder->groupBy('s.id_pelanggan');
        $query = $builder->get();
        return $query->getRow();
    }
}
