<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BahanSablon extends Migration
{
    public function up()
    {
        // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_bahan' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'nota_pindah' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'id_lokasi_asal' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'id_lokasi_tujuan' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
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
        'tanggal' => [
            'type' => 'DATE',
        ],
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_bahan', true);

    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_lokasi_asal', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_tujuan yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_lokasi_tujuan', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk satuan yang merujuk ke satuan1.kode_satuan
    $this->forge->addForeignKey('id_satuan', 'satuan1', 'id_satuan', 'CASCADE', 'CASCADE');

    // Membuat tabel
    $this->forge->createTable('bahansablon1');
    
    }

    public function down()
    {
        $this->forge->dropTable('bahansablon1');
    }
}
