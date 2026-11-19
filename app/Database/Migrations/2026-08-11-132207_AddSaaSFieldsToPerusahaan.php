<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSaaSFieldsToPerusahaan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('perusahaan', [
            'id_organization' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_perusahaan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('perusahaan', 'id_organization');
    }
}
