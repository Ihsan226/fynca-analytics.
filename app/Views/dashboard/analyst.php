<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php
    $nama = session()->get('nama_lengkap') ?? 'Analyst';
?>

<!-- Header Actions -->
<div class="flex justify-end mb-6">
    <a href="/simulation" class="bg-white border border-[#EEF0F3] text-gray-600 hover:bg-[#FAFAFB] px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center gap-2" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <i class="fa-solid fa-calculator"></i> Simulation
    </a>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
    
    <!-- Pending -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #F59E0B; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Pending Analysis</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= $pending ?></h3>
            <p class="text-[11px] text-gray-400">Laporan belum dianalisis</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
            <i class="fa-solid fa-hourglass-half text-amber-500 text-sm"></i>
        </div>
    </div>

    <!-- Drafts -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #94A3B8; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Drafts</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= $draft ?></h3>
            <p class="text-[11px] text-gray-400">Belum disubmit</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
            <i class="fa-solid fa-file-pen text-gray-400 text-sm"></i>
        </div>
    </div>

    <!-- Submitted -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #3B82F6; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Submitted</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= $submitted ?></h3>
            <p class="text-[11px] text-gray-400">Menunggu review</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
            <i class="fa-solid fa-paper-plane text-blue-500 text-sm"></i>
        </div>
    </div>

    <!-- Approved -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #10B981; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Approved</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= $completed ?></h3>
            <p class="text-[11px] text-gray-400">Analisis disetujui</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
            <i class="fa-solid fa-check-double text-emerald-500 text-sm"></i>
        </div>
    </div>

