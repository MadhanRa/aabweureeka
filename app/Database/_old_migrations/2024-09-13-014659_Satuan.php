<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Satuan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_satuan' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'nama_satuan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);
        $this->forge->addKey('id_satuan', true);
        $this->forge->createTable('satuan1');
    }

    public function down()
    {
        $this->forge->dropTable('satuan1');
    }
}
