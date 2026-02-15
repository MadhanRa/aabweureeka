<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SetupLokasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_lokasi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'nama_lokasi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);
        $this->forge->addKey('id_lokasi', true);
        $this->forge->createTable('lokasi1');
    }

    public function down()
    {
        $this->forge->dropTable('lokasi1');
    }
}
