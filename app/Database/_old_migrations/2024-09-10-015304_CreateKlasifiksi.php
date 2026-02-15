<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKlasifiksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_klasifikasi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_klasifikasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'nama_klasifikasi' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);
        $this->forge->addKey('id_klasifikasi', true);
        $this->forge->createTable('klasifikasi1');
    }

    public function down()
    {
        $this->forge->dropTable('klasifikasi1');
    }
}
