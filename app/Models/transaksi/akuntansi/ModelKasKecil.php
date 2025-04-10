<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelKasKecil extends Model
{
    protected $table            = 'kaskecil1';
    protected $primaryKey       = 'id_kaskecil';
    // protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = false;
    // protected $protectFields    = true;
    protected $allowedFields    = ['tanggal', 'nota', 'id_interface', 'id_kelproduksi', 'rekening', 'b_pembantu', 'nama_rekening', 'nama_bpembantu', 'rp', 'keterangan'];

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
        // Memulai builder untuk tabel 'tutangusaha1' dengan alias 'p'
        $builder = $this->db->table('kaskecil1 p');
        
        // Memilih kolom yang diperlukan dengan alias yang sesuai
        $builder->select('
            p.*, 
            sp.kas_interface AS kas_interface, 
            sb.nama_kelproduksi AS nama_kelproduksi
        ');
        
        // Melakukan JOIN dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
        $builder->join('interface1 sp', 'p.id_interface = sp.id_interface', 'left');
        
        // Melakukan JOIN dengan tabel 'setupbank1' untuk mendapatkan nama bank
        $builder->join('kelompokproduksi1 sb', 'p.id_kelproduksi = sb.id_kelproduksi', 'left');
        
        // Eksekusi query
        $query = $builder->get();
        
        // Mengembalikan hasil query sebagai array objek
        return $query->getResult();

        return $this->findAll();
    }

    public function getByMonthAndYear($bulan, $tahun)
    {
        $builder = $this->db->table('kaskecil1 p');
        $builder->select('
            p.*, 
            sp.kas_interface AS kas_interface, 
            sb.nama_kelproduksi AS nama_kelproduksi
        ');
        $builder->join('interface1 sp', 'p.id_interface = sp.id_interface', 'left');
        $builder->join('kelompokproduksi1 sb', 'p.id_kelproduksi = sb.id_kelproduksi', 'left');
        $builder->where('MONTH(p.tanggal)', $bulan);
        $builder->where('YEAR(p.tanggal)', $tahun);
        $query = $builder->get();
        $data = $query->getResult();


        $grandtotal =  $this->selectSum('rp')
        ->where('MONTH(tanggal)', $bulan)
        ->where('YEAR(tanggal)', $tahun)
        ->get()
        ->getRow()
        ->grand_total ?? 0;

            return [
                'data' => $data,           // Semua data
                'grandtotal' => $grandtotal, // Total nilai grand_total
            ];
    }

    function getById($id)
    {
        // Memulai builder untuk tabel 'tutangusaha1' dengan alias 'p'
        $builder = $this->db->table('kaskecil1 p');
        
        // Pilih kolom yang diperlukan, dengan join yang sesuai
        $builder->select('p.*, sp.kas_interface AS kas_interface, sb.nama_kelproduksi AS nama_kelproduksi');
        
        // Melakukan JOIN dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
        $builder->join('interface1 sp', 'p.id_interface = sp.id_interface', 'left');
        
        // Melakukan JOIN dengan tabel 'setupbank1' untuk mendapatkan nama bank
        $builder->join('kelompokproduksi1 sb', 'p.id_kelproduksi = sb.id_kelproduksi', 'left');
        
        // Tambahkan kondisi where untuk id_lunashusaha
        $builder->where('p.id_kaskecil', $id);
        
        // Eksekusi query
        $query = $builder->get();
        
        // Mengembalikan satu baris sebagai objek
        return $query->getRow();
    }

    public function get_laporan($tglawal, $tglakhir, $rekeningkas, $kelproduksi = null)
    {
            $builder = $this->db->table('kaskecil1 p');

            $builder->select('
            p.*, 
            sp.kas_interface AS kas_interface, 
            sb.nama_kelproduksi AS nama_kelproduksi
        ');
        
        // Melakukan JOIN dengan tabel 'setuppelanggan1' untuk mendapatkan nama pelanggan
        $builder->join('interface1 sp', 'p.id_interface = sp.id_interface', 'left');
        
        // Melakukan JOIN dengan tabel 'setupbank1' untuk mendapatkan nama bank
        $builder->join('kelompokproduksi1 sb', 'p.id_kelproduksi = sb.id_kelproduksi', 'left');


        // Filter tanggal
        if (!empty($tglawal)) {
            $builder->where('p.tanggal >=', $tglawal);
        }
        if (!empty($tglakhir)) {
            $builder->where('p.tanggal <=', $tglakhir);
        }
        // Filter supplier (jika ada)
        if (!empty($rekeningkas)) {
            $builder->where('p.id_interface', $rekeningkas);
        }
        // Filter Kelompok Produksi (jika ada)
        if (!empty($kelproduksi)) {
            $builder->where('p.id_kelproduksi', $kelproduksi);
        }


        return $builder->get()->getResult();
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
