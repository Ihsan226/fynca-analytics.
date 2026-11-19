<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php
    $nama = session()->get('nama_lengkap') ?? 'Owner';
    
    $totalRevenue  = (float)($financial_summary->total_revenue ?? 0);
    $totalLaba     = (float)($financial_summary->total_laba ?? 0);
    $totalDebt     = (float)($financial_summary->total_debt ?? 0);
    $totalEquity   = (float)($financial_summary->total_equity ?? 0);
    $totalBeban    = (float)($financial_summary->total_beban_pajak ?? 0);
    
    $avgEtr    = (float)($avg_row->avg_etr ?? 0);
    $avgDer    = (float)($avg_row->avg_der ?? 0);
    $avgGrowth = (float)($avg_row->avg_growth ?? 0);
    
    if ($avgEtr < 0.15) $taxRiskLevel = 'HIGH';
    elseif ($avgEtr < 0.22) $taxRiskLevel = 'MODERATE';
    else $taxRiskLevel = 'LOW';
    
    $taxRiskColor = ['HIGH' => 'text-red-600 bg-red-50', 'MODERATE' => 'text-amber-600 bg-amber-50', 'LOW' => 'text-emerald-600 bg-emerald-50'];
    
    $derStatus = $avgDer > 4 ? 'EXCEEDS LIMIT' : ($avgDer > 1 ? 'WITHIN LIMIT' : 'HEALTHY');
    $derStatusColor = $avgDer > 4 ? 'text-red-600' : ($avgDer > 1 ? 'text-amber-600' : 'text-emerald-600');
    
    function formatRupiah($val) {
        if (abs($val) >= 1e12) return 'Rp ' . number_format($val / 1e12, 1) . 'T';
        if (abs($val) >= 1e9) return 'Rp ' . number_format($val / 1e9, 1) . 'M';
        if (abs($val) >= 1e6) return 'Rp ' . number_format($val / 1e6, 0) . 'jt';
        return 'Rp ' . number_format($val, 0, ',', '.');
    }
?>

<!-- Company Overview Cards -->
<div class="mb-7">
    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
        <i class="fa-solid fa-building text-xs"></i> Company Overview
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5" style="border-left: 3px solid #10B981; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-coins text-emerald-500 text-sm"></i>
                </div>
                <p class="text-xs font-semibold text-gray-400">Total Revenue</p>
            </div>
            <p class="text-xl font-bold text-gray-800"><?= formatRupiah($totalRevenue) ?></p>
        </div>
        <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5" style="border-left: 3px solid #3B82F6; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-blue-500 text-sm"></i>
                </div>
                <p class="text-xs font-semibold text-gray-400">Total Laba</p>
            </div>
            <p class="text-xl font-bold text-gray-800"><?= formatRupiah($totalLaba) ?></p>
        </div>
        <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5" style="border-left: 3px solid #F43F5E; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-rose-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-hand-holding-dollar text-rose-500 text-sm"></i>
                </div>
                <p class="text-xs font-semibold text-gray-400">Total Debt</p>
            </div>
            <p class="text-xl font-bold text-gray-800"><?= formatRupiah($totalDebt) ?></p>
        </div>
        <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5" style="border-left: 3px solid #4F46E5; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-vault text-indigo-500 text-sm"></i>
                </div>
                <p class="text-xs font-semibold text-gray-400">Total Equity</p>
            </div>
            <p class="text-xl font-bold text-gray-800"><?= formatRupiah($totalEquity) ?></p>
        </div>
    </div>
</div>

