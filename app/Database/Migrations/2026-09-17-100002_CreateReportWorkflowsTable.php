<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReportWorkflowsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_workflow' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_laporan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'analyst_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'processing', 'completed', 'revision'],
                'default'    => 'pending',
            ],
            'accounting_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'processing', 'submitted_to_treasurer', 'completed'],
                'default'    => 'pending',
            ],
            'treasurer_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'paid', 'rejected'],
                'default'    => 'pending',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_workflow', true);
        $this->forge->addKey('id_laporan');
        
        // Add foreign key if supported, but typically CI4 forge needs to execute then add FK.
        // We'll skip formal FK for now to avoid migration drop issues, but logically linked.

        $this->forge->createTable('report_workflows');
    }

    public function down()
    {
        $this->forge->dropTable('report_workflows');
    }
}
