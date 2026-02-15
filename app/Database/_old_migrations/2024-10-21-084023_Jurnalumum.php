<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Jurnalumum extends Migration
{
    public function up()
    {
         // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_jurnalumum' => [
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
        'rekening' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                
        ],
        'b_pembantu' => [
            'type' => 'text',
            
        ],
        'nama_rekening' => [
                'type'           => 'vARCHAR',
                'constraint'     => 100,
                
        ],
        'nama_bpembantu' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'no_ref' => [
            'type'           => 'INT',
            'constraint'     => 100,
            'unsigned'       => true,
        ],
        'debet' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
            
        ],
        'kredit' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
            
        ],
        'tgl_nota' => [
            'type'       => 'INT',
            'constraint' => 11,
            'unsigned'   => true,
        ],
        'keterangan' => [
            'type'       => 'TEXT',
            
        ],
       
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_jurnalumum', true);

    // Membuat tabel
    $this->forge->createTable('jurnalumum1');
    }

    public function down()
    {
        $this->forge->dropTable('jurnalumum1');
    }
}
