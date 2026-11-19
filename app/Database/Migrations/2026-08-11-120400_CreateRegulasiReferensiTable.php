<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegulasiReferensiTable extends Migration
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
            'kode_regulasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
            ],
            'ringkasan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
            ],
            'indikator_terkait' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
            ],
            'berlaku_sejak' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'berlaku_hingga' => [
                'type' => 'DATE',
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
        $this->forge->createTable('regulasi_referensi');
    }

    public function down()
    {
        $this->forge->dropTable('regulasi_referensi');
    }
}
