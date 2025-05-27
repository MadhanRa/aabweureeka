<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeederSetupbuku extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_setupbuku' => '101.001',
                'nama_setupbuku'    => 'BANK BCA',
                'saldo_awal'    => '100000000',
                'saldo_berjalan'    => '100000000',
                'id_posneraca' => '10',
                'tanggal_awal_saldo' => '2025-04-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'kode_setupbuku' => '101.002',
                'nama_setupbuku'    => 'BANK MANDIRI',
                'saldo_awal'    => '150000000',
                'saldo_berjalan'    => '150000000',
                'id_posneraca' => '10',
                'tanggal_awal_saldo' => '2025-04-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode_setupbuku' => '100.001',
                'nama_setupbuku'    => 'KAS BESAR UMUM',
                'saldo_awal'    => '200000000',
                'saldo_berjalan'    => '200000000',
                'id_posneraca' => '9',
                'tanggal_awal_saldo' => '2025-04-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode_setupbuku' => '100.002.01',
                'nama_setupbuku'    => 'KAS KECIL - NITA BMM',
                'saldo_awal'    => '150000000',
                'saldo_berjalan'    => '150000000',
                'id_posneraca' => '9',
                'tanggal_awal_saldo' => '2025-05-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode_setupbuku' => '100.002.02',
                'nama_setupbuku'    => 'KAS KECIL - NITA EGN',
                'saldo_awal'    => '180000000',
                'saldo_berjalan'    => '180000000',
                'id_posneraca' => '9',
                'tanggal_awal_saldo' => '2025-05-01',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('setupbuku1')->insertBatch($data);
    }
}
