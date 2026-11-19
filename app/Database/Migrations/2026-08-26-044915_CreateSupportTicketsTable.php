<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupportTicketsTable extends Migration
{
    public function up()
    {
        // Support Tickets Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_user'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'subject'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'      => ['type' => 'ENUM', 'constraint' => ['open', 'in_progress', 'resolved'], 'default' => 'open'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('support_tickets');

        // Ticket Replies Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ticket_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_user'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'message'     => ['type' => 'TEXT'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('ticket_replies');
    }

    public function down()
    {
        $this->forge->dropTable('ticket_replies');
        $this->forge->dropTable('support_tickets');
    }
}
