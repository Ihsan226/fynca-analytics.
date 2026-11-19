<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParameterType extends Migration
{
    public function up()
    {
        $this->forge->addColumn('parameter_rasio', [
            'tipe' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'analytical',
                'after'      => 'warna',
            ],
            'regulasi_ref' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'tipe',
            ],
            'regulasi_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'after'      => 'regulasi_ref',
            ],
            'berlaku_sejak' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'regulasi_url',
            ],
            'berlaku_hingga' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'berlaku_sejak',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('parameter_rasio', [
            'tipe',
            'regulasi_ref',
            'regulasi_url',
            'berlaku_sejak',
            'berlaku_hingga',
        ]);
    }
}
