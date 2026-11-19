<?php
namespace App\Controllers;

use App\Models\PerusahaanModel;
use App\Models\LaporanKeuanganModel;
use App\Models\HasilAnalisisModel;
use App\Models\AnalystReportModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $role = session()->get('role') ?? 'admin';

        if ($role === 'analyst') {
            return $this->analystDashboard();
        } elseif ($role === 'owner') {
            return $this->ownerDashboard();
        } elseif ($role === 'staff') {
            return $this->staffDashboard();
        } elseif ($role === 'bendahara') {
            return redirect()->to('/finance/dashboard');
        } elseif ($role === 'akuntansi') {
            return redirect()->to('/accounting/dashboard');
        }

        return $this->adminDashboard();
    }

    // ═══════════════════════════════════════════════════
    // ADMIN DASHBOARD (existing logic)
    // ═══════════════════════════════════════════════════
    private function adminDashboard()
    {
        $db = \Config\Database::connect();

        // System Metrics
        $total_users = $db->table('users')->countAll();
        $total_sektor = $db->table('sektor')->countAll();
        $total_parameter = $db->table('parameter_rasio')->countAll();
        
        $today = date('Y-m-d');
        $total_aktivitas_hari_ini = $db->table('log_aktivitas')
            ->where('DATE(created_at)', $today)
            ->countAllResults();

        // Chart: Login Activity/System Usage (last 7 days)
        $chart_data = [];
        $chart_labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chart_labels[] = date('d M', strtotime($date));
            $count = $db->table('log_aktivitas')
                ->where('DATE(created_at)', $date)
                ->countAllResults();
            $chart_data[] = $count;
        }

        // Recent Activity Logs (limit 10)
        $recent_logs = $db->table('log_aktivitas a')
            ->select('a.*, u.nama_lengkap, u.role')
            ->join('users u', 'u.nama_lengkap = a.user', 'left')
            ->orderBy('a.created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        // User Roles for Pie Chart
        $user_roles = $db->table('users')
            ->select('role, COUNT(id_user) as count')
            ->groupBy('role')
            ->get()->getResultArray();

        // Critical Activity Logs (limit 5)
        $critical_logs = $db->table('log_aktivitas a')
            ->select('a.*, u.nama_lengkap, u.role')
            ->join('users u', 'u.nama_lengkap = a.user', 'left')
            ->where('a.tipe !=', 'auth')
            ->orderBy('a.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // Page header info (rendered in topbar)
        $page_title = 'System Administrator Dashboard';
        $page_subtitle = 'Selamat datang, ' . session()->get('nama_lengkap') . '. Berikut ringkasan operasional sistem.';
        $page_badge = 'System Online';

        return view('dashboard/admin', compact(
            'total_users', 'total_sektor', 'total_parameter', 'total_aktivitas_hari_ini',
            'chart_labels', 'chart_data', 'recent_logs', 'user_roles', 'critical_logs',
            'page_title', 'page_subtitle', 'page_badge'
        ));
    }

    // ═══════════════════════════════════════════════════
    // ANALYST DASHBOARD
    // ═══════════════════════════════════════════════════
    private function analystDashboard()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id_user');

        $laporanModel = new LaporanKeuanganModel();
        $reportModel = new AnalystReportModel();

        // Pending Analysis = Laporan yang di-assign tapi belum dianalisis (ar.id is null)
        $pending = $db->table('laporan_keuangan lk')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->join('analyst_reports ar', 'ar.id_laporan = lk.id_laporan AND ar.id_user = ' . $db->escape($userId), 'left')
            ->whereIn('lk.status', ['Sent to Analyst', 'Processing', 'Analysis Completed', 'Revision Required', 'Approved'])
            ->groupStart()
                ->where('p.assigned_analyst', $userId)
                ->orWhere('p.assigned_analyst', null)
                ->orWhere('p.assigned_analyst', 0)
                ->orWhere('p.assigned_analyst', '')
            ->groupEnd()
            ->where("NOT EXISTS (
                SELECT 1 FROM analyst_reports ar_other 
                WHERE ar_other.id_laporan = lk.id_laporan 
                AND ar_other.id_user != " . $db->escape($userId) . "
            )")
            ->where('ar.id IS NULL')
            ->countAllResults();
        
        // Analysis Report Status Counts for this analyst
        $draft     = $reportModel->where('id_user', $userId)->where('status', 'draft')->countAllResults();
        $submitted = $reportModel->where('id_user', $userId)
                                 ->whereIn('status', ['submitted', 'revision'])
                                 ->countAllResults();
        $completed = $reportModel->where('id_user', $userId)
                                 ->whereIn('status', ['approved', 'completed'])
                                 ->countAllResults();

        // My Work (Recent reports available)
        $my_work = $db->table('laporan_keuangan lk')
            ->select('lk.id_laporan, lk.tahun, lk.status as lk_status, p.id_perusahaan, p.nama_perusahaan, p.kode_saham, ar.id as id_analysis, ar.status as analysis_status, lk.updated_at')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->join('analyst_reports ar', 'ar.id_laporan = lk.id_laporan AND ar.id_user = ' . $db->escape($userId), 'left')
            ->whereIn('lk.status', ['Sent to Analyst', 'Processing', 'Analysis Completed', 'Revision Required', 'Approved'])
            ->groupStart()
                ->where('p.assigned_analyst', $userId)
                ->orWhere('p.assigned_analyst', null)
                ->orWhere('p.assigned_analyst', 0)
                ->orWhere('p.assigned_analyst', '')
            ->groupEnd()
            ->where("NOT EXISTS (
                SELECT 1 FROM analyst_reports ar_other 
                WHERE ar_other.id_laporan = lk.id_laporan 
                AND ar_other.id_user != " . $db->escape($userId) . "
            )")
            ->orderBy('lk.updated_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        // Quick stats from analysis data (from all ready reports)
        $avg_row = $db->table('hasil_analisis ha')
            ->select('AVG(val_etr) as avg_etr, AVG(val_rpt_ratio) as avg_rpt, AVG(val_der) as avg_der, AVG(val_sales_growth) as avg_growth')
            ->join('laporan_keuangan lk', 'lk.id_laporan = ha.id_laporan')
            ->whereIn('lk.status', ['Sent to Analyst', 'Processing', 'Analysis Completed', 'Revision Required', 'Approved'])
            ->get()->getRow();

        // Total clients globally since they can analyze any
        $total_clients = (new PerusahaanModel())->countAll();
        $total_reports = $laporanModel->countAll();

        // ── Analysis Trend: per-year indicators ──
        $trend_data = $db->table('hasil_analisis ha')
            ->select('lk.tahun, AVG(ha.val_etr) as avg_etr, AVG(ha.val_rpt_ratio) as avg_rpt, AVG(ha.val_der) as avg_der, AVG(ha.val_sales_growth) as avg_growth')
            ->join('laporan_keuangan lk', 'lk.id_laporan = ha.id_laporan')
            ->groupBy('lk.tahun')
            ->orderBy('lk.tahun', 'ASC')
            ->get()->getResultArray();

        // ── Latest Indicators (most recent year) & Previous Period for comparison ──
        $latest_indicators = null;
        $prev_indicators = null;
        if (!empty($trend_data)) {
            $latest_indicators = end($trend_data);
            if (count($trend_data) >= 2) {
                $prev_indicators = $trend_data[count($trend_data) - 2];
            }
        }

        // ── Recent Activity for this analyst ──
        $recent_activity = $db->table('log_aktivitas')
            ->where('user', session()->get('nama_lengkap'))
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get()->getResultArray();

        // Page header info (rendered in topbar)
        $page_title = 'Analysis Dashboard';
        $page_subtitle = 'Selamat datang, ' . session()->get('nama_lengkap') . '. Berikut ringkasan pekerjaan analisis Anda.';

        return view('dashboard/analyst', compact(
            'draft', 'submitted', 'completed', 'pending',
            'my_work', 'avg_row', 'total_clients', 'total_reports',
            'trend_data', 'latest_indicators', 'prev_indicators', 'recent_activity',
            'page_title', 'page_subtitle'
        ));
    }

    // ═══════════════════════════════════════════════════
    // OWNER DASHBOARD (Executive)
    // ═══════════════════════════════════════════════════
    private function ownerDashboard()
    {
        $db = \Config\Database::connect();
        $reportModel = new AnalystReportModel();

        // Company financial summary
        $financial_summary = $db->table('laporan_keuangan lk')
            ->select('
                SUM(lk.penjualan_tahun_ini) as total_revenue,
                SUM(lk.laba_sebelum_pajak) as total_laba,
                SUM(lk.total_hutang) as total_debt,
                SUM(lk.total_ekuitas) as total_equity,
                SUM(lk.beban_pajak) as total_beban_pajak
            ')
            ->get()->getRow();

        // Tax indicators average
        $avg_row = $db->table('hasil_analisis')
            ->select('AVG(val_etr) as avg_etr, AVG(val_rpt_ratio) as avg_rpt, AVG(val_der) as avg_der, AVG(val_sales_growth) as avg_growth, COUNT(*) as total')
            ->get()->getRow();

        // Risk breakdown
        $engine = new \App\Libraries\TaxExplanationEngine();
        $all_analyses = $db->table('hasil_analisis ha')
            ->select('ha.*, lk.tahun, p.nama_perusahaan, p.kode_saham')
            ->join('laporan_keuangan lk', 'lk.id_laporan = ha.id_laporan')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->get()->getResultArray();

        $risk_low = $risk_moderate = $risk_high = 0;
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
            if ($r['risk_score'] >= 80) { $r['risk_label'] = 'EXCELLENT'; $risk_low++; }
            elseif ($r['risk_score'] >= 60) { $r['risk_label'] = 'MODERATE'; $risk_moderate++; }
            else { $r['risk_label'] = 'HIGH RISK'; $risk_high++; }
        }

        // Workflow stats
        $pending_reviews = $reportModel->where('status', 'submitted')->countAllResults(false);
        $total_approved  = $reportModel->where('status', 'approved')->countAllResults(false);
        $need_attention  = $risk_high;

        // Recent submitted analyses for review
        $recent_submitted = $db->table('analyst_reports ar')
            ->select('ar.*, p.nama_perusahaan, p.kode_saham, u.nama_lengkap as analyst_name')
            ->join('perusahaan p', 'p.id_perusahaan = ar.id_perusahaan')
            ->join('users u', 'u.id_user = ar.id_user')
            ->where('ar.status', 'submitted')
            ->orderBy('ar.updated_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $total_clients = (new PerusahaanModel())->countAll();

        // Page header info (rendered in topbar)
        $page_title = 'Executive Dashboard';
        $page_subtitle = 'Ringkasan eksekutif perusahaan Anda.';

        return view('dashboard/owner', compact(
            'financial_summary', 'avg_row', 'all_analyses',
            'risk_low', 'risk_moderate', 'risk_high',
            'pending_reviews', 'total_approved', 'need_attention',
            'recent_submitted', 'total_clients',
            'page_title', 'page_subtitle'
        ));
    }

    // ═══════════════════════════════════════════════════
    // STAFF DASHBOARD
    // ═══════════════════════════════════════════════════
    private function staffDashboard()
    {
        $db = \Config\Database::connect();
        
        $total_clients = (new PerusahaanModel())->countAll();
        
        // Reports globally
        $total_reports = $db->table('laporan_keuangan')
            ->countAllResults();
            
        // Ready to send
        $ready_to_send = $db->table('laporan_keuangan')
            ->where('status', 'Ready for Analysis')
            ->countAllResults();
            
        $recent_reports = $db->table('laporan_keuangan lk')
            ->select('lk.*, p.nama_perusahaan, p.kode_saham')
            ->join('perusahaan p', 'p.id_perusahaan = lk.id_perusahaan')
            ->orderBy('lk.created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        // Page header info (rendered in topbar)
        $page_title = 'Staff Portal';
        $page_subtitle = 'Selamat datang, ' . session()->get('nama_lengkap') . '! Kelola data mentah laporan keuangan klien.';

        return view('dashboard/staff', compact(
            'total_clients', 'total_reports', 'ready_to_send', 'recent_reports',
            'page_title', 'page_subtitle'
        ));
    }
}