<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAkuntansiRole extends Migration
{
    public function up()
    {
        // Add 'akuntansi' to users role enum
        $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'analyst', 'owner', 'staff', 'bendahara', 'akuntansi') NOT NULL";
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'analyst', 'owner', 'staff', 'bendahara') NOT NULL";
        $this->db->query($sql);
    }
}
