<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountingPeriodsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_perusahaan' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'period_start' => ['type' => 'DATE'],
            'period_end' => ['type' => 'DATE'],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['open', 'closed'],
                'default' => 'open',
            ],
            'closed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'closed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['id_perusahaan', 'period_start', 'period_end'], 'accounting_period_unique');
        $this->forge->createTable('accounting_periods', true);
    }

    public function down()
    {
        $this->forge->dropTable('accounting_periods', true);
    }
}
