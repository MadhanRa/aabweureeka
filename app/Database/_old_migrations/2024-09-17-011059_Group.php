<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Group extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_group' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_group' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'nama_group' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'rekening_group' => [
                'type' => 'VARCHAR',
                'constraint' => 250, 
            ],    
        ]);
        $this->forge->addKey('id_group', true);
        $this->forge->createTable('group1');
    }
    

    public function down()
    {
        $this->forge->dropTable('group1');
    }
}
