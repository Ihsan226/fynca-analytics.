<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusPernikahanToGaji extends Migration
{
    public function up()
    {
        $this->forge->addColumn('finance_gaji_komponen', [
            'status_pernikahan' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'default'    => 'TK/0', // Tidak Kawin, 0 Tanggungan
                'after'      => 'potongan_bpjs'
            ]
        ]);
        
        $this->forge->addColumn('finance_payroll', [
            'potongan_pph21' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'after'      => 'potongan_bpjs'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('finance_gaji_komponen', 'status_pernikahan');
        $this->forge->dropColumn('finance_payroll', 'potongan_pph21');
    }
}
