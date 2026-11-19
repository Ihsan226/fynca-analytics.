<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LogAktivitasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user'       => 'Admin',
                'aksi'       => 'Input Laporan Keuangan',
                'detail'     => 'Menambahkan data keuangan CTRA tahun 2022',
                'tipe'       => 'create',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            ],
            [
                'user'       => 'Admin',
                'aksi'       => 'Input Laporan Keuangan',
                'detail'     => 'Menambahkan data keuangan BSDE tahun 2022',
                'tipe'       => 'create',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 hours')),
            ],
            [
                'user'       => 'Admin',
                'aksi'       => 'Kalkulasi Otomatis',
                'detail'     => 'Sistem menghitung rasio ETR, RPT, DER, dan Sales Growth untuk 2 emiten',
                'tipe'       => 'system',
                'created_at' => date('Y-m-d H:i:s', strtotime('-50 minutes')),
            ],
            [
                'user'       => 'Admin',
                'aksi'       => 'Login Sistem',
                'detail'     => 'Admin berhasil masuk ke sistem',
                'tipe'       => 'auth',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 minutes')),
            ]
        ];

        $this->db->table('log_aktivitas')->insertBatch($data);
    }
}
