<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Periode extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_periode' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'periode_bulan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'periode_tahun' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

        ]);
        $this->forge->addKey('id_periode', true);
        $this->forge->createTable('periode1');
    }

    public function down()
    {
        $this->forge->dropTable('periode1');
    }
}
