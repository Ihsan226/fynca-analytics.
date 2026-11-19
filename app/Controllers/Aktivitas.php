<?php
namespace App\Controllers;

use App\Models\LogAktivitasModel;

class Aktivitas extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new LogAktivitasModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Log Aktivitas Sistem',
            'logs'  => $this->logModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('aktivitas/index', $data);
    }

    public function clear()
    {
        // Menghapus semua data log
        $this->logModel->truncate();

        return redirect()->to('/aktivitas')->with('success', 'Seluruh log aktivitas berhasil dibersihkan.');
    }
}
