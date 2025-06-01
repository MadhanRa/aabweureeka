<?php

namespace App\Models\transaksi\penjualan;

use CodeIgniter\Model;

class ModelPenjualanDetail extends Model
{
    protected $table            = 'penjualan1_detail';
    protected $primaryKey       = 'id';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['id_penjualan', 'id_stock', 'kode', 'nama_barang', 'satuan', 'qty1', 'qty2', 'harga_satuan', 'jml_harga', 'disc_1_perc', 'disc_1_rp', 'disc_2_perc', 'disc_2_rp', 'total'];
}
