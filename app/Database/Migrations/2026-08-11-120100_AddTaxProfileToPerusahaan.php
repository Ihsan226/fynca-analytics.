<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTaxProfileToPerusahaan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('perusahaan', [
            'has_related_party' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'tahun_ipo',
            ],
            'has_rpt_transaction' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'has_related_party',
            ],
            'has_cross_border' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'has_rpt_transaction',
            ],
            'has_interest_debt' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'has_cross_border',
            ],
            'is_public_company' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'has_interest_debt',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('perusahaan', [
            'has_related_party',
            'has_rpt_transaction',
            'has_cross_border',
            'has_interest_debt',
            'is_public_company',
        ]);
    }
}
