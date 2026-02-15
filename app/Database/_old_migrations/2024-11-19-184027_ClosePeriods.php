<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClosePeriods extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'period_month' => [
                'type'       => 'INT',
                'constraint' => 2,
                'null'       => false,
            ],
            'period_year' => [
                'type'       => 'INT',
                'constraint' => 4,
                'null'       => false,
            ],
            'is_closed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        // Menambahkan primary key
        $this->forge->addKey('id', true);

        // Menambahkan unique key untuk kombinasi `period_month` dan `period_year`
        $this->forge->addUniqueKey(['period_month', 'period_year']);

        // Membuat tabel
        $this->forge->createTable('close_periods');
    }

    public function down()
    {
        // Menghapus tabel jika migrasi dibatalkan
        $this->forge->dropTable('close_periods');
    }
}
