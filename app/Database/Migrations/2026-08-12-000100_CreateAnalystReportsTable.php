<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnalystReportsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'comment'    => 'Analyst who created',
            ],
            'id_perusahaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_laporan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_analisis' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'tahun' => [
                'type'       => 'INT',
                'constraint' => 4,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'jenis_analisis' => [
                'type'       => 'ENUM',
                'constraint' => ['tax_indicator', 'debt_equity', 'profitability', 'tax_risk', 'simulation'],
                'default'    => 'tax_indicator',
            ],
            'analyst_insight' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'simulation_data' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'recommendation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'submitted', 'revision', 'approved'],
                'default'    => 'draft',
            ],
            'reviewer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'reviewer_comment' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
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

        $this->forge->addKey('id', true);
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_perusahaan');
        $this->forge->addKey('status');
        $this->forge->createTable('analyst_reports');
    }

    public function down()
    {
        $this->forge->dropTable('analyst_reports');
    }
}
