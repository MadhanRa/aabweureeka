<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSetupbank1Table extends Migration
{
    public function up()
    {
        $this->forge->addColumn('setupbank1', [
            'id_interface' => [
                'type'       => 'INT',
                'constraint' => 6,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'nama_setupbank', // Posisi kolom baru setelah rekening_group
            ],
        ]);

        // Menambahkan foreign key pada kolom id_interface yang menghubungkan ke tabel interface1
        $this->db->query('ALTER TABLE setupbank1 ADD CONSTRAINT fk_setupbank1_interface FOREIGN KEY (id_interface) REFERENCES interface1(id_interface) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // Menghapus foreign key dan kolom id_interface jika migrasi dibatalkan
        $this->db->query('ALTER TABLE setupbank1 DROP FOREIGN KEY fk_setupbank1_interface');

        // Menghapus kolom id_interface
        $this->forge->dropColumn('setupbank1', 'id_interface');
    }
}
