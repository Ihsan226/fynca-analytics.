<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSaaSFieldsToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'id_organization' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_user',
            ],
        ]);

        // Modify role ENUM
        $this->forge->modifyColumn('users', [
            'role' => [
                'name'       => 'role',
                'type'       => 'ENUM',
                'constraint' => ['owner', 'admin', 'analyst', 'viewer'],
                'default'    => 'admin',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'id_organization');
        
        $this->forge->modifyColumn('users', [
            'role' => [
                'name'       => 'role',
                'type'       => 'ENUM',
                'constraint' => ['admin', 'pimpinan'],
                'default'    => 'admin',
                'null'       => true,
            ],
        ]);
    }
}
