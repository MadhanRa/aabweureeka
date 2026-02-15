<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStock1Table extends Migration
{
    public function up()
    {
        $this->forge->addColumn('stock1', [
            'id_group' => [
                'type'       => 'INT',
                'constraint' => 6,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_lokasi', // Posisi kolom baru
            ],
            'id_kelompok' => [
                'type'       => 'INT',
                'constraint' => 6,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_group',
            ],
            'id_setupsupplier' => [
                'type'       => 'INT',
                'constraint' => 6,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_kelompok',
            ],
        ]);

        // Tambahkan foreign key
        $this->db->query('ALTER TABLE stock1 ADD CONSTRAINT fk_stock1_group FOREIGN KEY (id_group) REFERENCES group1(id_group)');
        $this->db->query('ALTER TABLE stock1 ADD CONSTRAINT fk_stock1_kelompok FOREIGN KEY (id_kelompok) REFERENCES kelompok1(id_kelompok)');
        $this->db->query('ALTER TABLE stock1 ADD CONSTRAINT fk_stock1_supplier FOREIGN KEY (id_setupsupplier) REFERENCES setupsupplier1(id_setupsupplier)');
    }

    public function down()
    {
        // Hapus foreign key
        $this->db->query('ALTER TABLE stock1 DROP FOREIGN KEY fk_stock1_group');
        $this->db->query('ALTER TABLE stock1 DROP FOREIGN KEY fk_stock1_kelompok');
        $this->db->query('ALTER TABLE stock1 DROP FOREIGN KEY fk_stock1_supplier');

        // Hapus kolom
        $this->forge->dropColumn('stock1', ['id_group', 'id_kelompok', 'id_setupsupplier']);
    }
}
