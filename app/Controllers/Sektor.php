<?php

namespace App\Controllers;

use App\Models\SektorModel;
use App\Models\PerusahaanModel;

class Sektor extends BaseController
{
    protected $sektorModel;
    protected $perusahaanModel;

    public function __construct()
    {
        $this->sektorModel     = new SektorModel();
        $this->perusahaanModel = new PerusahaanModel();
    }

    public function index()
    {
        $sektors = $this->sektorModel->orderBy('id', 'ASC')->findAll();

        // Hitung jumlah klien per sektor
        foreach ($sektors as &$s) {
            $s['jumlah_klien'] = $this->perusahaanModel
                ->where('sektor', $s['nama'])
                ->countAllResults();
        }

        $data = [
            'title'   => 'Sector Profiles',
            'sektors' => $sektors,
        ];

        return view('sektor/index', $data);
    }

    public function store()
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/sektor')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }

        $this->sektorModel->save([
            'nama'               => $this->request->getPost('nama'),
            'kode'               => strtoupper($this->request->getPost('kode')),
            'status'             => $this->request->getPost('status') ?? 'Aktif',
            'deskripsi'          => $this->request->getPost('deskripsi'),
            'enable_etr'         => $this->request->getPost('enable_etr') ?? 1,
            'enable_rpt'         => $this->request->getPost('enable_rpt') ?? 1,
            'enable_der'         => $this->request->getPost('enable_der') ?? 1,
            'enable_sales_growth'=> $this->request->getPost('enable_sales_growth') ?? 1,
            'special_rules'      => $this->request->getPost('special_rules'),
        ]);

        return redirect()->to('/sektor')->with('success', 'Sektor baru berhasil ditambahkan.');
    }

    public function update($id)
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/sektor')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }

        $sektor = $this->sektorModel->find($id);

        if (!$sektor) {
            return redirect()->to('/sektor')->with('error', 'Data sektor tidak ditemukan.');
        }

        $this->sektorModel->update($id, [
            'nama'               => $this->request->getPost('nama'),
            'kode'               => strtoupper($this->request->getPost('kode')),
            'status'             => $this->request->getPost('status'),
            'deskripsi'          => $this->request->getPost('deskripsi'),
            'enable_etr'         => $this->request->getPost('enable_etr') ?? 1,
            'enable_rpt'         => $this->request->getPost('enable_rpt') ?? 1,
            'enable_der'         => $this->request->getPost('enable_der') ?? 1,
            'enable_sales_growth'=> $this->request->getPost('enable_sales_growth') ?? 1,
            'special_rules'      => $this->request->getPost('special_rules'),
        ]);

        return redirect()->to('/sektor')->with('success', 'Data sektor berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/sektor')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }

        $sektor = $this->sektorModel->find($id);

        if (!$sektor) {
            return redirect()->to('/sektor')->with('error', 'Data sektor tidak ditemukan.');
        }

        // Cek apakah ada klien yang masih menggunakan sektor ini
        $klienCount = $this->perusahaanModel->where('sektor', $sektor['nama'])->countAllResults();
        if ($klienCount > 0) {
            return redirect()->to('/sektor')->with('error', 'Tidak dapat menghapus sektor karena masih ada ' . $klienCount . ' klien yang terkait.');
        }

        $this->sektorModel->delete($id);

        return redirect()->to('/sektor')->with('success', 'Sektor berhasil dihapus.');
    }
}
