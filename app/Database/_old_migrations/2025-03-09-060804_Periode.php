<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Periode extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['buka', 'tutup'],
                'default' => 'buka',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('periode1', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('periode1', 'status');
        $this->forge->dropColumn('periode1', 'created_at');
        $this->forge->dropColumn('periode1', 'updated_at');
    }
}
