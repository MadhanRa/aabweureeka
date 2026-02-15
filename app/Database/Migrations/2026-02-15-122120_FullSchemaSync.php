<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FullSchemaSync extends Migration
{
    public function up()
    {
        $path = "D:\OneDrive\Project\Akuntansi Eeureka\aabw_schema.sql";

        // 2. Cek apakah file ada
        if (file_exists($path)) {

            // 3. Ambil isi file
            $sql = file_get_contents($path);

            // 4. Pisahkan query berdasarkan tanda titik koma (;)
            // Karena $db->query() sebaiknya menjalankan 1 perintah dalam 1 waktu
            $statements = explode(';', $sql);

            foreach ($statements as $statement) {
                $statement = trim($statement);

                // Jalankan hanya jika query tidak kosong
                if (!empty($statement)) {
                    try {
                        $this->db->query($statement);
                    } catch (\Throwable $e) {
                        // Opsi: Tampilkan error tapi lanjut, atau stop.
                        // Disini kita biarkan error tampil agar ketahuan
                        echo "Error pada query: " . substr($statement, 0, 50) . "...\n";
                        throw $e;
                    }
                }
            }
        }
    }
    public function down()
    {
    }
}
