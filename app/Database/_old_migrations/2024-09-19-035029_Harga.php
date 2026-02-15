<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Harga extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_harga' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_harga' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'nama_barang' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'harga_jualexc' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'harga_jualinc' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'harga_beli' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('id_harga', true);
        $this->forge->createTable('harga1');
    }

    public function down()
    {
        $this->forge->dropTable('harga1');
    }
}
