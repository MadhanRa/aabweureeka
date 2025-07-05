<?php

namespace App\Models\transaksi;

use CodeIgniter\Model;

class ModelStockOpname extends Model
{
    protected $table            = 'stockopname1';
    protected $primaryKey       = 'id_stockopname';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['id_stockopname', 'id_lokasi', 'id_user', 'tanggal', 'nota', 'id_stock', 'satuan', 'qty_1', 'qty_2', 'qty_1_sys', 'qty_2_sys', 'selisih_1', 'selisih_2'];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // // Dates
    // protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    function getAll()
    {
        // Memulai builder untuk tabel 'stockopname1' dengan alias 'p'
        $builder = $this->db->table('stockopname1 p');

        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            sp.nama_lokasi AS nama_lokasi, 
            sb.nama_user AS nama_user, 
            st.nama_barang AS nama_barang,
        ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 sp', 'p.id_lokasi = sp.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupuser1' untuk mendapatkan nama user
        $builder->join('setupuser1 sb', 'p.id_user = sb.id_user', 'left');

        $builder->join('stock1 st', 'p.id_stock = st.id_stock', 'left');

        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        // Memulai builder untuk tabel 'stockopname1' dengan alias 'p'
        $builder = $this->db->table('stockopname1 p');

        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
             p.*, 
             sp.nama_lokasi AS nama_lokasi, 
             sb.nama_user AS nama_user, 
             st.nama_barang AS nama_barang,
         ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 sp', 'p.id_lokasi = sp.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupuser1' untuk mendapatkan nama user
        $builder->join('setupuser1 sb', 'p.id_user = sb.id_user', 'left');

        $builder->join('stock1 st', 'p.id_stock = st.id_stock', 'left');

        $builder->where('MONTH(p.tanggal)', $bulan);
        $builder->where('YEAR(p.tanggal)', $tahun);
        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan hasil query sebagai array objek
        $data = $query->getResult();

        return [
            'data' => $data,           // Semua data
        ];
    }
    function getById($id)
    {
        // Memulai builder untuk tabel 'stockopname1' dengan alias 'p'
        $builder = $this->db->table('stockopname1 p');

        // Pilih kolom yang diperlukan, dengan join yang sesuai
        $builder->select('
            p.*, 
            sp.nama_lokasi,
            sb.nama_user,
            st.nama_barang,
            st.kode
        ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 sp', 'p.id_lokasi = sp.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupuser1' untuk mendapatkan nama user
        $builder->join('setupuser1 sb', 'p.id_user = sb.id_user', 'left');

        $builder->join('stock1 st', 'p.id_stock = st.id_stock', 'left');

        // Tambahkan kondisi where untuk id_stockopname
        $builder->where('p.id_stockopname', $id);

        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan satu baris sebagai objek
        return $query->getRow();
    }

    public function get_laporan($tglawal, $tglakhir, $lokasi = '', $user = '')
    {
        // Memulai builder untuk tabel 'stockopname1' dengan alias 'p'
        $builder = $this->db->table('stockopname1 p');

        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            sp.nama_lokasi, 
            sb.nama_user,
            st.nama_barang,
            st.kode,
        ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 sp', 'p.id_lokasi = sp.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupuser1' untuk mendapatkan nama user
        $builder->join('setupuser1 sb', 'p.id_user = sb.id_user', 'left');

        $builder->join('stock1 st', 'p.id_stock = st.id_stock', 'left');

        // Menambahkan kondisi tanggal
        if ($tglawal) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if ($tglakhir) {
            $builder->where('p.tanggal <=', $tglakhir);
        }

        // Menambahkan kondisi lokasi jika ada
        if ($lokasi) {
            $builder->where('p.id_lokasi', $lokasi);
        }

        // Menambahkan kondisi user jika ada
        if ($user) {
            $builder->where('p.id_user', $user);
        }

        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();
    }

    public function get_laporan_perbandingan($tanggal, $lokasi = '', $user = '')
    {
        // Memulai builder untuk tabel 'stockopname1' dengan alias 'p'
        $builder = $this->db->table('stockopname1 p');

        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            sp.nama_lokasi, 
            sb.nama_user,
            st.nama_barang,
            st.kode,
        ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi
        $builder->join('lokasi1 sp', 'p.id_lokasi = sp.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupuser1' untuk mendapatkan nama user
        $builder->join('setupuser1 sb', 'p.id_user = sb.id_user', 'left');

        $builder->join('stock1 st', 'p.id_stock = st.id_stock', 'left');

        // Menambahkan kondisi tanggal
        if ($tanggal) {
            $builder->where('p.tanggal', $tanggal);
        }

        // Menambahkan kondisi lokasi jika ada
        if ($lokasi) {
            $builder->where('p.id_lokasi', $lokasi);
        }

        // Menambahkan kondisi user jika ada
        if ($user) {
            $builder->where('p.id_user', $user);
        }

        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();
    }


    // // Validation
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // // Callbacks
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];
}
