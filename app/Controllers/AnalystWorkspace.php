<?php
namespace App\Controllers;

use App\Models\PerusahaanModel;
use App\Models\LaporanKeuanganModel;
use App\Models\HasilAnalisisModel;
use App\Models\AnalystReportModel;
use App\Libraries\TaxExplanationEngine;

class AnalystWorkspace extends BaseController
{
    protected $reportModel;
    protected $perusahaanModel;
    protected $laporanModel;

    public function __construct()
    {
        $this->reportModel     = new AnalystReportModel();
        $this->perusahaanModel = new PerusahaanModel();
        $this->laporanModel    = new LaporanKeuanganModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        
        $userId = session()->get('id_user');

        $reports = $db->table('laporan_keuangan lk')
            ->select('lk.id_laporan, lk.tahun, lk.status as lk_status, p.id_perusahaan, p.nama_perusahaan, p.kode_saham, p.assigned_analyst as company_analyst_id, ar.id as id_analysis, ar.status as analysis_status, ar.id_user as assigned_analyst_id, u.nama_lengkap as assigned_analyst, u2.nama_lengkap as company_analyst_name, ha.val_etr, ha.val_rpt_ratio, ha.val_der, ha.val_sales_growth')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->join('analyst_reports ar', 'ar.id_laporan = lk.id_laporan AND ar.id_user = ' . $db->escape($userId), 'left')
            ->join('users u', 'u.id_user = ar.id_user', 'left') // Analyst from report
            ->join('users u2', 'u2.id_user = p.assigned_analyst', 'left') // Analyst assigned to company
            ->join('hasil_analisis ha', 'ha.id_laporan = lk.id_laporan', 'left')
            ->whereIn('lk.status', ['Sent to Analyst', 'Processing', 'Analysis Completed', 'Revision Required', 'Approved'])
            ->groupStart()
                ->where('p.assigned_analyst', $userId)
                ->orWhere('p.assigned_analyst', null)
                ->orWhere('p.assigned_analyst', 0)
                ->orWhere('p.assigned_analyst', '')
            ->groupEnd()
            // Ensure we don't show reports in the pool that someone else has already started working on
            ->where("NOT EXISTS (
                SELECT 1 FROM analyst_reports ar_other 
                WHERE ar_other.id_laporan = lk.id_laporan 
                AND ar_other.id_user != " . $db->escape($userId) . "
            )")
            // NEW: Ensure we don't show reports that the current user has already started working on
            ->where('ar.id IS NULL')
            ->orderBy('lk.tahun', 'DESC')
            ->orderBy('p.nama_perusahaan', 'ASC')
            ->get()->getResultArray();

        return view('analyst_workspace/index', compact('reports'));
    }

    public function analyze($idPerusahaan, $tahun)
    {
        $db = \Config\Database::connect();

        $perusahaan = $this->perusahaanModel->find($idPerusahaan);
        if (!$perusahaan) return redirect()->to('/workspace')->with('error', 'Client tidak ditemukan.');

        // Get financial data + analysis
        $row = $db->table('hasil_analisis ha')
            ->select('ha.*, lk.*, p.nama_perusahaan, p.kode_saham, p.sektor, p.has_related_party, p.has_rpt_transaction, p.has_interest_debt, p.is_public_company')
            ->join('laporan_keuangan lk', 'lk.id_laporan = ha.id_laporan')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->where('lk.id_perusahaan', $idPerusahaan)
            ->where('lk.tahun', $tahun)
            ->get()->getRowArray();

        if (!$row) return redirect()->to('/workspace')->with('error', 'Data analisis untuk periode tersebut tidak ditemukan.');

        // Generate explanations
        $engine = new TaxExplanationEngine();
        $explanations = [
            'etr'    => $engine->explainETR((float)($row['val_etr'] ?? 0)),
            'rpt'    => $engine->explainRPT((float)($row['val_rpt_ratio'] ?? 0), $row),
            'der'    => $engine->explainDER((float)($row['val_der'] ?? 0)),
            'growth' => $engine->explainSalesGrowth((float)($row['val_sales_growth'] ?? 0), $row),
        ];

        // Check if draft already exists for this analyst + company + year
        $existingDraft = $this->reportModel
            ->where('id_user', session()->get('id_user'))
            ->where('id_perusahaan', $idPerusahaan)
            ->where('tahun', $tahun)
            ->where('status', 'draft')
            ->first();

        // Get applicable regulations
        $regulasi = $db->table('regulasi_referensi')
            ->where('status', 'active')
            ->orderBy('indikator_terkait', 'ASC')
            ->get()->getResultArray();

        return view('analyst_workspace/analyze', compact(
            'row', 'perusahaan', 'tahun', 'explanations', 'existingDraft', 'regulasi'
        ));
    }

