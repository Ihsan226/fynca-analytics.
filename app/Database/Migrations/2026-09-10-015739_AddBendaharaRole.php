<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBendaharaRole extends Migration
{
    public function up()
    {
        // Add 'bendahara' to users role enum
        $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'analyst', 'owner', 'staff', 'bendahara') NOT NULL";
        $this->db->query($sql);
    }

    public function down()
    {
        $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'analyst', 'owner', 'staff') NOT NULL";
        $this->db->query($sql);
    }
}
