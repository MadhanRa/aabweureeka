<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HasilSablon extends Migration
{
    public function up()
    {
         // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_sablon' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'nota_sablon' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'bahan_sablon' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'id_lokasi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'id_setupsupplier' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'terpakai' => [
            'type'       => 'INT',
            'constraint' => 100,
            'unsigned'   => true,
        ],
        'rusak' => [
            'type'       => 'INT',
            'constraint' => 100,
            'unsigned'   => true,
        ],
        'nama_stock' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'id_satuan' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'qty_1' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'qty_2' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'harga' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'tanggal' => [
            'type' => 'DATE',
        ],
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_sablon', true);

    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_lokasi', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_tujuan yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_setupsupplier', 'setupsupplier1', 'id_setupsupplier', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk satuan yang merujuk ke satuan1.kode_satuan
    $this->forge->addForeignKey('id_satuan', 'satuan1', 'id_satuan', 'CASCADE', 'CASCADE');

    // Membuat tabel
    $this->forge->createTable('hasilsablon1');
    }

    public function down()
    {
        $this->forge->dropTable('hasilsablon1');
    }
}
