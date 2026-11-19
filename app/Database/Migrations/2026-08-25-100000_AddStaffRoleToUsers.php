<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStaffRoleToUsers extends Migration
{
    public function up()
    {
        // Ubah enum role di tabel users untuk menambahkan staff
        $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'analyst', 'owner', 'staff') NOT NULL";
        $this->db->query($sql);
    }

    public function down()
    {
        // Downgrade kembali ke role sebelumnya (dengan resiko error jika ada data staff)
        $sql = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'analyst', 'owner') NOT NULL";
        $this->db->query($sql);
    }
}
