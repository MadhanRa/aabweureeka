<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setupbuku extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setupbuku' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_setupbuku' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'nama_setupbuku' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'pos_neraca' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'saldo_setupbuku' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
           'created_at' => [
                'type' => 'DATE',
            ]    
            
        ]);
        $this->forge->addKey('id_setupbuku', true);
        $this->forge->createTable('setupbuku1');
    }

    public function down()
    {
        $this->forge->dropTable('setupbuku1');
    }
}