<!-- Tax Overview + Financial Risk + Analysis -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Tax Overview -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-rose-400 text-xs"></i> Tax Overview
            </h3>
        </div>
        <div class="p-5 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Effective Tax Rate</span>
                <span class="font-bold text-gray-800"><?= number_format($avgEtr * 100, 1) ?>%</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Tax Burden</span>
                <span class="font-bold text-gray-800"><?= formatRupiah($totalBeban) ?></span>
            </div>
            <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                <span class="text-sm text-gray-500 font-semibold">Tax Risk</span>
                <span class="font-bold text-sm px-3 py-1 rounded-full <?= $taxRiskColor[$taxRiskLevel] ?>"><?= $taxRiskLevel ?></span>
            </div>
        </div>
    </div>

    <!-- Financial Risk (DER) -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-amber-400 text-xs"></i> Financial Risk
            </h3>
        </div>
        <div class="p-5 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">DER</span>
                <span class="font-bold text-gray-800 text-lg"><?= number_format($avgDer, 2) ?>×</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Reference</span>
                <span class="font-semibold text-gray-600">4:1</span>
            </div>
            <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                <span class="text-sm text-gray-500 font-semibold">Status</span>
                <span class="font-bold <?= $derStatusColor ?>"><?= $derStatus ?></span>
            </div>
        </div>
    </div>

    <!-- Analysis Workflow -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-indigo-400 text-xs"></i> Analysis
            </h3>
        </div>
        <div class="p-5 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 flex items-center gap-2"><i class="fa-solid fa-bell text-amber-500 text-xs"></i> Pending Review</span>
                <span class="font-bold text-amber-600 text-lg"><?= $pending_reviews ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500 flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i> Approved</span>
                <span class="font-bold text-emerald-600 text-lg"><?= $total_approved ?></span>
            </div>
            <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                <span class="text-sm text-gray-500 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-red-500 text-xs"></i> Need Attention</span>
                <span class="font-bold text-red-600 text-lg"><?= $need_attention ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Risk Breakdown + Pending Reviews -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Risk Breakdown -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-shield-halved text-indigo-500 text-xs"></i> Risk Breakdown
            </h3>
            <a href="/risk-compliance" class="text-[11px] text-blue-600 hover:text-blue-700 font-semibold">View Details →</a>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-4 bg-[#FAFAFB] rounded-xl">
                    <p class="text-3xl font-bold text-emerald-600"><?= $risk_low ?></p>
                    <p class="text-[10px] font-bold text-emerald-500 uppercase mt-1">Low Risk</p>
                    <div class="w-8 h-1 bg-emerald-400 rounded-full mx-auto mt-2"></div>
                </div>
                <div class="text-center p-4 bg-[#FAFAFB] rounded-xl">
                    <p class="text-3xl font-bold text-amber-600"><?= $risk_moderate ?></p>
                    <p class="text-[10px] font-bold text-amber-500 uppercase mt-1">Moderate</p>
                    <div class="w-8 h-1 bg-amber-400 rounded-full mx-auto mt-2"></div>
                </div>
                <div class="text-center p-4 bg-[#FAFAFB] rounded-xl">
                    <p class="text-3xl font-bold text-rose-600"><?= $risk_high ?></p>
                    <p class="text-[10px] font-bold text-rose-500 uppercase mt-1">High Risk</p>
                    <div class="w-8 h-1 bg-rose-400 rounded-full mx-auto mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Reviews -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-bell text-amber-400 text-xs"></i> Pending Reviews
            </h3>
            <a href="/pending-reviews" class="text-[11px] text-blue-600 hover:text-blue-700 font-semibold">View All →</a>
        </div>
        <div class="p-4">
            <?php if (empty($recent_submitted)): ?>
            <div class="text-center py-8">
                <i class="fa-solid fa-inbox text-2xl text-gray-300 mb-2"></i>
                <p class="text-sm text-gray-400">Tidak ada analisis menunggu review</p>
            </div>
            <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($recent_submitted as $item): ?>
                <div class="flex items-center justify-between p-3 bg-[#FAFAFB] rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-file-lines text-blue-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800"><?= esc($item['kode_saham']) ?> — <?= esc($item['judul']) ?></p>
                            <p class="text-[10px] text-gray-400">By <?= esc($item['analyst_name']) ?> · <?= date('d M Y', strtotime($item['updated_at'])) ?></p>
                        </div>
                    </div>
                    <a href="/pending-reviews" class="text-xs text-blue-600 hover:text-blue-700 font-bold px-3 py-1.5 bg-blue-50 rounded-lg">Review</a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
