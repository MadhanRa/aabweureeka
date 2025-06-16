<?php

namespace App\Models\setup_persediaan;

use CodeIgniter\Model;

class ModelStock extends Model
{
    protected $table            = 'stock1';
    protected $primaryKey       = 'id_stock';
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_group', 'id_kelompok', 'id_setupsupplier', 'kode', 'nama_barang', 'min_stock', 'id_satuan', 'id_satuan2', 'conv_factor'];
    protected $useTimestamps = true;
    // Dates
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    public function getStockWithRelations()
    {
        return $this->select('stock1.*, 
                          group1.nama_group, 
                          kelompok1.nama_kelompok, 
                          satuan1.kode_satuan as kode_satuan,
                          satuan2.kode_satuan as kode_satuan2,
                          setupsupplier1.nama as nama_setupsupplier')
            ->join('group1', 'group1.id_group = stock1.id_group', 'left')
            ->join('kelompok1', 'kelompok1.id_kelompok = stock1.id_kelompok', 'left')
            ->join('setupsupplier1', 'setupsupplier1.id_setupsupplier = stock1.id_setupsupplier', 'left')
            ->join('satuan1', 'satuan1.id_satuan = stock1.id_satuan', 'left')
            ->join('satuan1 as satuan2', 'satuan2.id_satuan = stock1.id_satuan2', 'left')
            ->findAll();
    }

    public function getStockWithRelationsById($id)
    {
        return $this->select('stock1.*, 
                          group1.nama_group, 
                          kelompok1.nama_kelompok, 
                          satuan1.kode_satuan as kode_satuan,
                          satuan2.kode_satuan as kode_satuan2,
                          setupsupplier1.nama as nama_supplier')
            ->join('group1', 'group1.id_group = stock1.id_group', 'left')
            ->join('kelompok1', 'kelompok1.id_kelompok = stock1.id_kelompok', 'left')
            ->join('setupsupplier1', 'setupsupplier1.id_setupsupplier = stock1.id_setupsupplier', 'left')
            ->join('satuan1', 'satuan1.id_satuan = stock1.id_satuan', 'left')
            ->join('satuan1 as satuan2', 'satuan2.id_satuan = stock1.id_satuan2', 'left')
            ->where(['stock1.id_stock' => $id])
            ->first();
    }

    public function getStockWithSatuanRelations()
    {
        return $this->select('
                    stock1.id_stock, 
                    stock1.kode, 
                    stock1.nama_barang, 
                    stock1.conv_factor, 
                    satuan1.kode_satuan as kode_satuan,
                    satuan2.kode_satuan as kode_satuan2')
            ->join('satuan1', 'satuan1.id_satuan = stock1.id_satuan', 'left')
            ->join('satuan1 as satuan2', 'satuan2.id_satuan = stock1.id_satuan2', 'left')
            ->findAll();
    }

    public function getStockById($id)
    {
        return $this->select('
        stock1.id_stock,
        stock1.kode,
                          stock1.nama_barang, 
                          stock1.conv_factor')
            ->where(['stock1.id_stock' => $id])
            ->first();
    }
}
