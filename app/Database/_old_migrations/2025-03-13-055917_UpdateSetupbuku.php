<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSetupbuku extends Migration
{
    public function up()
    {
        // Modify the existing table
        $this->forge->dropColumn('setupbuku1', 'pos_neraca');

        $this->forge->addColumn('setupbuku1', [
            'id_posneraca' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,

            ],
            'nama_posneraca' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'tanggal_awal_saldo' => [
                'type' => 'DATE',
            ],
        ]);

        $this->forge->addForeignKey('id_posneraca', 'pos_neraca', 'id_posneraca', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        // Revert the changes
        $this->forge->dropColumn('setupbuku1', ['id_posneraca', 'nama_posneraca', 'tanggal_awal_saldo']);

        $this->forge->addColumn('setupbuku1', [
            'pos_neraca' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
        ]);
    }
}