    public function store()
    {
        $data = [
            'id_user'          => session()->get('id_user'),
            'id_perusahaan'    => $this->request->getPost('id_perusahaan'),
            'id_laporan'       => $this->request->getPost('id_laporan'),
            'id_analisis'      => $this->request->getPost('id_analisis'),
            'tahun'            => $this->request->getPost('tahun'),
            'judul'            => $this->request->getPost('judul'),
            'jenis_analisis'   => $this->request->getPost('jenis_analisis') ?? 'tax_indicator',
            'analyst_insight'  => $this->request->getPost('analyst_insight'),
            'recommendation'   => $this->request->getPost('recommendation'),
            'note_etr'         => $this->request->getPost('note_etr'),
            'note_rpt'         => $this->request->getPost('note_rpt'),
            'note_der'         => $this->request->getPost('note_der'),
            'note_growth'      => $this->request->getPost('note_growth'),
            'status'           => $this->request->getPost('status') ?? 'draft',
        ];

        $this->reportModel->insert($data);

        // Sync status to laporan_keuangan
        $lkStatus = $data['status'] === 'submitted' ? 'Analysis Completed' : 'Processing';
        $this->laporanModel->update($data['id_laporan'], ['status' => $lkStatus]);

        // --- NOTIFICATION ---
        if ($data['status'] === 'submitted') {
            $perusahaan = $this->perusahaanModel->find($data['id_perusahaan']);
            $notifModel = new \App\Models\NotificationModel();
            $notifModel->insert([
                'target_role'    => 'owner', // Or admin, assuming Owner reviews it
                'target_user_id' => null,
                'title'          => 'Analisis Menunggu Persetujuan',
                'message'        => 'Laporan analisis untuk klien ' . ($perusahaan['nama_perusahaan'] ?? 'Unknown') . ' telah di-submit oleh Analyst dan menunggu review Anda.',
                'link'           => '/pending-reviews'
            ]);
        }

        $msg = $data['status'] === 'submitted' ? 'Analisis berhasil di-submit untuk review.' : 'Analisis berhasil disimpan sebagai draft.';
        $aksi = $data['status'] === 'submitted' ? 'Submit Analysis' : 'Start/Save Draft';
        
        log_activity($aksi, "Analyst menyimpan/mengirim laporan ID: " . $data['id_laporan'], 'system');
        
        return redirect()->to('/my-analyses')->with('success', $msg);
    }

    public function edit($id)
    {
        $report = $this->reportModel->find($id);
        if (!$report || $report['id_user'] != session()->get('id_user')) {
            return redirect()->to('/my-analyses')->with('error', 'Data tidak ditemukan.');
        }

        $perusahaan = $this->perusahaanModel->find($report['id_perusahaan']);

        $db = \Config\Database::connect();
        $row = $db->table('hasil_analisis ha')
            ->select('ha.*, lk.*, p.nama_perusahaan, p.kode_saham, p.sektor, p.has_related_party, p.has_rpt_transaction')
            ->join('laporan_keuangan lk', 'lk.id_laporan = ha.id_laporan')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->where('lk.id_perusahaan', $report['id_perusahaan'])
            ->where('lk.tahun', $report['tahun'])
            ->get()->getRowArray();

        $engine = new TaxExplanationEngine();
        $explanations = [
            'etr'    => $engine->explainETR((float)($row['val_etr'] ?? 0)),
            'rpt'    => $engine->explainRPT((float)($row['val_rpt_ratio'] ?? 0), $row),
            'der'    => $engine->explainDER((float)($row['val_der'] ?? 0)),
            'growth' => $engine->explainSalesGrowth((float)($row['val_sales_growth'] ?? 0), $row),
        ];

        return view('analyst_workspace/edit', compact('report', 'row', 'perusahaan', 'explanations'));
    }

