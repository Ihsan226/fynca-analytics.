<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFinanceTables extends Migration
{
    public function up()
    {
        // 1. finance_gaji_komponen
        $this->forge->addField([
            'id_user'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'gaji_pokok'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'tunjangan'      => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'potongan_bpjs'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_user', true);
        $this->forge->createTable('finance_gaji_komponen', true);

        // 2. finance_payroll
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_user'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'periode_bulan'    => ['type' => 'TINYINT', 'constraint' => 2],
            'periode_tahun'    => ['type' => 'YEAR', 'constraint' => 4],
            'gaji_pokok'       => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'tunjangan'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'bonus'            => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'potongan_bpjs'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'potongan_lain'    => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'total_bersih'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'status'           => ['type' => 'ENUM', 'constraint' => ['draft', 'pending_approval', 'approved', 'locked'], 'default' => 'draft'],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('finance_payroll', true);

        // 3. finance_reimbursement
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_user'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tanggal'        => ['type' => 'DATE'],
            'deskripsi'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'nominal'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'bukti_file'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'         => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected'], 'default' => 'pending'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('finance_reimbursement', true);

        // 4. finance_jurnal_kas
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tanggal'        => ['type' => 'DATE'],
            'tipe'           => ['type' => 'ENUM', 'constraint' => ['debit', 'kredit']],
            'nominal'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'deskripsi'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'referensi_tipe' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true], // 'payroll' or 'reimbursement' or null for manual
            'referensi_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('finance_jurnal_kas', true);
    }

    public function down()
    {
        $this->forge->dropTable('finance_jurnal_kas', true);
        $this->forge->dropTable('finance_reimbursement', true);
        $this->forge->dropTable('finance_payroll', true);
        $this->forge->dropTable('finance_gaji_komponen', true);
    }
}
