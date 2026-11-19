<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
    // Helper untuk format angka besar agar tidak overflow card
    if (!function_exists('fmtRp')) {
        function fmtRp($val) {
            $val = (float)$val;
            if (abs($val) >= 1e12) return 'Rp ' . number_format($val / 1e12, 1, ',', '.') . ' T';
            if (abs($val) >= 1e9)  return 'Rp ' . number_format($val / 1e9, 1, ',', '.') . ' M';
            if (abs($val) >= 1e6)  return 'Rp ' . number_format($val / 1e6, 0, ',', '.') . ' jt';
            return 'Rp ' . number_format($val, 0, ',', '.');
        }
    }
?>

<!-- Page Header -->
<div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="min-w-0">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center gap-2 truncate">
            <i class="fa-solid fa-coins text-emerald-600 shrink-0"></i>
            <span class="truncate">Financial Overview</span>
        </h1>
        <p class="text-gray-500 mt-1 text-xs sm:text-sm">Executive summary of financial performance across all clients</p>
    </div>
</div>

<!-- Key Metrics Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex flex-col justify-center min-w-0">
        <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 sm:mb-2">Total Revenue</p>
        <h3 class="text-base sm:text-lg lg:text-xl font-black text-gray-800 truncate" title="Rp <?= number_format($summary->total_revenue ?? 0, 0, ',', '.') ?>"><?= fmtRp($summary->total_revenue ?? 0) ?></h3>
    </div>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex flex-col justify-center min-w-0">
        <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 sm:mb-2">Total Net Profit</p>
        <h3 class="text-base sm:text-lg lg:text-xl font-black text-emerald-600 truncate" title="Rp <?= number_format($summary->total_laba ?? 0, 0, ',', '.') ?>"><?= fmtRp($summary->total_laba ?? 0) ?></h3>
    </div>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex flex-col justify-center min-w-0">
        <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 sm:mb-2">Total Assets/Equity</p>
        <h3 class="text-base sm:text-lg lg:text-xl font-black text-blue-600 truncate" title="Rp <?= number_format($summary->total_equity ?? 0, 0, ',', '.') ?>"><?= fmtRp($summary->total_equity ?? 0) ?></h3>
    </div>
    
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 flex flex-col justify-center min-w-0">
        <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 sm:mb-2">Total Debt</p>
        <h3 class="text-base sm:text-lg lg:text-xl font-black text-red-600 truncate" title="Rp <?= number_format($summary->total_debt ?? 0, 0, ',', '.') ?>"><?= fmtRp($summary->total_debt ?? 0) ?></h3>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <h2 class="text-base sm:text-lg font-bold text-gray-700">Detailed Financial Reports</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" style="min-width: 640px;">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-gray-500 text-[10px] sm:text-xs uppercase tracking-wider">
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold">Client</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold">Year</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-right whitespace-nowrap">Revenue (Rp)</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-right whitespace-nowrap">Profit Before Tax (Rp)</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-right whitespace-nowrap">Debt (Rp)</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-right whitespace-nowrap">Equity (Rp)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($laporan)): ?>
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">No financial data available.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($laporan as $row): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 sm:py-4 px-4 sm:px-6">
                            <div class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($row['nama_perusahaan']) ?></div>
                            <div class="text-[10px] sm:text-xs text-gray-500"><?= htmlspecialchars($row['sektor']) ?></div>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-gray-700 text-sm">
                            <?= $row['tahun'] ?>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 text-right font-medium text-gray-700 text-sm whitespace-nowrap">
                            <?= number_format($row['penjualan_tahun_ini'], 0, ',', '.') ?>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 text-right font-medium text-sm whitespace-nowrap <?= $row['laba_sebelum_pajak'] < 0 ? 'text-red-600' : 'text-emerald-600' ?>">
                            <?= number_format($row['laba_sebelum_pajak'], 0, ',', '.') ?>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 text-right font-medium text-red-600 text-sm whitespace-nowrap">
                            <?= number_format($row['total_hutang'], 0, ',', '.') ?>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 text-right font-medium text-blue-600 text-sm whitespace-nowrap">
                            <?= number_format($row['total_ekuitas'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
