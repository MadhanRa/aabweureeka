<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setuppiutang extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setuppiutang' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_piutang' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'nama_piutang' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'lokasi_piutang' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            'tanggal_piutang' => [
                'type' => 'DATE',
            ], 
            'saldo_piutang' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            
        ]);
        $this->forge->addKey('id_setuppiutang', true);
        $this->forge->createTable('setuppiutang1');
    }

    public function down()
    {
        $this->forge->dropTable('setuppiutang1');
    }
}
