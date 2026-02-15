<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateKelompokproduksi1Table extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kelompokproduksi1', [
            'id_interface' => [
                'type'       => 'INT',
                'constraint' => 6,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'nama_kelproduksi', // Posisi kolom baru setelah rekening_group
            ],
        ]);

        // Menambahkan foreign key pada kolom id_interface yang menghubungkan ke tabel interface1
        $this->db->query('ALTER TABLE kelompokproduksi1 ADD CONSTRAINT fk_kelompokproduksi1_interface FOREIGN KEY (id_interface) REFERENCES interface1(id_interface) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
         // Menghapus foreign key dan kolom id_interface jika migrasi dibatalkan
         $this->db->query('ALTER TABLE kelompokproduksi1 DROP FOREIGN KEY fk_kelompokproduksi1_interface');

         // Menghapus kolom id_interface
         $this->forge->dropColumn('kelompokproduksi1', 'id_interface');
    }
}
