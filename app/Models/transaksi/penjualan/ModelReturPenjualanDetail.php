<?php

namespace App\Models\transaksi\penjualan;

use CodeIgniter\Model;

class ModelReturPenjualanDetail extends Model
{
    protected $table            = 'returpenjualan1_detail';
    protected $primaryKey       = 'id';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['id_returpenjualan', 'id_stock', 'kode', 'nama_barang', 'satuan', 'qty1', 'qty2', 'harga_satuan', 'jml_harga', 'disc_1_perc', 'disc_1_rp', 'disc_2_perc', 'disc_2_rp', 'total'];

    public function getById($id_returpenjualan)
    {
        $builder = $this->db->table('returpenjualan1_detail rpd');
        $builder->select('rpd.*, s.conv_factor, h.harga_jualexc AS harga_satuan_exclude, h.harga_jualinc AS harga_satuan_include');
        $builder->join('stock1 s', 'rpd.id_stock = s.id_stock', 'left');
        $builder->join('harga1 h', 'rpd.id_stock = h.id_stock', 'left');
        $builder->where('id_returpenjualan', $id_returpenjualan);

        return $builder->get()->getResult();
    }
}
