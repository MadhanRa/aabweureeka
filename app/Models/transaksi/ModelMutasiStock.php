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


    // Laporan Karto Stock
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

    // Laporan Daftar Stock
    public function get_laporan_daftar($tglawal, $tglakhir, $kelompok, $group)
    {
        // First get stock information
        $stockBuilder = $this->db->table('stock1 AS s');
        $stockBuilder->select('
            s.id_stock,
            s.kode,
            s.nama_barang,
            s.conv_factor,
            s.id_kelompok,
            s.id_group,
            st1.kode_satuan AS satuan,
            st2.kode_satuan AS satuan2
        ');

        $stockBuilder->join('satuan1 st1', 'st1.id_satuan = s.id_satuan', 'left');
        $stockBuilder->join('satuan1 st2', 'st2.id_satuan = s.id_satuan2', 'left');

        // Apply filters for kelompok and group
        if (!empty($kelompok)) {
            $stockBuilder->where('s.id_kelompok', $kelompok);
        }
        if (!empty($group)) {
            $stockBuilder->where('s.id_group', $group);
        }

        $stocks = $stockBuilder->get()->getResult();

        // For each stock, calculate initial, in, and out values
        foreach ($stocks as $stock) {
            // Get initial balance (before tglawal)
            $initialBuilder = $this->db->table('mutasi_stock');
            $initialBuilder->select('
            SUM(CASE WHEN jenis = "masuk" THEN qty1 ELSE -qty1 END) as initial_qty1,
            SUM(CASE WHEN jenis = "masuk" THEN qty2 ELSE -qty2 END) as initial_qty2,
            SUM(CASE WHEN jenis = "masuk" THEN nilai ELSE -nilai END) as initial_nilai
        ');
            $initialBuilder->where('id_stock', $stock->id_stock);

            if (!empty($tglawal)) {
                $initialBuilder->where('tanggal <', $tglawal);
            }

            $initial = $initialBuilder->get()->getRow();

            $stock->initial_qty1 = $initial->initial_qty1 ?? 0;
            $stock->initial_qty2 = $initial->initial_qty2 ?? 0;
            $stock->initial_nilai = $initial->initial_nilai ?? 0;

            // Get incoming values (jenis = 'masuk')
            $inBuilder = $this->db->table('mutasi_stock');
            $inBuilder->selectSum('qty1', 'in_qty1');
            $inBuilder->selectSum('qty2', 'in_qty2');
            $inBuilder->selectSum('nilai', 'in_nilai');
            $inBuilder->where('id_stock', $stock->id_stock);
            $inBuilder->where('jenis', 'masuk');

            if (!empty($tglawal)) {
                $inBuilder->where('tanggal >=', $tglawal);
            }
            if (!empty($tglakhir)) {
                $inBuilder->where('tanggal <=', $tglakhir);
            }

            $in = $inBuilder->get()->getRow();

            $stock->in_qty1 = $in->in_qty1 ?? 0;
            $stock->in_qty2 = $in->in_qty2 ?? 0;
            $stock->in_nilai = $in->in_nilai ?? 0;

            // Get outgoing values (jenis = 'keluar')
            $outBuilder = $this->db->table('mutasi_stock');
            $outBuilder->selectSum('qty1', 'out_qty1');
            $outBuilder->selectSum('qty2', 'out_qty2');
            $outBuilder->selectSum('nilai', 'out_nilai');
            $outBuilder->where('id_stock', $stock->id_stock);
            $outBuilder->where('jenis', 'keluar');

            if (!empty($tglawal)) {
                $outBuilder->where('tanggal >=', $tglawal);
            }
            if (!empty($tglakhir)) {
                $outBuilder->where('tanggal <=', $tglakhir);
            }

            $out = $outBuilder->get()->getRow();

            $stock->out_qty1 = $out->out_qty1 ?? 0;
            $stock->out_qty2 = $out->out_qty2 ?? 0;
            $stock->out_nilai = $out->out_nilai ?? 0;

            // Calculate ending balance
            $stock->ending_qty1 = $stock->initial_qty1 + $stock->in_qty1 - $stock->out_qty1;
            $stock->ending_qty2 = $stock->initial_qty2 + $stock->in_qty2 - $stock->out_qty2;
            $stock->ending_nilai = $stock->initial_nilai + $stock->in_nilai - $stock->out_nilai;

            // Calculate average price
            // normalize quantity
            if ($stock->ending_qty1 > 0 || $stock->ending_qty2 > 0) {
                $total_qty = $stock->ending_qty1 + ($stock->ending_qty2 / $stock->conv_factor);
                if ($total_qty > 0) {
                    $stock->rata_rata = $stock->ending_nilai / $total_qty;
                } else {
                    $stock->rata_rata = 0;
                }
            } else {
                $stock->rata_rata = 0;
            }
        }

        return $stocks;
    }

    public function get_laporan_daftar_summary($tglawal, $tglakhir, $kelompok, $group)
    {
        // Start with building the stock filter to apply on mutasi_stock
        $stockBuilder = $this->db->table('stock1 AS s');
        $stockBuilder->select('s.id_stock');

        // Apply filters for kelompok and group
        if (!empty($kelompok)) {
            $stockBuilder->where('s.id_kelompok', $kelompok);
        }
        if (!empty($group)) {
            $stockBuilder->where('s.id_group', $group);
        }

        $stockIds = array_map(function ($item) {
            return $item->id_stock;
        }, $stockBuilder->get()->getResult());

        // If no stocks match filters, return empty summary with zeros
        if (empty($stockIds)) {
            $summary = new \stdClass();
            $summary->initial_nilai = 0;
            $summary->in_nilai = 0;
            $summary->out_nilai = 0;
            $summary->ending_nilai = 0;
            return $summary;
        }

        // Create summary object
        $summary = new \stdClass();

        // Get initial values (before tglawal)
        $initialBuilder = $this->db->table('mutasi_stock');
        $initialBuilder->select('
            SUM(CASE WHEN jenis = "masuk" THEN nilai ELSE -nilai END) as initial_nilai
        ');
        $initialBuilder->whereIn('id_stock', $stockIds);

        if (!empty($tglawal)) {
            $initialBuilder->where('tanggal <', $tglawal);
        } else {
            // If no start date, initial is 0
            $summary->initial_nilai = 0;
        }

        if (!empty($tglawal)) {
            $initial = $initialBuilder->get()->getRow();
            $summary->initial_nilai = $initial->initial_nilai ?? 0;
        }

        // Get incoming values (jenis = 'masuk')
        $inBuilder = $this->db->table('mutasi_stock');
        $inBuilder->select('SUM(nilai) as in_nilai');
        $inBuilder->whereIn('id_stock', $stockIds);
        $inBuilder->where('jenis', 'masuk');

        if (!empty($tglawal)) {
            $inBuilder->where('tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $inBuilder->where('tanggal <=', $tglakhir);
        }

        $in = $inBuilder->get()->getRow();
        $summary->in_nilai = $in->in_nilai ?? 0;

        // Get outgoing values (jenis = 'keluar')
        $outBuilder = $this->db->table('mutasi_stock');
        $outBuilder->select('SUM(nilai) as out_nilai');
        $outBuilder->whereIn('id_stock', $stockIds);
        $outBuilder->where('jenis', 'keluar');

        if (!empty($tglawal)) {
            $outBuilder->where('tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $outBuilder->where('tanggal <=', $tglakhir);
        }

        $out = $outBuilder->get()->getRow();
        $summary->out_nilai = $out->out_nilai ?? 0;

        // Calculate ending balance
        $summary->ending_nilai = $summary->initial_nilai + $summary->in_nilai - $summary->out_nilai;

        return $summary;
    }

    public function get_laporan_daftar_total($kelompok, $group)
    {
        // First get filtered stock IDs based on kelompok and group
        $stockBuilder = $this->db->table('stock1 AS s');
        $stockBuilder->select('s.id_stock');

        // Apply filters for kelompok and group
        if (!empty($kelompok)) {
            $stockBuilder->where('s.id_kelompok', $kelompok);
        }
        if (!empty($group)) {
            $stockBuilder->where('s.id_group', $group);
        }

        $stockIds = array_map(function ($item) {
            return $item->id_stock;
        }, $stockBuilder->get()->getResult());

        // If no stocks match filters, return zeros
        if (empty($stockIds)) {
            $totals = new \stdClass();
            $totals->total_awal = 0;
            $totals->total_masuk = 0;
            $totals->total_keluar = 0;
            $totals->total_akhir = 0;
            return $totals;
        }

        // Calculate in and out values for filtered stock IDs
        $builder = $this->db->table('mutasi_stock');
        $builder->select('
            SUM(CASE WHEN jenis = "masuk" THEN nilai ELSE 0 END) as total_masuk,
            SUM(CASE WHEN jenis = "keluar" THEN nilai ELSE 0 END) as total_keluar
        ');
        $builder->whereIn('id_stock', $stockIds);

        $result = $builder->get()->getRow();

        // Calculate initial and final values
        $initial = 0; // Initial value is always 0 when not considering date filters
        $masuk = $result->total_masuk ?? 0;
        $keluar = $result->total_keluar ?? 0;
        $akhir = $masuk - $keluar; // Final value is in - out

        // Create a structured result object
        $totals = new \stdClass();
        $totals->total_awal = $initial;
        $totals->total_masuk = $masuk;
        $totals->total_keluar = $keluar;
        $totals->total_akhir = $akhir;

        return $totals;
    }

    // Laporan Daftar Stock QTY
    public function get_laporan_daftar_q($tglawal, $tglakhir, $kelompok, $group, $lokasi, $supplier)
    {
        // First get stock information
        $stockBuilder = $this->db->table('stock1 AS s');
        $stockBuilder->select('
        s.id_stock,
        s.kode,
        s.nama_barang,
        s.conv_factor,
        s.id_kelompok,
        s.id_group,
        s.id_setupsupplier,
        st1.kode_satuan AS satuan,
        st2.kode_satuan AS satuan2
    ');

        $stockBuilder->join('satuan1 st1', 'st1.id_satuan = s.id_satuan', 'left');
        $stockBuilder->join('satuan1 st2', 'st2.id_satuan = s.id_satuan2', 'left');

        // Apply filters for kelompok, group, and supplier directly to stock query
        if (!empty($kelompok)) {
            $stockBuilder->where('s.id_kelompok', $kelompok);
        }
        if (!empty($group)) {
            $stockBuilder->where('s.id_group', $group);
        }
        if (!empty($supplier)) {
            $stockBuilder->where('s.id_setupsupplier', $supplier);
        }

        // If location is specified, use a sub-query to filter stocks that exist in that location
        if (!empty($lokasi)) {
            $stockBuilder->whereIn('s.id_stock', function ($subquery) use ($lokasi) {
                $subquery->select('id_stock')
                    ->from('stock1_gudang')
                    ->where('id_lokasi', $lokasi);
            });
        }

        $stocks = $stockBuilder->get()->getResult();

        // For each stock, calculate initial, in, and out values
        foreach ($stocks as $stock) {
            // Store the location for reference in mutasi filters
            $stock->id_lokasi = $lokasi; // This will be null if no location filter was applied

            // Get initial balance (before tglawal)
            $initialBuilder = $this->db->table('mutasi_stock');
            $initialBuilder->select('
            SUM(CASE WHEN jenis = "masuk" THEN qty1 ELSE -qty1 END) as initial_qty1,
            SUM(CASE WHEN jenis = "masuk" THEN qty2 ELSE -qty2 END) as initial_qty2
        ');
            $initialBuilder->where('id_stock', $stock->id_stock);

            // Apply location filter to mutasi_stock
            if (!empty($lokasi)) {
                $initialBuilder->where('id_lokasi', $lokasi);
            }

            if (!empty($tglawal)) {
                $initialBuilder->where('tanggal <', $tglawal);
            }

            $initial = $initialBuilder->get()->getRow();

            $stock->initial_qty1 = $initial->initial_qty1 ?? 0;
            $stock->initial_qty2 = $initial->initial_qty2 ?? 0;

            // Get incoming values (jenis = 'masuk')
            $inBuilder = $this->db->table('mutasi_stock');
            $inBuilder->selectSum('qty1', 'in_qty1');
            $inBuilder->selectSum('qty2', 'in_qty2');
            $inBuilder->where('id_stock', $stock->id_stock);
            $inBuilder->where('jenis', 'masuk');

            // Apply location filter to mutasi_stock
            if (!empty($lokasi)) {
                $inBuilder->where('id_lokasi', $lokasi);
            }

            if (!empty($tglawal)) {
                $inBuilder->where('tanggal >=', $tglawal);
            }
            if (!empty($tglakhir)) {
                $inBuilder->where('tanggal <=', $tglakhir);
            }

            $in = $inBuilder->get()->getRow();

            $stock->in_qty1 = $in->in_qty1 ?? 0;
            $stock->in_qty2 = $in->in_qty2 ?? 0;

            // Get outgoing values (jenis = 'keluar')
            $outBuilder = $this->db->table('mutasi_stock');
            $outBuilder->selectSum('qty1', 'out_qty1');
            $outBuilder->selectSum('qty2', 'out_qty2');
            $outBuilder->where('id_stock', $stock->id_stock);
            $outBuilder->where('jenis', 'keluar');

            // Apply location filter to mutasi_stock
            if (!empty($lokasi)) {
                $outBuilder->where('id_lokasi', $lokasi);
            }

            if (!empty($tglawal)) {
                $outBuilder->where('tanggal >=', $tglawal);
            }
            if (!empty($tglakhir)) {
                $outBuilder->where('tanggal <=', $tglakhir);
            }

            $out = $outBuilder->get()->getRow();

            $stock->out_qty1 = $out->out_qty1 ?? 0;
            $stock->out_qty2 = $out->out_qty2 ?? 0;

            // Calculate ending balance for quantities
            $stock->ending_qty1 = $stock->initial_qty1 + $stock->in_qty1 - $stock->out_qty1;
            $stock->ending_qty2 = $stock->initial_qty2 + $stock->in_qty2 - $stock->out_qty2;

            // Get the nilai values only for ending calculation
            $nilaiBuilder = $this->db->table('mutasi_stock');
            $nilaiBuilder->select('
            SUM(CASE WHEN jenis = "masuk" THEN nilai ELSE -nilai END) as ending_nilai
        ');
            $nilaiBuilder->where('id_stock', $stock->id_stock);

            // Apply location filter to mutasi_stock
            if (!empty($lokasi)) {
                $nilaiBuilder->where('id_lokasi', $lokasi);
            }

            if (!empty($tglawal)) {
                // Include all transactions up to tglakhir
                if (!empty($tglakhir)) {
                    $nilaiBuilder->where('tanggal <=', $tglakhir);
                }
            }

            $nilai = $nilaiBuilder->get()->getRow();
            $stock->ending_nilai = $nilai->ending_nilai ?? 0;

            // Calculate average price
            // normalize quantity
            if ($stock->ending_qty1 > 0 || $stock->ending_qty2 > 0) {
                $total_qty = $stock->ending_qty1 + ($stock->ending_qty2 / $stock->conv_factor);
                if ($total_qty > 0) {
                    $stock->rata_rata = $stock->ending_nilai / $total_qty;
                } else {
                    $stock->rata_rata = 0;
                }
            } else {
                $stock->rata_rata = 0;
            }
        }

        return $stocks;
    }

    public function get_laporan_daftar_summary_q($tglawal, $tglakhir, $kelompok, $group, $lokasi, $supplier)
    {
        // Start with building the stock filter to apply on mutasi_stock
        $stockBuilder = $this->db->table('stock1 AS s');
        $stockBuilder->select('s.id_stock');
        $stockBuilder->join('stock1_gudang g', 'g.id_stock = s.id_stock', 'left');

        // Apply filters for kelompok and group
        if (!empty($kelompok)) {
            $stockBuilder->where('s.id_kelompok', $kelompok);
        }
        if (!empty($group)) {
            $stockBuilder->where('s.id_group', $group);
        }
        if (!empty($supplier)) {
            $stockBuilder->where('s.id_setupsupplier', $supplier);
        }
        if (!empty($lokasi)) {
            $stockBuilder->where('g.id_lokasi', $lokasi);
        }

        $stockIds = array_map(function ($item) {
            return $item->id_stock;
        }, $stockBuilder->get()->getResult());

        // If no stocks match filters, return empty summary with zeros
        if (empty($stockIds)) {
            $summary = new \stdClass();
            $summary->initial_nilai = 0;
            $summary->in_nilai = 0;
            $summary->out_nilai = 0;
            $summary->ending_nilai = 0;
            return $summary;
        }

        // Create summary object
        $summary = new \stdClass();

        // Get initial values (before tglawal)
        $initialBuilder = $this->db->table('mutasi_stock');
        $initialBuilder->select('
            SUM(CASE WHEN jenis = "masuk" THEN nilai ELSE -nilai END) as initial_nilai
        ');
        $initialBuilder->whereIn('id_stock', $stockIds);

        if (!empty($tglawal)) {
            $initialBuilder->where('tanggal <', $tglawal);
        } else {
            // If no start date, initial is 0
            $summary->initial_nilai = 0;
        }

        if (!empty($tglawal)) {
            $initial = $initialBuilder->get()->getRow();
            $summary->initial_nilai = $initial->initial_nilai ?? 0;
        }

        // Get incoming values (jenis = 'masuk')
        $inBuilder = $this->db->table('mutasi_stock');
        $inBuilder->select('SUM(nilai) as in_nilai');
        $inBuilder->whereIn('id_stock', $stockIds);
        $inBuilder->where('jenis', 'masuk');

        if (!empty($tglawal)) {
            $inBuilder->where('tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $inBuilder->where('tanggal <=', $tglakhir);
        }

        $in = $inBuilder->get()->getRow();
        $summary->in_nilai = $in->in_nilai ?? 0;

        // Get outgoing values (jenis = 'keluar')
        $outBuilder = $this->db->table('mutasi_stock');
        $outBuilder->select('SUM(nilai) as out_nilai');
        $outBuilder->whereIn('id_stock', $stockIds);
        $outBuilder->where('jenis', 'keluar');

        if (!empty($tglawal)) {
            $outBuilder->where('tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $outBuilder->where('tanggal <=', $tglakhir);
        }

        $out = $outBuilder->get()->getRow();
        $summary->out_nilai = $out->out_nilai ?? 0;

        // Calculate ending balance
        $summary->ending_nilai = $summary->initial_nilai + $summary->in_nilai - $summary->out_nilai;

        return $summary;
    }

    public function get_laporan_daftar_total_q($kelompok, $group, $lokasi, $supplier)
    {
        // First get filtered stock IDs
        $stockBuilder = $this->db->table('stock1 AS s');
        $stockBuilder->select('s.id_stock');
        $stockBuilder->join('stock1_gudang g', 'g.id_stock = s.id_stock', 'left');

        // Apply filters for kelompok, group and supplier (from stock1 table)
        if (!empty($kelompok)) {
            $stockBuilder->where('s.id_kelompok', $kelompok);
        }
        if (!empty($group)) {
            $stockBuilder->where('s.id_group', $group);
        }
        if (!empty($supplier)) {
            $stockBuilder->where('s.id_setupsupplier', $supplier);
        }
        if (!empty($lokasi)) {
            $stockBuilder->where('g.id_lokasi', $lokasi);
        }

        $stockIds = array_map(function ($item) {
            return $item->id_stock;
        }, $stockBuilder->get()->getResult());

        // If no stocks match filters, return zeros
        if (empty($stockIds)) {
            $totals = new \stdClass();
            $totals->total_awal = 0;
            $totals->total_masuk = 0;
            $totals->total_keluar = 0;
            $totals->total_akhir = 0;
            return $totals;
        }

        // Calculate in and out values for filtered stock IDs
        $builder = $this->db->table('mutasi_stock');
        $builder->select('
        SUM(CASE WHEN jenis = "masuk" THEN nilai ELSE 0 END) as total_masuk,
        SUM(CASE WHEN jenis = "keluar" THEN nilai ELSE 0 END) as total_keluar
    ');
        $builder->whereIn('id_stock', $stockIds);

        // Apply lokasi filter to mutasi_stock table if specified
        if (!empty($lokasi)) {
            $builder->where('id_lokasi', $lokasi);
        }

        $result = $builder->get()->getRow();

        // Calculate initial and final values
        $initial = 0; // Initial value is always 0 when not considering date filters
        $masuk = $result->total_masuk ?? 0;
        $keluar = $result->total_keluar ?? 0;
        $akhir = $masuk - $keluar; // Final value is in - out

        // Create a structured result object
        $totals = new \stdClass();
        $totals->total_awal = $initial;
        $totals->total_masuk = $masuk;
        $totals->total_keluar = $keluar;
        $totals->total_akhir = $akhir;

        return $totals;
    }
}
