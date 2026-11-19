<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTaxParametersTable extends Migration
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
            'tax_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'parameter_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'value' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'effective_date' => [
                'type'       => 'DATE',
                'null'       => true,
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
        $this->forge->createTable('tax_parameters');
    }

    public function down()
    {
        $this->forge->dropTable('tax_parameters');
    }
}
