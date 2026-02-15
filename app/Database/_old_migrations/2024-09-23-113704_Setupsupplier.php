<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setupsupplier extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setupsupplier' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'alamat' => [
                'type' => 'VARCHAR',
                'constraint' => 300,
            ], 
            'telepon' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            'contact_person' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            'npwp' => [
                'type' => 'VARCHAR',
                'constraint' => 300,
            ], 
            'tanggal' => [
                'type' => 'DATE',
            ], 
            'saldo' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            'tipe' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
        ]);
        $this->forge->addKey('id_setupsupplier', true);
        $this->forge->createTable('setupsupplier1');
    }

    public function down()
    {
        $this->forge->dropTable('setupsupplier1');
    }
}
