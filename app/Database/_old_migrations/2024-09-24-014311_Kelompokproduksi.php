<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Kelompokproduksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kelproduksi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_kelproduksi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_kelproduksi' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            
        ]);
        $this->forge->addKey('id_kelproduksi', true);
        $this->forge->createTable('kelompokproduksi1');
    }

    public function down()
    {
        $this->forge->dropTable('kelompokproduksi1');
    }
}
