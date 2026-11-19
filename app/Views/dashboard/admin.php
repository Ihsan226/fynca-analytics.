<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- 4 Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
    
    <!-- Users -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #4F46E5; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Users</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= $total_users ?></h3>
            <p class="text-[11px] text-gray-400">Total Pengguna Terdaftar</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
            <i class="fa-solid fa-users-gear text-indigo-500 text-sm"></i>
        </div>
    </div>

    <!-- Sektor Industri -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #F59E0B; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Sektor Industri</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= $total_sektor ?></h3>
            <p class="text-[11px] text-gray-400">Master Data Sektor</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
            <i class="fa-solid fa-industry text-amber-500 text-sm"></i>
        </div>
    </div>

    <!-- Parameter Pajak -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #10B981; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Parameter Pajak</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= $total_parameter ?></h3>
            <p class="text-[11px] text-gray-400">Aturan Kalkulasi Aktif</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
            <i class="fa-solid fa-scale-balanced text-emerald-500 text-sm"></i>
        </div>
    </div>

    <!-- Aktivitas Hari Ini -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #EAB308; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Aktivitas Hari Ini</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= $total_aktivitas_hari_ini ?></h3>
            <p class="text-[11px] text-gray-400">Tercatat di sistem hari ini</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-yellow-50 flex items-center justify-center">
            <i class="fa-solid fa-bolt text-yellow-500 text-sm"></i>
        </div>
    </div>

