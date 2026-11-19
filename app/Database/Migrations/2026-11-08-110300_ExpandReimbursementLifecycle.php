<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExpandReimbursementLifecycle extends Migration
{
    public function up()
    {
        $this->db->query(
            "ALTER TABLE finance_reimbursement MODIFY status ENUM('pending','approved','payment_requested','processing','paid','rejected','failed') NOT NULL DEFAULT 'pending'"
        );
        $this->forge->addColumn('finance_reimbursement', [
            'payment_proof' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'bukti_file',
            ],
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'payment_proof',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('finance_reimbursement', ['payment_proof', 'paid_at']);
        $this->db->query(
            "ALTER TABLE finance_reimbursement MODIFY status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'"
        );
    }
}
