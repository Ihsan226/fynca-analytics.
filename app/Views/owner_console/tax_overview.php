<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
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
<div class="mb-6 sm:mb-8">
    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-receipt text-indigo-600 shrink-0"></i>
        <span class="truncate">Tax Overview</span>
    </h1>
    <p class="text-gray-500 mt-1 text-xs sm:text-sm">Executive summary of tax burdens and Effective Tax Rates (ETR) across all clients</p>
</div>

<!-- Aggregated Metrics -->
<?php
    $total_tax = array_sum(array_column($analisis, 'beban_pajak'));
    $avg_etr = count($analisis) > 0 ? array_sum(array_column($analisis, 'val_etr')) / count($analisis) : 0;
?>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl sm:rounded-2xl shadow-sm text-white p-4 sm:p-6 flex flex-col justify-center relative overflow-hidden min-w-0">
        <div class="relative z-10 min-w-0">
            <p class="text-xs sm:text-sm font-bold text-indigo-200 uppercase tracking-wider mb-1 sm:mb-2">Total Tax Burden (All Clients)</p>
            <h3 class="text-xl sm:text-2xl lg:text-3xl font-black truncate" title="Rp <?= number_format($total_tax, 0, ',', '.') ?>"><?= fmtRp($total_tax) ?></h3>
        </div>
        <i class="fa-solid fa-money-bill-wave absolute -right-4 -bottom-4 text-5xl sm:text-7xl text-white/10"></i>
    </div>
    
    <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl sm:rounded-2xl shadow-sm text-white p-4 sm:p-6 flex flex-col justify-center relative overflow-hidden min-w-0">
        <div class="relative z-10 min-w-0">
            <p class="text-xs sm:text-sm font-bold text-blue-200 uppercase tracking-wider mb-1 sm:mb-2">Average Effective Tax Rate</p>
            <h3 class="text-xl sm:text-2xl lg:text-3xl font-black"><?= number_format($avg_etr * 100, 2) ?>%</h3>
        </div>
        <i class="fa-solid fa-percent absolute -right-4 -bottom-4 text-5xl sm:text-7xl text-white/10"></i>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-base sm:text-lg font-bold text-gray-700">Detailed Tax Reports</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" style="min-width: 580px;">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-gray-500 text-[10px] sm:text-xs uppercase tracking-wider">
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold">Client</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold">Year</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-right whitespace-nowrap">Profit Before Tax (Rp)</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-right whitespace-nowrap">Tax Expense (Rp)</th>
                    <th class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-right whitespace-nowrap">Effective Tax Rate (ETR)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($analisis)): ?>
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">No tax data available.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($analisis as $row): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 sm:py-4 px-4 sm:px-6">
                            <div class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($row['nama_perusahaan']) ?></div>
                            <div class="text-[10px] sm:text-xs text-gray-500"><?= htmlspecialchars($row['kode_saham']) ?></div>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 font-semibold text-gray-700 text-sm">
                            <?= $row['tahun'] ?>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 text-right font-medium text-gray-700 text-sm whitespace-nowrap">
                            <?= number_format($row['laba_sebelum_pajak'], 0, ',', '.') ?>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 text-right font-medium text-red-600 text-sm whitespace-nowrap">
                            <?= number_format($row['beban_pajak'], 0, ',', '.') ?>
                        </td>
                        <td class="py-3 sm:py-4 px-4 sm:px-6 text-right font-black text-sm <?= $row['val_etr'] < 0.2 ? 'text-red-600' : 'text-emerald-600' ?>">
                            <?= number_format($row['val_etr'] * 100, 2) ?>%
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

