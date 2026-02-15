<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSetupGroup extends Migration
{
    public function up()
    {
        // First, drop the existing foreign key constraint
        $this->db->query('ALTER TABLE group1 DROP FOREIGN KEY fk_group1_interface');

        // Rename the column
        $this->db->query('ALTER TABLE group1 CHANGE id_interface id_setupbuku INT(6) UNSIGNED NULL');

        // Add a new foreign key constraint pointing to the correct table
        // Note: You'll need to adjust the reference table and column if needed
        $this->db->query('ALTER TABLE group1 ADD CONSTRAINT fk_group1_setupbuku1 FOREIGN KEY (id_setupbuku) REFERENCES setupbuku1(id_setupbuku) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop the new foreign key constraint
        $this->db->query('ALTER TABLE group1 DROP FOREIGN KEY fk_group1_setupbuku');

        // Rename the column back to original name
        $this->db->query('ALTER TABLE group1 CHANGE id_setupbuku id_interface INT(6) UNSIGNED NULL');

        // Re-add the original foreign key
        $this->db->query('ALTER TABLE group1 ADD CONSTRAINT fk_group1_interface FOREIGN KEY (id_interface) REFERENCES interface1(id_interface) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}
