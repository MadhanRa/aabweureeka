<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penyesuaianstock extends Migration
{
    public function up()
    {
                // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_penyesuaianstock' => [
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
        'id_setupbank' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'id_lokasi' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'id_group' => [
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
        'saldo' => [
            'type'           => 'VARCHAR',
            'constraint'     => 100,
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
        'penyesuaian' => [
            'type'       => 'INT',
            'constraint' => 100,
            
        ],
        'keterangan' => [
            'type'       => 'INT',
            'constraint' => 100,
            
        ],
        
       
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_penyesuaianstock', true);
    
    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_setupbank', 'setupbank1', 'id_setupbank', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_lokasi', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_group', 'group1', 'id_group', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('id_satuan', 'satuan1', 'id_satuan', 'CASCADE', 'CASCADE');
    

    // Membuat tabel
    $this->forge->createTable('penyesuaianstock1');
    }

    public function down()
    {
        $this->forge->dropTable('penyesuaianstock1');
    }
}
