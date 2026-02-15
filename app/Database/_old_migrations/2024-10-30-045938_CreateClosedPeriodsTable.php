<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClosedPeriodsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'periode_start'     => [
                'type'       => 'DATE',
            ],
            'periode_end'     => [
                'type'       => 'DATE',
            ],
            'is_closed'   => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at'  => [
                'type' => 'TIMESTAMP',
            ],
        ]);

        // Set primary key
        $this->forge->addKey('id', true);

        // Membuat tabel
        $this->forge->createTable('closed_periods');
    }

    public function down()
    {
        // Drop tabel jika rollback
        $this->forge->dropTable('closed_periods');
    }
}
