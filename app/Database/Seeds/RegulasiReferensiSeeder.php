<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegulasiReferensiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'indikator_terkait' => 'DER',
                'kode_regulasi'     => 'PMK 169/2015',
                'judul'             => 'PMK No. 169/PMK.010/2015',
                'ringkasan'         => 'Penentuan besarnya perbandingan antara utang dan modal perusahaan untuk keperluan penghitungan pajak penghasilan. Batas maksimal yang diperkenankan adalah 4:1.',
                'url'               => 'https://peraturan.bpk.go.id/Home/Details/116499/pmk-no-169pmk0102015',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'indikator_terkait' => 'RPT Ratio',
                'kode_regulasi'     => 'PER-32/PJ/2011',
                'judul'             => 'PER-32/PJ/2011',
                'ringkasan'         => 'Penerapan Prinsip Kewajaran dan Kelaziman Usaha dalam Transaksi antara Wajib Pajak dengan Pihak yang Mempunyai Hubungan Istimewa.',
                'url'               => 'https://peraturan.bpk.go.id/Home/Details/128362/peraturan-dirjen-pajak-no-per-32pj2011',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'indikator_terkait' => 'ETR',
                'kode_regulasi'     => 'UU HPP',
                'judul'             => 'UU No. 7 Tahun 2021 (HPP)',
                'ringkasan'         => 'Tarif Pajak Penghasilan Badan sebesar 22% mulai tahun pajak 2022 sesuai Undang-Undang Harmonisasi Peraturan Perpajakan.',
                'url'               => 'https://peraturan.bpk.go.id/Home/Details/185162/uu-no-7-tahun-2021',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('regulasi_referensi')->insertBatch($data);
    }
}
