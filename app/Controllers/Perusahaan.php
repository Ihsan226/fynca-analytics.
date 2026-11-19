<?php
namespace App\Controllers;

use App\Models\PerusahaanModel;
use App\Models\SektorModel;
use App\Models\UserModel;

class Perusahaan extends BaseController
{
    protected $perusahaanModel;
    protected $sektorModel;
    protected $userModel;

    public function __construct()
    {
        $this->perusahaanModel = new PerusahaanModel();
        $this->sektorModel = new SektorModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $perusahaanQuery = $this->perusahaanModel;
        $perusahaanList = $perusahaanQuery->orderBy('id_perusahaan', 'DESC')->findAll();

        $analysts = $this->userModel->where('role', 'analyst')->findAll();
        $analystMap = [];
        foreach ($analysts as $a) {
            $analystMap[$a['id_user']] = $a['nama_lengkap'];
        }

        $data = [
            'perusahaan' => $perusahaanList,
            'analystMap' => $analystMap
        ];
        
        return view('perusahaan/index', $data);
    }

    // Menampilkan form tambah perusahaan
    public function create()
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/perusahaan')->with('error', 'Analyst tidak diizinkan menambah klien baru.');
        }

        $data = [
            'sektors' => $this->sektorModel->orderBy('nama', 'ASC')->findAll(),
            'analysts' => session('role') === 'admin' ? $this->userModel->where('role', 'analyst')->findAll() : []
        ];
        return view('perusahaan/create', $data);
    }

    public function store()
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/perusahaan')->with('error', 'Analyst tidak diizinkan menambah klien baru.');
        }

        $assignedAnalyst = $this->request->getPost('assigned_analyst') ?: null;

        $this->perusahaanModel->save([
            'kode_saham'         => $this->request->getPost('kode_saham'),
            'nama_perusahaan'    => $this->request->getPost('nama_perusahaan'),
            'sektor'             => $this->request->getPost('sektor'),
            'tahun_ipo'          => $this->request->getPost('tahun_ipo'),
            'assigned_analyst'   => $assignedAnalyst,
            // Tax Profile (Applicability Engine)
            'has_related_party'  => $this->request->getPost('has_related_party') ?? 0,
            'has_rpt_transaction'=> $this->request->getPost('has_rpt_transaction') ?? 0,
            'has_cross_border'   => $this->request->getPost('has_cross_border') ?? 0,
            'has_interest_debt'  => $this->request->getPost('has_interest_debt') ?? 0,
            'is_public_company'  => $this->request->getPost('is_public_company') ?? 0,
        ]);

        log_activity('Klien Baru', 'Menambahkan data klien baru: ' . $this->request->getPost('nama_perusahaan'), 'create');

        return redirect()->to('/perusahaan')->with('success', 'Data Perusahaan berhasil ditambahkan.');
    }
    
    // Menampilkan form edit perusahaan
    public function edit($id)
    {
        $perusahaan = $this->perusahaanModel->find($id);

        if (!$perusahaan) {
            return redirect()->to('/perusahaan')->with('error', 'Data tidak ditemukan.');
        }

        if (session('role') === 'analyst' && $perusahaan['assigned_analyst'] != session('id_user')) {
            return redirect()->to('/perusahaan')->with('error', 'Anda tidak memiliki akses ke klien ini.');
        }

        $data = [
            'perusahaan' => $perusahaan,
            'sektors' => $this->sektorModel->orderBy('nama', 'ASC')->findAll(),
            'analysts' => session('role') === 'admin' ? $this->userModel->where('role', 'analyst')->findAll() : []
        ];

        return view('perusahaan/edit', $data);
    }

    // Memproses update data perusahaan
    public function update($id)
    {
        $perusahaan = $this->perusahaanModel->find($id);
        if (!$perusahaan) return redirect()->to('/perusahaan')->with('error', 'Data tidak ditemukan.');

        if (session('role') === 'analyst' && $perusahaan['assigned_analyst'] != session('id_user')) {
            return redirect()->to('/perusahaan')->with('error', 'Anda tidak memiliki akses ke klien ini.');
        }

        $updateData = [
            'kode_saham'         => $this->request->getPost('kode_saham'),
            'nama_perusahaan'    => $this->request->getPost('nama_perusahaan'),
            'sektor'             => $this->request->getPost('sektor'),
            'tahun_ipo'          => $this->request->getPost('tahun_ipo'),
            // Tax Profile (Applicability Engine)
            'has_related_party'  => $this->request->getPost('has_related_party') ?? 0,
            'has_rpt_transaction'=> $this->request->getPost('has_rpt_transaction') ?? 0,
            'has_cross_border'   => $this->request->getPost('has_cross_border') ?? 0,
            'has_interest_debt'  => $this->request->getPost('has_interest_debt') ?? 0,
            'is_public_company'  => $this->request->getPost('is_public_company') ?? 0,
        ];

        if (session('role') === 'admin') {
            $updateData['assigned_analyst'] = $this->request->getPost('assigned_analyst') ?: null;
        }

        $this->perusahaanModel->update($id, $updateData);

        log_activity('Update Klien', 'Memperbarui profil klien: ' . $this->request->getPost('nama_perusahaan'), 'update');

        return redirect()->to('/perusahaan')->with('success', 'Data Perusahaan berhasil diupdate.');
    }

    // Menghapus data perusahaan
    public function delete($id)
    {
        if (session('role') === 'analyst') {
            return redirect()->to('/perusahaan')->with('error', 'Analyst tidak diizinkan menghapus klien.');
        }

        $this->perusahaanModel->delete($id);
        // Catatan: Karena di database sudah kita set ON DELETE CASCADE, 
        // menghapus perusahaan otomatis menghapus semua laporan & hasil analisisnya.
        return redirect()->to('/perusahaan')->with('success', 'Data Perusahaan berhasil dihapus.');
    }
}