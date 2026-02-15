<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StockOpname extends Migration
{
    public function up()
    {
        // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_stockopname' => [
            'type'           => 'INT',
            'constraint'     => 11,
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
        'id_lokasi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'id_setupuser' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'nama_stock' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                
        ],
        'id_satuan' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'qty_1' => [
                'type'           => 'vARCHAR',
                'constraint'     => 100,
                
        ],
        'qty_2' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        
       
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_stockopname', true);
    
    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_lokasi', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_setupuser', 'setupuser1', 'id_setupuser', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_satuan', 'satuan1', 'id_satuan', 'CASCADE', 'CASCADE');

    // Membuat tabel
    $this->forge->createTable('stockopname1');
    }

    public function down()
    {
        $this->forge->dropTable('stockopname1');
    }
}
