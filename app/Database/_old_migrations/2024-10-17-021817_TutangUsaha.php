<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TutangUsaha extends Migration
{
    public function up()
    {
         // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_lunashusaha' => [
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => true,
            'auto_increment' => true,
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
        'tanggal' => [
            'type' => 'DATE',
        ],
        'id_setupbank' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'saldo' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'nilai_pelunasan' => [
            'type'           => 'INT',
            'constraint'     => 100,
            'unsigned'       => true,
        ],
        'diskon' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'pdpt' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
            
        ],
        'sisa' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'keterangan' => [
            'type'       => 'TEXT',
            
        ],
       
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_lunashusaha', true);

    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_pelanggan', 'setuppelanggan1', 'id_pelanggan', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_tujuan yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_setupbank', 'setupbank1', 'id_setupbank', 'CASCADE', 'CASCADE');


    // Membuat tabel
    $this->forge->createTable('tutangusaha1');
    }

    public function down()
    {
        $this->forge->dropTable('tutangusaha1');
    }
}