</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    
    <!-- Area Chart (Left - 2 cols) -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] lg:col-span-2 flex flex-col overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-area text-indigo-500 text-xs"></i> Tren Aktivitas Sistem (7 Hari Terakhir)
            </h3>
        </div>
        <div class="p-5 flex-1">
            <div class="relative w-full h-64">
                <canvas id="activityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity (Right - 1 col) -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden flex flex-col" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-gray-400 text-xs"></i> Aktivitas Terbaru
            </h3>
            <a href="/aktivitas" class="text-[11px] text-blue-600 hover:text-blue-700 font-semibold transition-colors">
                Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="p-0 overflow-y-auto" style="max-height: 310px;">
            <?php if(empty($recent_logs)): ?>
                <div class="p-8 text-center">
                    <i class="fa-solid fa-info-circle text-gray-300 text-3xl mb-2"></i>
                    <p class="text-gray-400 text-sm">Belum ada aktivitas</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-50">
                <?php foreach ($recent_logs as $log): ?>
                    <?php
                        $iconMap = ['create' => 'fa-circle-plus', 'system' => 'fa-microchip', 'auth' => 'fa-right-to-bracket', 'update' => 'fa-pen-to-square', 'delete' => 'fa-trash-can'];
                        $colorMap = ['create' => 'text-green-600', 'system' => 'text-purple-600', 'auth' => 'text-blue-600', 'update' => 'text-amber-600', 'delete' => 'text-rose-600'];
                        $bgMap = ['create' => 'bg-green-50', 'system' => 'bg-purple-50', 'auth' => 'bg-blue-50', 'update' => 'bg-amber-50', 'delete' => 'bg-rose-50'];
                        $icon = $iconMap[$log['tipe']] ?? 'fa-circle';
                        $color = $colorMap[$log['tipe']] ?? 'text-gray-500';
                        $bg = $bgMap[$log['tipe']] ?? 'bg-gray-100';
                    ?>
                    <div class="px-5 py-3 hover:bg-[#FAFAFB] transition-colors flex gap-3 items-start">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 <?= $bg ?>">
                            <i class="fa-solid <?= $icon ?> <?= $color ?> text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-700 truncate"><?= esc($log['aksi']) ?></p>
                            <p class="text-[10px] text-gray-400 truncate mt-0.5"><?= esc($log['user'] ?? $log['nama_lengkap']) ?> (<?= esc($log['role'] ?? '-') ?>)</p>
                        </div>
                        <div class="text-[10px] font-medium text-gray-400 shrink-0 mt-0.5">
                            <?= date('H:i', strtotime($log['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bottom Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <!-- User Roles Chart -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden flex flex-col h-full" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-indigo-500 text-xs"></i> Distribusi Pengguna Sistem
            </h3>
        </div>
        <div class="p-5 flex-1 flex flex-col justify-between items-center">
            <div class="relative w-full h-48 mb-4">
                <canvas id="userRolesChart"></canvas>
            </div>
            <span class="bg-[#FAFAFB] border border-[#EEF0F3] text-gray-500 px-4 py-1.5 rounded-full text-[11px] font-medium flex items-center gap-2">
                <i class="fa-solid fa-code-branch text-gray-400"></i> Status Sistem: Development
            </span>
        </div>
    </div>

    <!-- Critical Activity Logs -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden flex flex-col h-full" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-400 text-xs"></i> Peringatan Aktivitas Penting
            </h3>
        </div>
        <div class="p-0 overflow-y-auto flex-1">
            <?php if(empty($critical_logs)): ?>
                <div class="p-8 text-center h-full flex flex-col justify-center items-center">
                    <i class="fa-solid fa-shield-halved text-gray-300 text-3xl mb-2"></i>
                    <p class="text-gray-400 text-sm">Belum ada aktivitas kritis</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-50">
                <?php foreach ($critical_logs as $log): ?>
                    <?php
                        $iconMap = ['create' => 'fa-circle-plus', 'system' => 'fa-microchip', 'auth' => 'fa-right-to-bracket', 'update' => 'fa-pen-to-square', 'delete' => 'fa-trash-can'];
                        $colorMap = ['create' => 'text-green-600', 'system' => 'text-purple-600', 'auth' => 'text-blue-600', 'update' => 'text-amber-600', 'delete' => 'text-rose-600'];
                        $bgMap = ['create' => 'bg-green-50', 'system' => 'bg-purple-50', 'auth' => 'bg-blue-50', 'update' => 'bg-amber-50', 'delete' => 'bg-rose-50'];
                        $icon = $iconMap[$log['tipe']] ?? 'fa-circle';
                        $color = $colorMap[$log['tipe']] ?? 'text-gray-500';
                        $bg = $bgMap[$log['tipe']] ?? 'bg-gray-100';
                    ?>
                    <div class="px-5 py-3 hover:bg-[#FAFAFB] transition-colors flex gap-3 items-start">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 <?= $bg ?>">
                            <i class="fa-solid <?= $icon ?> <?= $color ?> text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-700 truncate"><?= esc($log['aksi']) ?></p>
                            <p class="text-[10px] text-gray-400 truncate mt-0.5"><?= esc($log['user'] ?? $log['nama_lengkap']) ?> (<?= esc($log['role'] ?? '-') ?>)</p>
                        </div>
                        <div class="text-[10px] font-medium text-gray-400 shrink-0 mt-0.5">
                            <?= date('d M, H:i', strtotime($log['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('activityChart');
    if(ctx) {
        var gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.15)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_labels ?? []) ?>,
                datasets: [{
                    label: 'Total Aktivitas',
                    data: <?= json_encode($chart_data ?? []) ?>,
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: 'Inter', size: 12 },
                        bodyFont: { family: 'Inter', size: 12, weight: 'bold' },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226,232,240,0.6)', drawBorder: false },
                        ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8', padding: 8, stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', size: 11 }, color: '#94a3b8' }
                    }
                }
            }
        });
    }

    var ctxPie = document.getElementById('userRolesChart');
    if(ctxPie) {
        <?php
        $role_labels = [];
        $role_data = [];
        $role_colors = [];
        $color_palette = [
            'admin' => '#4f46e5',
            'analyst' => '#10b981',
            'staff' => '#f59e0b',
            'owner' => '#8b5cf6',
        ];
        if(isset($user_roles)) {
            foreach ($user_roles as $ur) {
                $role_labels[] = ucfirst($ur['role']);
                $role_data[] = $ur['count'];
                $role_colors[] = $color_palette[$ur['role']] ?? '#94a3b8';
            }
        }
        ?>
        new Chart(ctxPie.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($role_labels) ?>,
                datasets: [{
                    data: <?= json_encode($role_data) ?>,
                    backgroundColor: <?= json_encode($role_colors) ?>,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { family: 'Inter', size: 11 }, usePointStyle: true, padding: 20, color: '#64748b' }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: 'Inter', size: 12 },
                        bodyFont: { family: 'Inter', size: 12, weight: 'bold' },
                        padding: 10, cornerRadius: 8, displayColors: true
                    }
                }
            }
        });
    }
});
</script>

<?= $this->endSection(); ?>