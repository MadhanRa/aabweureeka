<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeederStock extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_group' => '02',
                'id_kelompok' => '02',
                'id_setupsupplier' => '02',
                'kode' => '02',
                'nama_barang' => '02',
                'min_stock' => '02',
                'id_satuan' => '02',
                'id_satuan2' => '02',
                'conv_factor' => '02',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('stock1')->insertBatch($data);
    }
}
