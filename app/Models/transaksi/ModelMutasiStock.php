<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelMutasiStock extends Model
{
    protected $table            = 'mutasi_stock';
    protected $primaryKey       = 'id_mutasi';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = [
        'id_stock',
        'id_lokasi',
        'tanggal',
        'jenis',
        'qty1',
        'qty2',
        'nilai',
        'sumber_transaksi', // Pembelian, Penjualan, Retur, Penyesuaian, etc.
        'id_transaksi', // ID dari transaksi terkait
        'nota'
    ];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // // Dates
    // protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    public function get_laporan($tglawal, $tglakhir, $lokasi, $stock)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            mutasi_stock.*,
            stock.conv_factor,
            gudang.qty1 as g_qty1,
            gudang.qty2 as g_qty2,
            gudang.jml_harga');

        $builder->join('stock1_gudang gudang', 'gudang.id_stock = mutasi_stock.id_stock AND gudang.id_lokasi = mutasi_stock.id_lokasi', 'inner');
        $builder->join('stock1 stock', 'stock.id_stock = mutasi_stock.id_stock', 'inner');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('rh.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('rh.tanggal <=', $tglakhir);
        }

        // Filter lokasi
        if (!empty($lokasi)) {
            $builder->where('mutasi_stock.id_lokasi', $lokasi);
        }
        // Filter stock
        if (!empty($stock)) {
            $builder->where('mutasi_stock.id_stock', $stock);
        }

        $builder->orderBy('mutasi_stock.tanggal', 'ASC');

        return $builder->get()->getResult();
    }

    public function get_laporan_summary($tglawal, $tglakhir, $lokasi, $stock)
    {
        $builder = $this->db->table($this->table);
        $builder->select('
            mutasi_stock.id_stock,
            mutasi_stock.id_lokasi,
            mutasi_stock.jenis,
            SUM(mutasi_stock.qty1) AS qty1,
            SUM(mutasi_stock.qty2) AS qty2,
            SUM(mutasi_stock.nilai) AS nilai');

        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('mutasi_stock.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('mutasi_stock.tanggal <=', $tglakhir);
        }

        // Filter lokasi
        if (!empty($lokasi)) {
            $builder->where('mutasi_stock.id_lokasi', $lokasi);
        }
        // Filter stock
        if (!empty($stock)) {
            $builder->where('mutasi_stock.id_stock', $stock);
        }

        $builder->groupBy('mutasi_stock.id_stock, mutasi_stock.id_lokasi, mutasi_stock.jenis');

        return $builder->get()->getResult();
    }
}
