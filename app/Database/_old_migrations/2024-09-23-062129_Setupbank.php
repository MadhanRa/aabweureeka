<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setupbank extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setupbank' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_setupbank' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'nama_setupbank' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'rekening_setupbank' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            
        ]);
        $this->forge->addKey('id_setupbank', true);
        $this->forge->createTable('setupbank1');
    }

    public function down()
    {
        $this->forge->dropTable('setupbank1');
    }
}
