<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkflowHistoryTable extends Migration
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
                'constraint' => 80,
            ],
            'entity_id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'from_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'to_status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'actor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'request_key' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['entity_type', 'entity_id']);
        $this->forge->addKey(['actor_id', 'created_at']);
        $this->forge->addUniqueKey('request_key');
        $this->forge->createTable('workflow_history', true);
    }

    public function down()
    {
        $this->forge->dropTable('workflow_history', true);
    }
}
