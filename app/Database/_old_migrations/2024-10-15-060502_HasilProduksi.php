<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HasilProduksi extends Migration
{
    public function up()
    {
          // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_produksi' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'nota_produksi' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'id_lokasi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'id_kelproduksi' => [
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
        'harga_satuan' => [
            'type'       => 'INT',
            'constraint' => 100,
            'unsigned'   => true,
        ],
        'jml_harga' => [
            'type'       => 'INT',
            'constraint' => 100,
            'unsigned'   => true,
        ],
        'tanggal' => [
            'type' => 'DATE',
        ],
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_produksi', true);

    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_lokasi', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_tujuan yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_kelproduksi', 'kelompokproduksi1', 'id_kelproduksi', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk satuan yang merujuk ke satuan1.kode_satuan
    $this->forge->addForeignKey('id_satuan', 'satuan1', 'id_satuan', 'CASCADE', 'CASCADE');

    // Membuat tabel
    $this->forge->createTable('hasilproduksi1');
    }
    

    public function down()
    {
        $this->forge->dropTable('hasilproduksi1');
    }
}
