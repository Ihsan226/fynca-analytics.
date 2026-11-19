<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_organization' => 1,
                'nama_organisasi' => 'PT Konsultan Pajak ABC',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'id_organization' => 2,
                'nama_organisasi' => 'KJA DEF Associates',
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('organizations')->ignore(true)->insertBatch($data);
    }
}