    public function update($id)
    {
        $report = $this->reportModel->find($id);
        if (!$report || $report['id_user'] != session()->get('id_user')) {
            return redirect()->to('/my-analyses')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'judul'           => $this->request->getPost('judul'),
            'jenis_analisis'  => $this->request->getPost('jenis_analisis') ?? $report['jenis_analisis'],
            'analyst_insight' => $this->request->getPost('analyst_insight'),
            'recommendation'  => $this->request->getPost('recommendation'),
            'note_etr'        => $this->request->getPost('note_etr'),
            'note_rpt'        => $this->request->getPost('note_rpt'),
            'note_der'        => $this->request->getPost('note_der'),
            'note_growth'     => $this->request->getPost('note_growth'),
            'status'          => $this->request->getPost('status') ?? 'draft',
        ];

        $this->reportModel->update($id, $data);

        // Sync status to laporan_keuangan
        $lkStatus = $data['status'] === 'submitted' ? 'Analysis Completed' : 'Processing';
        $this->laporanModel->update($report['id_laporan'], ['status' => $lkStatus]);

        // --- NOTIFICATION ---
        if ($data['status'] === 'submitted') {
            $perusahaan = $this->perusahaanModel->find($report['id_perusahaan']);
            $notifModel = new \App\Models\NotificationModel();
            $notifModel->insert([
                'target_role'    => 'owner',
                'target_user_id' => null,
                'title'          => 'Analisis Menunggu Persetujuan',
                'message'        => 'Laporan analisis untuk klien ' . ($perusahaan['nama_perusahaan'] ?? 'Unknown') . ' telah di-submit oleh Analyst dan menunggu review Anda.',
                'link'           => '/pending-reviews'
            ]);
        }

        $msg = $data['status'] === 'submitted' ? 'Analisis berhasil di-submit untuk review.' : 'Draft analisis berhasil diperbarui.';
        $aksi = $data['status'] === 'submitted' ? 'Submit Analysis' : 'Update Draft';
        
        log_activity($aksi, "Analyst mengupdate/mengirim laporan ID: " . $report['id_laporan'], 'system');

        return redirect()->to('/my-analyses')->with('success', $msg);
    }

    public function submit($id)
    {
        $report = $this->reportModel->find($id);
        if (!$report || $report['id_user'] != session()->get('id_user')) {
            return redirect()->to('/my-analyses')->with('error', 'Data tidak ditemukan.');
        }

        $this->reportModel->update($id, ['status' => 'submitted']);

        // --- NOTIFICATION ---
        $perusahaan = $this->perusahaanModel->find($report['id_perusahaan']);
        $notifModel = new \App\Models\NotificationModel();
        $notifModel->insert([
            'target_role'    => 'owner',
            'target_user_id' => null,
            'title'          => 'Analisis Menunggu Persetujuan',
            'message'        => 'Laporan analisis untuk klien ' . ($perusahaan['nama_perusahaan'] ?? 'Unknown') . ' telah di-submit oleh Analyst dan menunggu review Anda.',
            'link'           => '/pending-reviews'
        ]);

        log_activity('Submit Analysis', "Analyst men-submit laporan ID: " . $report['id_laporan'], 'system');

        return redirect()->to('/my-analyses')->with('success', 'Analisis berhasil di-submit untuk review Owner.');
    }

    public function myAnalyses()
    {
        $db = \Config\Database::connect();
        $reports = $db->table('analyst_reports ar')
            ->select('ar.*, p.nama_perusahaan, p.kode_saham, u.nama_lengkap as analyst_name')
            ->join('perusahaan p', 'p.id_perusahaan = ar.id_perusahaan')
            ->join('users u', 'u.id_user = ar.id_user')
            ->where('ar.id_user', session()->get('id_user'))
            ->orderBy('ar.updated_at', 'DESC')
            ->get()->getResultArray();

        return view('analyst_workspace/my_analyses', compact('reports'));
    }

    public function reviewStatus()
    {
        $db = \Config\Database::connect();
        $reports = $db->table('analyst_reports ar')
            ->select('ar.*, p.nama_perusahaan, p.kode_saham')
            ->join('perusahaan p', 'p.id_perusahaan = ar.id_perusahaan')
            ->where('ar.id_user', session()->get('id_user'))
            ->whereIn('ar.status', ['submitted', 'revision'])
            ->orderBy('ar.updated_at', 'DESC')
            ->get()->getResultArray();

        return view('analyst_workspace/review_status', compact('reports'));
    }
}
