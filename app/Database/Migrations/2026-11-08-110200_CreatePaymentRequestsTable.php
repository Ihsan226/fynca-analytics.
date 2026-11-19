<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'entity_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'id_organization' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'entity_period_month' => [
                'type' => 'TINYINT',
                'constraint' => 2,
                'null' => true,
            ],
            'entity_period_year' => [
                'type' => 'SMALLINT',
                'constraint' => 4,
                'null' => true,
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['requested', 'processing', 'paid', 'failed', 'cancelled'],
                'default' => 'requested',
            ],
            'requested_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'executed_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'payment_proof' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'failure_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'requested_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'paid_at' => [
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
        $this->forge->addKey(['id_organization', 'entity_type', 'entity_period_month', 'entity_period_year'], false, false, 'payment_request_scope_idx');
        $this->forge->addUniqueKey(['id_organization', 'entity_type', 'entity_period_month', 'entity_period_year'], 'payment_request_scope_unique');
        $this->forge->createTable('payment_requests', true);
    }

    public function down()
    {
        $this->forge->dropTable('payment_requests', true);
    }
}
