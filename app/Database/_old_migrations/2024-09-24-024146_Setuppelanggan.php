<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setuppelanggan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pelanggan' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_pelanggan' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'nama_pelanggan' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'alamat_pelanggan' => [
                'type' => 'VARCHAR',
                'constraint' => 300,
            ],
            'kota_pelanggan' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],  
            'telp_pelanggan' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            'plafond' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            'npwp' => [
                'type' => 'VARCHAR',
                'constraint' => 300,
            ], 
            'class_pelanggan' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'tipe' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            'tanggal' => [
                'type' => 'DATE',
            ], 
            'saldo' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            
        ]);
        $this->forge->addKey('id_pelanggan', true);
        $this->forge->createTable('setuppelanggan1');
    }

    public function down()
    {
        $this->forge->dropTable('setuppelanggan1');
    }
}
