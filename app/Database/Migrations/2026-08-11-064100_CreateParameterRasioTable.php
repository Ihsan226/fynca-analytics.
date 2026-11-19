<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateParameterRasioTable extends Migration
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
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'variabel' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'batas_bawah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'default'    => 0,
            ],
            'batas_atas' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,4',
                'default'    => 0,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'warna' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'blue',
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
        $this->forge->createTable('parameter_rasio');
    }

    public function down()
    {
        $this->forge->dropTable('parameter_rasio');
    }
}
