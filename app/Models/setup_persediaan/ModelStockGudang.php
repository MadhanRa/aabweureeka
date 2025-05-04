<?php

namespace App\Models\setup_persediaan;

use CodeIgniter\Model;

class ModelStockGudang extends Model
{
    protected $table            = 'stock1_gudang';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_lokasi', 'id_stock', 'qty1', 'qty2', 'jml_harga'];
    protected $useTimestamps = true;
    // Dates
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
