<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePotonganPajakTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_potongan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_journal_item' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jenis_pajak' => [
                'type'       => 'VARCHAR',
                'constraint' => 100, // e.g. PPh Pasal 4(2), PPh 23
            ],
            'tarif' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2', // percentage
            ],
            'nominal_potongan' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,2',
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
        $this->forge->addKey('id_potongan', true);
        $this->forge->createTable('potongan_pajak');
    }

    public function down()
    {
        $this->forge->dropTable('potongan_pajak');
    }
}
