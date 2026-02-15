<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Stock extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_stock' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_lokasi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
            ],
            'satuan_stock' => [
                'type'           => 'VARCHAR',
                'constraint'     => 250,
            ],
            'jml_harga' => [
                'type'       => 'VARCHAR',
                'constraint' => 250,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ]    
        ]);
    
        // Set primary key for id_stock
        $this->forge->addKey('id_stock', true);
        
        // Foreign key configuration
        $this->forge->addForeignKey('id_lokasi', 'lokasi1', 'id_lokasi');
    
        // Create the table
        $this->forge->createTable('stock1');
    }

    public function down()
    {
        $this->forge->dropForeignKey('stock1', 'stock1_id_lokasi_foreign');
        $this->forge->dropTable('stock1');
    }
}
