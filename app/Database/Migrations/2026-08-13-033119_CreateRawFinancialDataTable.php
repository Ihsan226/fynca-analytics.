<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRawFinancialDataTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_raw' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_perusahaan' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tahun' => [
                'type'       => 'YEAR',
            ],
            'source_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'page' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => true,
            ],
            'label' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'value' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'IDR',
            ],
            'source_type' => [
                'type'       => 'VARCHAR', // e.g., PDF, XLSX, XBRL
                'constraint' => 50,
                'null'       => true,
            ],
            'mapped_to' => [
                'type'       => 'VARCHAR', // the normalized field name (e.g., profit_before_tax)
                'constraint' => 100,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_raw', true);
        // $this->forge->addForeignKey('id_perusahaan', 'perusahaan', 'id_perusahaan', 'CASCADE', 'CASCADE');
        $this->forge->createTable('raw_financial_data');
    }

    public function down()
    {
        $this->forge->dropTable('raw_financial_data');
    }
}
