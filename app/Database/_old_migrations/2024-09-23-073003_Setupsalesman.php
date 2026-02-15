<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Setupsalesman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_setupsalesman' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_setupsalesman' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'nama_setupsalesman' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'lokasi_setupsalesman' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ], 
            'tanggal_setupsalesman' => [
                'type' => 'DATE',
            ], 
            'saldo_setupsalesman' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            
        ]);
        $this->forge->addKey('id_setupsalesman', true);
        $this->forge->createTable('setupsalesman1');
    }

    public function down()
    {
        $this->forge->dropTable('setupsalesman1');
    }
}
