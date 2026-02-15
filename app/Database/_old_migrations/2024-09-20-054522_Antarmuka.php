<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class antarmuka extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_interface' => [
                'type'           => 'INT',
                'constraint'     => 6,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kas_interface' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
            ],
            'biaya' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'hutang' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'hpp' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'terima_mundur' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'laba_ditahan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'hutang_lancar' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'neraca_laba' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'piutang_salesman' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'rekening_biaya' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
            ],
            'piutang_dagang' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'penjualan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'retur_penjualan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'diskon_penjualan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'laba_bulan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'laba_tahun' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'potongan_pembelian' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'ppn_masukan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'ppn_keluaran' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'potongan_penjualan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'bank' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);
        $this->forge->addKey('id_interface', true);
        $this->forge->createTable('interface1');
    }

    public function down()
    {
        $this->forge->dropTable('interface1');
    }
}
