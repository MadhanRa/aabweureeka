<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PosNeraca extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_posneraca' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_posneraca' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'kode_posneraca' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],    
           'id_klasifikasi' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
            ],
            'posisi_posneraca' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => false,
            ],
        ]);
    
        // Menentukan primary key
        $this->forge->addKey('id_posneraca', true);
        
        // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
        $this->forge->addForeignKey('id_klasifikasi', 'klasifikasi1', 'id_klasifikasi', 'CASCADE', 'CASCADE');
        
        // Membuat tabel
        $this->forge->createTable('pos_neraca');
    
    }

    public function down()
    {
        $this->forge->dropTable('pos_neraca');
    }
}
