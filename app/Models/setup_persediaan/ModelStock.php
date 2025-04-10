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

    public function baseQuery()
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
            ->join('satuan1 as satuan2', 'satuan2.id_satuan = stock1.id_satuan2', 'left');
    }

    public function ajaxGetData($start, $length)
    {
        $result = $this->baseQuery()
            ->findAll($length, $start);
        return $result;
    }

    public function ajaxGetDataSearch($search, $start, $length)
    {
        $result = $this->baseQuery()
            ->like('stock1.kode', $search)
            ->orLike('stock1.nama_barang', $search)
            ->orLike('group1.nama_group', $search)
            ->orLike('kelompok1.nama_kelompok', $search)
            ->orLike('setupsupplier1.nama', $search)
            ->findAll($length, $start);
        return $result;
    }

    public function ajaxGetTotal()
    {
        $result = $this->countAll();

        if (isset($result)) {
            return $result;
        }

        return 0;
    }

    public function ajaxGetTotalSearch($search)
    {
        $result = $this->baseQuery()
            ->like('stock1.kode', $search)
            ->orLike('stock1.nama_barang', $search)
            ->orLike('group1.nama_group', $search)
            ->orLike('kelompok1.nama_kelompok', $search)
            ->orLike('setupsupplier1.nama', $search)
            ->countAllResults();

        if (isset($result)) {
            return $result;
        }

        return 0;
    }
}
