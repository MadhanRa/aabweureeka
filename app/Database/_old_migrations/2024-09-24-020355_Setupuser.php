<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setupuser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setupuser' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_setupuser' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_setupuser' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'check_setupuser' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            
        ]);
        $this->forge->addKey('id_setupuser', true);
        $this->forge->createTable('setupuser1');
    }

    public function down()
    {
        $this->forge->dropTable('setupuser1');
    }
}
