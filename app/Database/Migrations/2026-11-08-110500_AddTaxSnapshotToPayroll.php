<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTaxSnapshotToPayroll extends Migration
{
    public function up()
    {
        $this->forge->addColumn('finance_payroll', [
            'tax_gross_monthly' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'tax_gross_annual' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'tax_ptkp' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'tax_pkp' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'tax_rule_version' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('finance_payroll', [
            'tax_gross_monthly',
            'tax_gross_annual',
            'tax_ptkp',
            'tax_pkp',
            'tax_rule_version',
        ]);
    }
}
