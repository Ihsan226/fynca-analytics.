<?php
namespace App\Controllers;

use App\Models\PerusahaanModel;
use App\Models\LaporanKeuanganModel;
use App\Models\HasilAnalisisModel;
use App\Models\AnalystReportModel;

class OwnerConsole extends BaseController
{
    public function financialOverview()
    {
        $db = \Config\Database::connect();
        
        $laporan = $db->table('laporan_keuangan lk')
            ->select('lk.*, p.nama_perusahaan, p.kode_saham, p.sektor')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->orderBy('lk.tahun', 'DESC')
            ->get()->getResultArray();

        $summary = $db->table('laporan_keuangan lk')
            ->select('
                SUM(lk.penjualan_tahun_ini) as total_revenue,
                SUM(lk.laba_sebelum_pajak) as total_laba,
                SUM(lk.total_hutang) as total_debt,
                SUM(lk.total_ekuitas) as total_equity
            ')
            ->get()->getRow();

        return view('owner_console/financial_overview', compact('laporan', 'summary'));
    }

    public function taxOverview()
    {
        $db = \Config\Database::connect();
        
        $analisis = $db->table('hasil_analisis ha')
            ->select('ha.*, lk.tahun, lk.beban_pajak, lk.laba_sebelum_pajak, p.nama_perusahaan, p.kode_saham')
            ->join('laporan_keuangan lk', 'lk.id_laporan = ha.id_laporan')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->orderBy('lk.tahun', 'DESC')
            ->get()->getResultArray();

        return view('owner_console/tax_overview', compact('analisis'));
    }

    public function analysisResults()
    {
        $db = \Config\Database::connect();
        
        $analisis = $db->table('hasil_analisis ha')
            ->select('ha.*, lk.tahun, p.nama_perusahaan, p.kode_saham')
            ->join('laporan_keuangan lk', 'lk.id_laporan = ha.id_laporan')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->orderBy('lk.tahun', 'DESC')
            ->get()->getResultArray();

        return view('owner_console/analysis_results', compact('analisis'));
    }

    public function riskCompliance()
    {
        $db = \Config\Database::connect();
        $engine = new \App\Libraries\TaxExplanationEngine();
        
        $all_analyses = $db->table('hasil_analisis ha')
            ->select('ha.*, lk.tahun, p.nama_perusahaan, p.kode_saham')
            ->join('laporan_keuangan lk', 'lk.id_laporan = ha.id_laporan')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->orderBy('lk.tahun', 'DESC')
            ->get()->getResultArray();

        foreach ($all_analyses as &$r) {
            $score = 0;
            $exps = [
                $engine->explainETR((float)$r['val_etr']),
                $engine->explainRPT((float)$r['val_rpt_ratio'], $r),
                $engine->explainDER((float)$r['val_der']),
                $engine->explainSalesGrowth((float)$r['val_sales_growth'], $r),
            ];
            foreach ($exps as $exp) {
                if (in_array($exp['level'], ['high', 'negative'])) $score += 10;
                elseif (in_array($exp['level'], ['low', 'positive', 'stable', 'normal'])) $score += 25;
                else $score += 15;
            }
            $r['risk_score'] = min(100, max(0, $score));
            
            if ($r['risk_score'] >= 80) $r['risk_label'] = 'LOW RISK';
            elseif ($r['risk_score'] >= 60) $r['risk_label'] = 'MODERATE';
            else $r['risk_label'] = 'HIGH RISK';
            
            $r['explanations'] = $exps;
        }

        return view('owner_console/risk_compliance', compact('all_analyses'));
    }

    public function recommendations()
    {
        $db = \Config\Database::connect();
        
        $reports = $db->table('analyst_reports ar')
            ->select('ar.*, p.nama_perusahaan, p.kode_saham, u.nama_lengkap as analyst_name')
            ->join('perusahaan p', 'p.id_perusahaan = ar.id_perusahaan')
            ->join('users u', 'u.id_user = ar.id_user')
            ->where('ar.status', 'approved')
            ->orderBy('ar.updated_at', 'DESC')
            ->get()->getResultArray();

        return view('owner_console/recommendations', compact('reports'));
    }

    public function pendingReviews()
    {
        $db = \Config\Database::connect();
        
        $reports = $db->table('analyst_reports ar')
            ->select('ar.*, p.nama_perusahaan, p.kode_saham, u.nama_lengkap as analyst_name, ha.val_etr, ha.val_rpt_ratio, ha.val_der, ha.val_sales_growth')
            ->join('perusahaan p', 'p.id_perusahaan = ar.id_perusahaan')
            ->join('users u', 'u.id_user = ar.id_user')
            ->join('hasil_analisis ha', 'ha.id_analisis = ar.id_analisis')
            ->where('ar.status', 'submitted')
            ->orderBy('ar.updated_at', 'DESC')
            ->get()->getResultArray();

        return view('owner_console/pending_reviews', compact('reports'));
    }

    public function approvedAnalyses()
    {
        $db = \Config\Database::connect();
        
        $reports = $db->table('analyst_reports ar')
            ->select('ar.*, p.nama_perusahaan, p.kode_saham, u.nama_lengkap as analyst_name')
            ->join('perusahaan p', 'p.id_perusahaan = ar.id_perusahaan')
            ->join('users u', 'u.id_user = ar.id_user')
            ->where('ar.status', 'approved')
            ->orderBy('ar.reviewed_at', 'DESC')
            ->get()->getResultArray();

        return view('owner_console/approved_analyses', compact('reports'));
    }

    public function reviewReport($id)
    {
        $reportModel = new AnalystReportModel();
        
        $action = $this->request->getPost('action');
        $comment = $this->request->getPost('reviewer_comment');
        
        $status = ($action === 'approve') ? 'approved' : 'revision';
        
        $reportModel->update($id, [
            'status' => $status,
            'reviewer_id' => session()->get('id_user'),
            'reviewer_comment' => $comment,
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);
        
        // Sync status to laporan_keuangan
        $report = $reportModel->find($id);
        if ($report) {
            $laporanModel = new \App\Models\LaporanKeuanganModel();
            $lkStatus = ($status === 'approved') ? 'Approved' : 'Revision Required';
            $laporanModel->update($report['id_laporan'], ['status' => $lkStatus]);
            
            // --- NOTIFICATION ---
            $perusahaanModel = new \App\Models\PerusahaanModel();
            $perusahaan = $perusahaanModel->find($report['id_perusahaan']);
            $namaPerusahaan = $perusahaan['nama_perusahaan'] ?? 'Unknown';

            $notifModel = new \App\Models\NotificationModel();
            $title = ($action === 'approve') ? 'Analisis Disetujui' : 'Revisi Analisis Diperlukan';
            $message = ($action === 'approve') 
                ? 'Laporan analisis untuk klien ' . $namaPerusahaan . ' telah disetujui oleh Owner.' 
                : 'Laporan analisis untuk klien ' . $namaPerusahaan . ' ditolak dan memerlukan revisi.';

            $notifModel->insert([
                'target_role'    => null,
                'target_user_id' => $report['id_user'], // The analyst who submitted it
            ]);
        }

        $msg = ($action === 'approve') ? 'Report successfully approved.' : 'Report sent back for revision.';
        $aksi = ($action === 'approve') ? 'Approve Laporan' : 'Reject Laporan';
        
        log_activity($aksi, "Owner me-review (".$action.") laporan ID: " . $report['id_laporan'], 'system');
        
        return redirect()->to('/pending-reviews')->with('success', $msg);
    }
}
