<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setupbiaya extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setupbiaya' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_setupbiaya' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'nama_setupbiaya' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'rekening_setupbiaya' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            
        ]);
        $this->forge->addKey('id_setupbiaya', true);
        $this->forge->createTable('setupbiaya1');
    }

    public function down()
    {
        $this->forge->dropTable('setupbiaya1');
    }
}
