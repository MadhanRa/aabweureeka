<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KasKecil extends Migration
{
    public function up()
    {
        // Mendefinisikan struktur tabel bahansablon1
       $this->forge->addField([
        'id_kaskecil' => [
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
        'id_interface' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
        ],
        'id_kelproduksi' => [
            'type'           => 'INT',
            'constraint'     => 6,
            'unsigned'       => true,
        ],
        'rekening' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                
        ],
        'b_pembantu' => [
            'type' => 'DATE',
        ],
        'nama_rekening' => [
                'type'           => 'vARCHAR',
                'constraint'     => 100,
                
        ],
        'nama_bpembantu' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'rp' => [
            'type'           => 'INT',
            'constraint'     => 100,
            'unsigned'       => true,
        ],
        'keterangan' => [
            'type'       => 'TEXT',
            
        ],
        
       
    ]);

    // Menambahkan primary key
    $this->forge->addKey('id_kaskecil', true);
    
    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_interface', 'interface1', 'id_interface', 'CASCADE', 'CASCADE');

    // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
    $this->forge->addForeignKey('id_kelproduksi', 'kelompokproduksi1', 'id_kelproduksi', 'CASCADE', 'CASCADE');

    // Membuat tabel
    $this->forge->createTable('kaskecil1');
    }
    

    public function down()
    {
        $this->forge->dropTable('kaskecil1');
    }
}
