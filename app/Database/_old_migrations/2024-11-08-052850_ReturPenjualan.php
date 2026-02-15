<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ReturPenjualan extends Migration
{
    public function up()
    {
        // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_returpenjualan' => [
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
        'id_pelanggan' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'id_setupsalesman' => [
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
       'id_penjualan_tgl' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'id_penjualan_nota' => [
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
    $this->forge->addKey('id_returpenjualan', true);
    
    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_setupsalesman', 'setupsalesman1', 'id_setupsalesman', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_lokasi', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_satuan', 'satuan1', 'id_satuan', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_pelanggan', 'setuppelanggan1', 'id_pelanggan', 'CASCADE', 'CASCADE');
    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_penjualan_tgl', 'penjualan1', 'id_penjualan', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_tujuan yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_penjualan_nota', 'penjualan1', 'id_penjualan', 'CASCADE', 'CASCADE');

    // Membuat tabel
    $this->forge->createTable('returpenjualan1');
    }

    public function down()
    {
        $this->forge->dropTable('returpenjualan1');
    }
}
