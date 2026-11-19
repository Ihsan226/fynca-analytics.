<?php

namespace App\Controllers;

use App\Models\ParameterRasioModel;

class ParameterRasio extends BaseController
{
    protected $parameterModel;

    public function __construct()
    {
        $this->parameterModel = new ParameterRasioModel();
    }

    public function index()
    {
        $all = $this->parameterModel->orderBy('id', 'ASC')->findAll();

        // Split into analytical and regulatory
        $analytical = [];
        $regulatory = [];
        foreach ($all as $p) {
            if (($p['tipe'] ?? 'analytical') === 'regulatory') {
                $regulatory[] = $p;
            } else {
                $analytical[] = $p;
            }
        }

        $data = [
            'title'      => 'Tax Parameters',
            'parameters' => $all,
            'analytical' => $analytical,
            'regulatory' => $regulatory,
        ];
        return view('parameter_rasio/index', $data);
    }

    public function store()
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/parameter-rasio')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }
        
        $this->parameterModel->save([
            'nama'           => $this->request->getPost('nama'),
            'variabel'       => $this->request->getPost('variabel'),
            'batas_bawah'    => $this->request->getPost('batas_bawah'),
            'batas_atas'     => $this->request->getPost('batas_atas'),
            'keterangan'     => $this->request->getPost('keterangan'),
            'warna'          => $this->request->getPost('warna'),
            'tipe'           => $this->request->getPost('tipe') ?? 'analytical',
            'regulasi_ref'   => $this->request->getPost('regulasi_ref'),
            'regulasi_url'   => $this->request->getPost('regulasi_url'),
            'berlaku_sejak'  => $this->request->getPost('berlaku_sejak') ?: null,
            'berlaku_hingga' => $this->request->getPost('berlaku_hingga') ?: null,
        ]);

        return redirect()->to('/parameter-rasio')->with('success', 'Parameter baru berhasil ditambahkan.');
    }

    public function update($id)
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/parameter-rasio')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }
        
        $parameter = $this->parameterModel->find($id);

        if (!$parameter) {
            return redirect()->to('/parameter-rasio')->with('error', 'Data parameter tidak ditemukan.');
        }

        $this->parameterModel->update($id, [
            'nama'           => $this->request->getPost('nama'),
            'variabel'       => $this->request->getPost('variabel'),
            'batas_bawah'    => $this->request->getPost('batas_bawah'),
            'batas_atas'     => $this->request->getPost('batas_atas'),
            'keterangan'     => $this->request->getPost('keterangan'),
            'warna'          => $this->request->getPost('warna'),
            'tipe'           => $this->request->getPost('tipe') ?? 'analytical',
            'regulasi_ref'   => $this->request->getPost('regulasi_ref'),
            'regulasi_url'   => $this->request->getPost('regulasi_url'),
            'berlaku_sejak'  => $this->request->getPost('berlaku_sejak') ?: null,
            'berlaku_hingga' => $this->request->getPost('berlaku_hingga') ?: null,
        ]);

        return redirect()->to('/parameter-rasio')->with('success', 'Parameter berhasil diperbarui.');
    }

    public function delete($id)
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/parameter-rasio')->with('error', 'Akses ditolak. Analyst hanya memiliki akses lihat (Read-Only).');
        }

        $parameter = $this->parameterModel->find($id);

        if (!$parameter) {
            return redirect()->to('/parameter-rasio')->with('error', 'Data parameter tidak ditemukan.');
        }

        $this->parameterModel->delete($id);

        return redirect()->to('/parameter-rasio')->with('success', 'Parameter berhasil dihapus.');
    }
}
