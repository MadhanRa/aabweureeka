<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Returpembelian1 extends Migration
{
    public function up()
    {
         // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_returpembelian' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'tanggal' => [
            'type' => 'DATE',
        ],
        'nota' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'id_setupsupplier' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'id_lokasi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,

        ],
        'nama_stock' => [
                'type'           => 'vARCHAR',
                'constraint'     => 100,
                
        ],
       'id_satuan' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'qty_1' => [
            'type'           => 'INT',
            'constraint'     => 100,
            'unsigned'       => true,
        ],
        'qty_2' => [
            'type'           => 'INT',
            'constraint'     => 100,
            'unsigned'       => true,
        ],
        'harga_satuan' => [
            'type'       => 'INT',
            'constraint' => 100,
            
        ],
        'jml_harga' => [
            'type'       => 'INT',
            'constraint' => 100,
            
        ],
        'disc_1' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
        ],
        'disc_2' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
        ],
        'total' => [
            'type'       => 'INT',
            'constraint' => 250,
        ],
       'id_pembelian_tgl' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'id_pembelian_nota' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'pembayaran' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
        ],
        'tipe' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
        ],
        
       
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_returpembelian', true);
    
    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_setupsupplier', 'setupsupplier1', 'id_setupsupplier', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_lokasi', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_satuan', 'satuan1', 'id_satuan', 'CASCADE', 'CASCADE');
    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_pembelian_tgl', 'pembelian1', 'id_pembelian', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_tujuan yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_pembelian_nota', 'pembelian1', 'id_pembelian', 'CASCADE', 'CASCADE');

    // Membuat tabel
    $this->forge->createTable('returpembelian1');
    }

    public function down()
    {
        $this->forge->dropTable('returpembelian1');
    }
}
