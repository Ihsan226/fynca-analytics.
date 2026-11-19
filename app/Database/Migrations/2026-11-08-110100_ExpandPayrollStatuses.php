<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExpandPayrollStatuses extends Migration
{
    public function up()
    {
        $this->db->query(
            "ALTER TABLE finance_payroll MODIFY status ENUM('draft','pending_approval','approved','processing','paid','rejected','locked') NOT NULL DEFAULT 'draft'"
        );
    }

    public function down()
    {
        $this->db->query(
            "ALTER TABLE finance_payroll MODIFY status ENUM('draft','pending_approval','approved','locked') NOT NULL DEFAULT 'draft'"
        );
    }
}
