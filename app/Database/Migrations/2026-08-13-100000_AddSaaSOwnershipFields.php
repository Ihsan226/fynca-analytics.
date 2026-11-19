<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSaaSOwnershipFields extends Migration
{
    public function up()
    {
        // Add assigned_analyst to perusahaan
        $this->forge->addColumn('perusahaan', [
            'assigned_analyst' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_organization', // Assuming id_organization exists
            ],
        ]);

        // Add prepared_by and status to laporan_keuangan
        $this->forge->addColumn('laporan_keuangan', [
            'prepared_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'tahun',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Draft',
                'null'       => false,
                'after'      => 'prepared_by',
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('perusahaan', 'assigned_analyst');
        $this->forge->dropColumn('laporan_keuangan', 'prepared_by');
        $this->forge->dropColumn('laporan_keuangan', 'status');
    }
}
