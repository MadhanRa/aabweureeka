<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPemakaianBahan extends Model
{
    protected $table            = 'bahan1';
    protected $primaryKey       = 'id_bahan';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['nota_bahan', 'id_lokasi', 'id_kelproduksi', 'id_setupbank', 'nama_stock', 'id_satuan', 'qty_1', 'qty_2'];

    // protected bool $allowEmptyInserts = false;
    // protected bool $updateOnlyChanged = true;

    // protected array $casts = [];
    // protected array $castHandlers = [];

    // // Dates
    // protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    function getAll()
    {
        // Memulai builder untuk tabel 'hasilsablon1' dengan alias 'p'
        $builder = $this->db->table('bahan1 p');

        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama_kelproduksi, 
            s.kode_satuan,
            sb.nama_setupbank
        ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi asal
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupsupplier1' untuk mendapatkan nama supplier
        $builder->join('kelompokproduksi1 sp', 'p.id_kelproduksi = sp.id_kelproduksi', 'left');

        // Melakukan JOIN dengan tabel 'satuan1' untuk mendapatkan kode satuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');

        // Jika Anda ingin menambahkan informasi dari 'setupbank1', uncomment dan sesuaikan baris berikut:
        $builder->join('setupbank1 sb', 'p.id_setupbank = sb.id_setupbank', 'left');


        // Eksekusi query
        $query = $builder->get();

        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();

        return $this->findAll();
    }
    public function getByMonthAndYear($bulan, $tahun)
    {
        $builder = $this->db->table('bahan1 p');
        $builder->select('
            p.*, 
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama_kelproduksi, 
            s.kode_satuan,
            sb.nama_setupbank
        ');
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');
        $builder->join('kelompokproduksi1 sp', 'p.id_kelproduksi = sp.id_kelproduksi', 'left');
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');
        $builder->join('setupbank1 sb', 'p.id_setupbank = sb.id_setupbank', 'left');
        $builder->where('MONTH(p.tanggal)', $bulan);
        $builder->where('YEAR(p.tanggal)', $tahun);
        $query = $builder->get();
        $data = $query->getResult();

        return [
            'data' => $data,           // Semua data
        ];
    }

    public function get_laporan($tglawal, $tglakhir = null)
    {
            $builder = $this->db->table('bahan1 p');

            $builder->select('
            p.*, 
            l1.nama_lokasi AS lokasi_asal, 
            sp.nama_kelproduksi, 
            s.kode_satuan,
            sb.nama_setupbank
        ');

        // Melakukan JOIN dengan tabel 'lokasi1' untuk mendapatkan nama lokasi asal
        $builder->join('lokasi1 l1', 'p.id_lokasi = l1.id_lokasi', 'left');

        // Melakukan JOIN dengan tabel 'setupsupplier1' untuk mendapatkan nama supplier
        $builder->join('kelompokproduksi1 sp', 'p.id_kelproduksi = sp.id_kelproduksi', 'left');

        // Melakukan JOIN dengan tabel 'satuan1' untuk mendapatkan kode satuan
        $builder->join('satuan1 s', 'p.id_satuan = s.id_satuan', 'left');

        // Jika Anda ingin menambahkan informasi dari 'setupbank1', uncomment dan sesuaikan baris berikut:
        $builder->join('setupbank1 sb', 'p.id_setupbank = sb.id_setupbank', 'left');



        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }


        return $builder->get()->getResult();
    }
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

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
