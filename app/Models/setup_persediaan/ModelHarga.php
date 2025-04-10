<?php

namespace App\Models\setup_persediaan;

use CodeIgniter\Model;

class ModelHarga extends Model
{
    protected $table            = 'harga1';
    protected $primaryKey       = 'id_harga';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'id_stock',
        'harga_jualexc',
        'harga_jualinc',
        'harga_beli'
    ];

    public function getAllHarga()
    {
        return $this->select('harga1.*, stock1.kode, stock1.nama_barang')
            ->join('stock1', 'stock1.id_stock = harga1.id_stock', 'left')
            ->findAll();
    }
}
