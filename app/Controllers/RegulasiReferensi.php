<?php

namespace App\Controllers;

use App\Models\RegulasiReferensiModel;

class RegulasiReferensi extends BaseController
{
    protected $regulasiModel;

    public function __construct()
    {
        $this->regulasiModel = new RegulasiReferensiModel();
    }

    public function index()
    {
        $regulasi = $this->regulasiModel->orderBy('indikator_terkait', 'ASC')->findAll();

        // Group by indicator
        $grouped = [];
        foreach ($regulasi as $r) {
            $key = $r['indikator_terkait'] ?? 'general';
            $grouped[$key][] = $r;
        }

        $data = [
            'title'    => 'Regulatory References',
            'regulasi' => $regulasi,
            'grouped'  => $grouped,
        ];
        return view('regulasi/index', $data);
    }

    public function store()
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/regulasi')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }

        $this->regulasiModel->save([
            'kode_regulasi'     => $this->request->getPost('kode_regulasi'),
            'judul'             => $this->request->getPost('judul'),
            'ringkasan'         => $this->request->getPost('ringkasan'),
            'url'               => $this->request->getPost('url'),
            'indikator_terkait' => $this->request->getPost('indikator_terkait'),
            'status'            => $this->request->getPost('status') ?? 'active',
            'berlaku_sejak'     => $this->request->getPost('berlaku_sejak') ?: null,
            'berlaku_hingga'    => $this->request->getPost('berlaku_hingga') ?: null,
        ]);

        return redirect()->to('/regulasi')->with('success', 'Regulasi berhasil ditambahkan.');
    }

    public function update($id)
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/regulasi')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }

        $regulasi = $this->regulasiModel->find($id);
        if (!$regulasi) {
            return redirect()->to('/regulasi')->with('error', 'Data regulasi tidak ditemukan.');
        }

        $this->regulasiModel->update($id, [
            'kode_regulasi'     => $this->request->getPost('kode_regulasi'),
            'judul'             => $this->request->getPost('judul'),
            'ringkasan'         => $this->request->getPost('ringkasan'),
            'url'               => $this->request->getPost('url'),
            'indikator_terkait' => $this->request->getPost('indikator_terkait'),
            'status'            => $this->request->getPost('status'),
            'berlaku_sejak'     => $this->request->getPost('berlaku_sejak') ?: null,
            'berlaku_hingga'    => $this->request->getPost('berlaku_hingga') ?: null,
        ]);

        return redirect()->to('/regulasi')->with('success', 'Regulasi berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/regulasi')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }

        $regulasi = $this->regulasiModel->find($id);
        if (!$regulasi) {
            return redirect()->to('/regulasi')->with('error', 'Data regulasi tidak ditemukan.');
        }

        $this->regulasiModel->delete($id);

        return redirect()->to('/regulasi')->with('success', 'Regulasi berhasil dihapus.');
    }
}
