<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccountingTables extends Migration
{
    public function up()
    {
        // 1. acc_coa (Chart of Accounts)
        $this->forge->addField([
            'id_coa' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_akun' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'tipe_akun' => [
                'type'       => 'ENUM',
                'constraint' => ['asset', 'liability', 'equity', 'revenue', 'expense'],
                'default'    => 'asset',
            ],
            'saldo_normal' => [
                'type'       => 'ENUM',
                'constraint' => ['debit', 'credit'],
                'default'    => 'debit',
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
        $this->forge->addKey('id_coa', true);
        $this->forge->addUniqueKey('kode_akun');
        $this->forge->createTable('acc_coa');

        // 2. acc_journals
        $this->forge->addField([
            'id_journal' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_perusahaan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'referensi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'posted'],
                'default'    => 'draft',
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
        $this->forge->addKey('id_journal', true);
        $this->forge->createTable('acc_journals');

        // 3. acc_journal_items
        $this->forge->addField([
            'id_item' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_journal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_coa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'debit' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0,
            ],
            'credit' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
                'default'    => 0,
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
        $this->forge->addKey('id_item', true);
        $this->forge->createTable('acc_journal_items');
    }

    public function down()
    {
        $this->forge->dropTable('acc_journal_items');
        $this->forge->dropTable('acc_journals');
        $this->forge->dropTable('acc_coa');
    }
}
