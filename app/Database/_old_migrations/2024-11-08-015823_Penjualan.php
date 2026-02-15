<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penjualan extends Migration
{
    public function up()
    {
        // Mendefinisikan struktur tabel bahansablon1
        $this->forge->addField([
            'id_penjualan' => [
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
            'TOP' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,

            ],
            'tgl_jatuhtempo' => [
                'type' => 'DATE',
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
            'no_fp' => [
                'type'           => 'INT',
                'constraint'     => 250,
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
        $this->forge->addKey('id_penjualan', true);

        // Menambahkan foreign key untuk lokasi_asal yang merujuk ke lokasi1.kode_lokasi
        $this->forge->addForeignKey('id_pelanggan', 'setuppelanggan1', 'id_pelanggan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_lokasi', 'lokasi1', 'id_lokasi', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_satuan', 'satuan1', 'id_satuan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_setupsalesman', 'setupsalesman1', 'id_setupsalesman', 'CASCADE', 'CASCADE');
        // Membuat tabel bahansablon1
        $this->forge->createTable('penjualan1');
    }

    public function down()
    {
        $this->forge->dropTable('penjualan1');
    }
}
