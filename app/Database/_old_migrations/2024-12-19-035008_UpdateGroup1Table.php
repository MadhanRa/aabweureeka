<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateGroup1Table extends Migration
{
    public function up()
    {
        // Menambahkan kolom id_interface pada tabel group1
        $this->forge->addColumn('group1', [
            'id_interface' => [
                'type'       => 'INT',
                'constraint' => 6,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'rekening_group', // Posisi kolom baru setelah rekening_group
            ],
        ]);

        // Menambahkan foreign key pada kolom id_interface yang menghubungkan ke tabel interface1
        $this->db->query('ALTER TABLE group1 ADD CONSTRAINT fk_group1_interface FOREIGN KEY (id_interface) REFERENCES interface1(id_interface) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // Menghapus foreign key dan kolom id_interface jika migrasi dibatalkan
        $this->db->query('ALTER TABLE group1 DROP FOREIGN KEY fk_group1_interface');

        // Menghapus kolom id_interface
        $this->forge->dropColumn('group1', 'id_interface');
    }
}
