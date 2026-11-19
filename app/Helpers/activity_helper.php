<?php

if (!function_exists('log_activity')) {
    /**
     * Helper untuk mencatat aktivitas sistem
     *
     * @param string $aksi   Contoh: 'Import Data', 'Hapus Data', 'Login'
     * @param string $detail Penjelasan aktivitas, contoh: 'Import laporan keuangan PT ABC 2026'
     * @param string $tipe   SISTEM, AUTH, DATA (kategori)
     */
    function log_activity($aksi, $detail, $tipe = 'SISTEM')
    {
        $logModel = new \App\Models\LogAktivitasModel();
        
        $user = session()->get('nama_lengkap') ?? 'Sistem';
        
        $data = [
            'user'       => $user,
            'aksi'       => $aksi,
            'detail'     => $detail,
            'tipe'       => $tipe,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $res = $logModel->insert($data);
        if ($res === false) {
            log_message('error', 'Activity Log Failed: ' . json_encode($logModel->errors()));
        }
    }
}
