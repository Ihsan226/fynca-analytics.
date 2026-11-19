<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNotesToAnalystReports extends Migration
{
    public function up()
    {
        $fields = [
            'note_etr' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'note_rpt' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'note_der' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'note_growth' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('analyst_reports', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('analyst_reports', 'note_etr');
        $this->forge->dropColumn('analyst_reports', 'note_rpt');
        $this->forge->dropColumn('analyst_reports', 'note_der');
        $this->forge->dropColumn('analyst_reports', 'note_growth');
    }
}