</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- My Work (Left - 2 cols) -->
    <div class="lg:col-span-2 bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-blue-500 text-xs"></i> My Work
            </h3>
            <a href="/workspace" class="text-[11px] text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                View All <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="p-0">
            <?php if (empty($my_work)): ?>
            <div class="flex flex-col items-center justify-center py-14">
                <div class="w-12 h-12 bg-[#FAFAFB] rounded-lg flex items-center justify-center mb-3">
                    <i class="fa-solid fa-microscope text-xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium">Belum ada pekerjaan</p>
                <p class="text-gray-400 text-xs mt-1">Mulai dengan menambahkan data klien dan laporan keuangan.</p>
                <a href="/workspace" class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-700 transition-colors">
                    <i class="fa-solid fa-plus mr-1"></i> Mulai Pekerjaan
                </a>
            </div>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#FAFAFB] border-b border-gray-100">
                            <th class="text-left py-3 px-5 text-[10px] font-bold text-gray-400 uppercase">Client</th>
                            <th class="text-left py-3 px-5 text-[10px] font-bold text-gray-400 uppercase">Financial Report</th>
                            <th class="text-center py-3 px-5 text-[10px] font-bold text-gray-400 uppercase">Status</th>
                            <th class="text-right py-3 px-5 text-[10px] font-bold text-gray-400 uppercase">Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($my_work as $item): ?>
                        <tr class="border-b border-gray-50 hover:bg-[#FAFAFB] transition-colors">
                            <td class="py-3 px-5">
                                <span class="font-semibold text-gray-800"><?= esc($item['kode_saham']) ?></span>
                                <span class="text-gray-400 text-xs ml-1">(<?= $item['tahun'] ?>)</span>
                            </td>
                            <td class="py-3 px-5 text-gray-600">Laporan Keuangan FY <?= esc($item['tahun']) ?></td>
                            <td class="py-3 px-5 text-center">
                                <?php 
                                    $status = $item['analysis_status'] ?? null;
                                    if (!$status) {
                                        $statusClass = 'bg-[#FEF3E2] text-[#B45309]';
                                        $icon = 'fa-hourglass-start';
                                        $label = 'PENDING ANALYSIS';
                                    } elseif ($status == 'draft') {
                                        $statusClass = 'bg-gray-100 text-gray-600';
                                        $icon = 'fa-pen-ruler';
                                        $label = 'DRAFT';
                                    } elseif ($status == 'submitted') {
                                        $statusClass = 'bg-blue-50 text-blue-700';
                                        $icon = 'fa-paper-plane';
                                        $label = 'SUBMITTED';
                                    } elseif ($status == 'revision') {
                                        $statusClass = 'bg-orange-50 text-orange-700';
                                        $icon = 'fa-rotate-left';
                                        $label = 'REVISION';
                                    } elseif ($status == 'approved' || $status == 'completed') {
                                        $statusClass = 'bg-[#ECFDF5] text-[#047857]';
                                        $icon = 'fa-check-double';
                                        $label = 'APPROVED';
                                    } else {
                                        $statusClass = 'bg-gray-100 text-gray-600';
                                        $icon = 'fa-file';
                                        $label = strtoupper($status);
                                    }
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                    <i class="fa-solid <?= $icon ?>"></i> <?= $label ?>
                                </span>
                            </td>
                            <td class="py-3 px-5 text-right text-xs text-gray-400">
                                <?= date('d M Y', strtotime($item['updated_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column -->
    <div class="flex flex-col gap-5">
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-indigo-500 text-xs"></i> Quick Actions
                </h3>
            </div>
            <div class="p-4 space-y-2">
                <a href="/workspace" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#F5F6FF] hover:bg-indigo-100/60 transition-colors">
                    <div class="w-9 h-9 bg-[#4F46E5] rounded-[10px] flex items-center justify-center text-white">
                        <i class="fa-solid fa-folder-open text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Available Reports</p>
                        <p class="text-[10px] text-gray-400">Buka Workspace untuk Analisis</p>
                    </div>
                </a>
                <?php if ($draft > 0): ?>
                <a href="/my-analyses" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#FAFAFB] hover:bg-gray-100 transition-colors">
                    <div class="w-9 h-9 bg-gray-500 rounded-[10px] flex items-center justify-center text-white">
                        <i class="fa-solid fa-pen text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Continue Draft</p>
                        <p class="text-[10px] text-gray-400"><?= $draft ?> draft menunggu</p>
                    </div>
                </a>
                <?php endif; ?>
                <?php if ($submitted > 0): ?>
                <a href="/review-status" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-[#FAFAFB] hover:bg-gray-100 transition-colors">
                    <div class="w-9 h-9 bg-blue-500 rounded-[10px] flex items-center justify-center text-white">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">Review Status</p>
                        <p class="text-[10px] text-gray-400"><?= $submitted ?> analisis dalam antrean</p>
                    </div>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Data Summary -->
        <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Data Summary</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
                    <span class="text-sm text-gray-600 flex items-center gap-2"><i class="fa-solid fa-building text-blue-500 text-xs"></i> Total Clients</span>
                    <span class="font-bold text-gray-800"><?= $total_clients ?></span>
                </div>
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-sm text-gray-600 flex items-center gap-2"><i class="fa-solid fa-file-invoice text-emerald-500 text-xs"></i> Financial Reports</span>
                    <span class="font-bold text-gray-800"><?= $total_reports ?></span>
                </div>
                <?php if ($avg_row): ?>
                <div class="border-t border-gray-100 pt-3 mt-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Avg Indicators</p>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-[#FAFAFB] rounded-lg p-2.5 text-center">
                            <p class="text-[10px] text-gray-400 font-semibold">ETR</p>
                            <p class="text-sm font-bold text-rose-600"><?= number_format((float)($avg_row->avg_etr ?? 0) * 100, 1) ?>%</p>
                        </div>
                        <div class="bg-[#FAFAFB] rounded-lg p-2.5 text-center">
                            <p class="text-[10px] text-gray-400 font-semibold">RPT</p>
                            <p class="text-sm font-bold text-blue-600"><?= number_format((float)($avg_row->avg_rpt ?? 0), 3) ?></p>
                        </div>
                        <div class="bg-[#FAFAFB] rounded-lg p-2.5 text-center">
                            <p class="text-[10px] text-gray-400 font-semibold">DER</p>
                            <p class="text-sm font-bold text-amber-600"><?= number_format((float)($avg_row->avg_der ?? 0), 2) ?>×</p>
                        </div>
                        <div class="bg-[#FAFAFB] rounded-lg p-2.5 text-center">
                            <p class="text-[10px] text-gray-400 font-semibold">Growth</p>
                            <p class="text-sm font-bold text-emerald-600"><?= number_format((float)($avg_row->avg_growth ?? 0) * 100, 1) ?>%</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Analysis Trend + Latest Indicators -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Analysis Trend Chart (Left - 2 cols) -->
    <div class="lg:col-span-2 bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-blue-500 text-xs"></i> Analysis Trend
            </h3>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1.5 text-[10px] text-rose-500 font-semibold"><span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span> ETR</span>
                <span class="flex items-center gap-1.5 text-[10px] text-blue-500 font-semibold"><span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span> RPT</span>
                <span class="flex items-center gap-1.5 text-[10px] text-amber-500 font-semibold"><span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span> DER</span>
                <span class="flex items-center gap-1.5 text-[10px] text-emerald-500 font-semibold"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Growth</span>
            </div>
        </div>
        <div class="p-5">
            <?php if (!empty($trend_data) && count($trend_data) >= 2): ?>
            <canvas id="analysisTrendChart" height="220"></canvas>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center py-16">
                <div class="w-12 h-12 bg-[#FAFAFB] rounded-lg flex items-center justify-center mb-3">
                    <i class="fa-solid fa-chart-line text-xl text-gray-300"></i>
                </div>
                <p class="text-sm text-gray-400 font-medium">Belum cukup data untuk menampilkan tren</p>
                <p class="text-xs text-gray-300 mt-1">Minimal 2 periode diperlukan</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Latest Indicators (Right - 1 col) -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-gauge-high text-indigo-500 text-xs"></i> Latest Indicators
            </h3>
            <?php if ($latest_indicators): ?>
            <p class="text-[10px] text-gray-400 mt-0.5">Fiscal Year <?= $latest_indicators['tahun'] ?></p>
            <?php endif; ?>
        </div>
        <div class="p-5">
            <?php if ($latest_indicators): ?>
            <div class="space-y-4">
                <?php
                    $indicators = [
                        ['key' => 'avg_etr', 'label' => 'ETR', 'icon' => 'fa-percent', 'color' => 'rose', 'multiply' => 100, 'suffix' => '%', 'decimals' => 1],
                        ['key' => 'avg_rpt', 'label' => 'RPT', 'icon' => 'fa-handshake', 'color' => 'blue', 'multiply' => 1, 'suffix' => '', 'decimals' => 3],
                        ['key' => 'avg_der', 'label' => 'DER', 'icon' => 'fa-scale-balanced', 'color' => 'amber', 'multiply' => 1, 'suffix' => '×', 'decimals' => 2],
                        ['key' => 'avg_growth', 'label' => 'Growth', 'icon' => 'fa-arrow-trend-up', 'color' => 'emerald', 'multiply' => 100, 'suffix' => '%', 'decimals' => 1],
                    ];
                    foreach ($indicators as $ind):
                        $currentVal = (float)($latest_indicators[$ind['key']] ?? 0) * $ind['multiply'];
                        $prevVal = $prev_indicators ? (float)($prev_indicators[$ind['key']] ?? 0) * $ind['multiply'] : null;
                        $diff = $prevVal !== null ? $currentVal - $prevVal : null;
                ?>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-<?= $ind['color'] ?>-50 rounded-lg flex items-center justify-center">
                            <i class="fa-solid <?= $ind['icon'] ?> text-<?= $ind['color'] ?>-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-semibold"><?= $ind['label'] ?></p>
                            <p class="text-lg font-bold text-gray-800"><?= number_format($currentVal, $ind['decimals']) ?><?= $ind['suffix'] ?></p>
                        </div>
                    </div>
                    <?php if ($diff !== null): ?>
                    <div class="text-right">
                        <?php if ($diff > 0): ?>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-[#ECFDF5] px-2 py-1 rounded-full">
                            <i class="fa-solid fa-arrow-up text-[8px]"></i> +<?= number_format(abs($diff), $ind['decimals']) ?>
                        </span>
                        <?php elseif ($diff < 0): ?>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-full">
                            <i class="fa-solid fa-arrow-down text-[8px]"></i> -<?= number_format(abs($diff), $ind['decimals']) ?>
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-500 bg-[#FAFAFB] px-2 py-1 rounded-full">
                            <i class="fa-solid fa-minus text-[8px]"></i> 0.0
                        </span>
                        <?php endif; ?>
                        <p class="text-[9px] text-gray-400 mt-0.5">vs FY<?= $prev_indicators['tahun'] ?? '' ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center py-10">
                <i class="fa-solid fa-gauge-high text-xl text-gray-300 mb-2"></i>
                <p class="text-sm text-gray-400">Belum ada data indikator</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Chart.js Script -->
<?php if (!empty($trend_data) && count($trend_data) >= 2): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('analysisTrendChart').getContext('2d');
    const labels = <?= json_encode(array_column($trend_data, 'tahun')) ?>;
    const etrData = <?= json_encode(array_map(fn($r) => round((float)$r['avg_etr'] * 100, 2), $trend_data)) ?>;
    const rptData = <?= json_encode(array_map(fn($r) => round((float)$r['avg_rpt'], 4), $trend_data)) ?>;
    const derData = <?= json_encode(array_map(fn($r) => round((float)$r['avg_der'], 3), $trend_data)) ?>;
    const growthData = <?= json_encode(array_map(fn($r) => round((float)$r['avg_growth'] * 100, 2), $trend_data)) ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.map(y => 'FY ' + y),
            datasets: [
                { label: 'ETR (%)', data: etrData, borderColor: '#f43f5e', backgroundColor: 'rgba(244,63,94,0.06)', fill: true, tension: 0.4, pointRadius: 5, pointHoverRadius: 7, pointBackgroundColor: '#f43f5e', borderWidth: 2 },
                { label: 'RPT', data: rptData, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.06)', fill: true, tension: 0.4, pointRadius: 5, pointHoverRadius: 7, pointBackgroundColor: '#3b82f6', borderWidth: 2 },
                { label: 'DER', data: derData, borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.06)', fill: true, tension: 0.4, pointRadius: 5, pointHoverRadius: 7, pointBackgroundColor: '#f59e0b', borderWidth: 2 },
                { label: 'Growth (%)', data: growthData, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.06)', fill: true, tension: 0.4, pointRadius: 5, pointHoverRadius: 7, pointBackgroundColor: '#10b981', borderWidth: 2 },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: 'rgba(15,23,42,0.9)', titleFont: { size: 12, weight: '600' }, bodyFont: { size: 11 }, cornerRadius: 8, padding: 12, displayColors: true, boxWidth: 8, boxHeight: 8, usePointStyle: true }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#94a3b8' } },
                y: { grid: { color: 'rgba(226,232,240,0.6)', drawBorder: false }, ticks: { font: { size: 10 }, color: '#94a3b8', padding: 8 }, beginAtZero: true }
            }
        }
    });
});
</script>
<?php endif; ?>

<?= $this->endSection(); ?>
