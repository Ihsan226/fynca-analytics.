<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameAnalysisColumns extends Migration
{
    public function up()
    {
        // Rename val_agresivitas → val_etr
        $this->forge->modifyColumn('hasil_analisis', [
            'val_agresivitas' => [
                'name'       => 'val_etr',
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
            ],
        ]);

        // Rename val_transfer_pricing → val_rpt_ratio
        $this->forge->modifyColumn('hasil_analisis', [
            'val_transfer_pricing' => [
                'name'       => 'val_rpt_ratio',
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
            ],
        ]);

        // Rename val_thin_cap → val_der
        $this->forge->modifyColumn('hasil_analisis', [
            'val_thin_cap' => [
                'name'       => 'val_der',
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        // Revert: val_etr → val_agresivitas
        $this->forge->modifyColumn('hasil_analisis', [
            'val_etr' => [
                'name'       => 'val_agresivitas',
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
            ],
        ]);

        // Revert: val_rpt_ratio → val_transfer_pricing
        $this->forge->modifyColumn('hasil_analisis', [
            'val_rpt_ratio' => [
                'name'       => 'val_transfer_pricing',
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
            ],
        ]);

        // Revert: val_der → val_thin_cap
        $this->forge->modifyColumn('hasil_analisis', [
            'val_der' => [
                'name'       => 'val_thin_cap',
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'null'       => true,
            ],
        ]);
    }
}
