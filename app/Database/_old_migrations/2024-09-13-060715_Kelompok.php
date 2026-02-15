<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kelompok extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kelompok' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_kelompok' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'nama_kelompok' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);
        $this->forge->addKey('id_kelompok', true);
        $this->forge->createTable('kelompok1');
    }

    public function down()
    {
        $this->forge->dropTable('kelompok1');
    }
}
