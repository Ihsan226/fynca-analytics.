<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectorConfig extends Migration
{
    public function up()
    {
        $this->forge->addColumn('sektor', [
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status',
            ],
            'enable_etr' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'deskripsi',
            ],
            'enable_rpt' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'enable_etr',
            ],
            'enable_der' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'enable_rpt',
            ],
            'enable_sales_growth' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'enable_der',
            ],
            'special_rules' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'enable_sales_growth',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sektor', [
            'deskripsi',
            'enable_etr',
            'enable_rpt',
            'enable_der',
            'enable_sales_growth',
            'special_rules',
        ]);
    }
}
