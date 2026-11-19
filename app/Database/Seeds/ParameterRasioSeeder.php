<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ParameterRasioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'        => 'DER Sehat (< 1)',
                'variabel'    => 'DER',
                'batas_bawah' => 0,
                'batas_atas'  => 1.0,
                'keterangan'  => 'Rasio hutang terhadap ekuitas dalam batas aman',
                'warna'       => 'green',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama'        => 'DER Moderat (1–2)',
                'variabel'    => 'DER',
                'batas_bawah' => 1.0,
                'batas_atas'  => 2.0,
                'keterangan'  => 'Rasio hutang mulai perlu perhatian',
                'warna'       => 'amber',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama'        => 'DER Tinggi (> 2)',
                'variabel'    => 'DER',
                'batas_bawah' => 2.0,
                'batas_atas'  => 999,
                'keterangan'  => 'Risiko Thin Capitalization sangat tinggi',
                'warna'       => 'rose',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama'        => 'ETR Agresif',
                'variabel'    => 'ETR',
                'batas_bawah' => 0,
                'batas_atas'  => 0.22,
                'keterangan'  => 'ETR di bawah tarif efektif menandakan agresivitas pajak',
                'warna'       => 'rose',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama'        => 'DER Batas PMK (4:1)',
                'variabel'    => 'DER',
                'batas_bawah' => 0,
                'batas_atas'  => 4.0,
                'keterangan'  => 'Batas wajar DER menurut PMK No. 169/PMK.010/2015',
                'warna'       => 'blue',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('parameter_rasio')->insertBatch($data);
    }
}
