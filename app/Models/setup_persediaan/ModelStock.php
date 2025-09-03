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
        return $this->select('stock1.*, 
                          satuan1.kode_satuan as satuan_1,
                          satuan2.kode_satuan as satuan_2,
                          harga1.harga_beli')
            ->join('harga1', 'harga1.id_stock = stock1.id_stock', 'left')
            ->join('satuan1', 'satuan1.id_satuan = stock1.id_satuan', 'left')
            ->join('satuan1 as satuan2', 'satuan2.id_satuan = stock1.id_satuan2', 'left')
            ->where(['stock1.id_stock' => $id])
            ->first();
    }

    public function searchAndDisplay($keyword = null, $start = 0, $length = 0, $supplierId = null)
    {
        $builder = $this->select('stock1.*, 
                          group1.nama_group, 
                          kelompok1.nama_kelompok, 
                          satuan1.kode_satuan as kode_satuan,
                          satuan2.kode_satuan as kode_satuan2')
            ->join('group1', 'group1.id_group = stock1.id_group', 'left')
            ->join('kelompok1', 'kelompok1.id_kelompok = stock1.id_kelompok', 'left')
            ->join('satuan1', 'satuan1.id_satuan = stock1.id_satuan', 'left')
            ->join('satuan1 as satuan2', 'satuan2.id_satuan = stock1.id_satuan2', 'left');

        if ($supplierId) {
            $builder->where('stock1.id_setupsupplier', $supplierId);
        }

        if ($keyword) {
            $builder->groupStart();
            $arr_keywords = explode(" ", $keyword);
            for ($i = 0; $i < count($arr_keywords); $i++) {
                $builder->orlike('stock1.nama_barang', $arr_keywords[$i]);
                $builder->orLike('stock1.kode', $arr_keywords[$i]);
                $builder->orLike('group1.nama_group', $arr_keywords[$i]);
                $builder->orLike('kelompok1.nama_kelompok', $arr_keywords[$i]);
                $builder->orLike('satuan1.kode_satuan', $arr_keywords[$i]);
                $builder->orLike('satuan2.kode_satuan', $arr_keywords[$i]);
            }
            $builder->groupEnd();
        }

        if ($start != 0 or $length != 0) {
            $builder->limit($length, $start);
        }

        return $builder->orderBy('stock1.nama_barang', 'ASC')->get()->getResult();
    }
}
