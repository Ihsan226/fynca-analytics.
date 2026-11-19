<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<?php
    $has_data = $total_emiten > 0 && $total_laporan > 0;
    $has_analisis = !empty($preview_analisis);
    $has_chart = $count > 0;
?>

<!-- Header Dashboard -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Selamat datang, <?= session()->get('nama_lengkap') ?>. Berikut ringkasan indikator.</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="bg-white border border-gray-200 text-gray-600 px-4 py-1.5 rounded-full text-xs font-semibold shadow-sm flex items-center gap-2">
            <i class="fa-regular fa-calendar"></i> Tahun Fiskal Aktif
        </span>
        <?php if ($has_data): ?>
        <span class="bg-white border border-emerald-200 text-emerald-600 px-4 py-1.5 rounded-full text-xs font-semibold shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> Data Tersedia
        </span>
        <?php else: ?>
        <span class="bg-white border border-amber-200 text-amber-600 px-4 py-1.5 rounded-full text-xs font-semibold shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i> Belum Ada Data
        </span>
        <?php endif; ?>
    </div>
</div>

<!-- 4 Card Ringkasan Utama -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    
    <!-- Card 1: Total Emiten -->
    <div class="bg-blue-600 rounded-xl p-5 text-white shadow-md flex justify-between items-center relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-[10px] text-blue-100 font-bold uppercase tracking-wider mb-1 flex items-center gap-2">
                <i class="fa-solid fa-bars"></i> Total Klien
            </p>
            <h3 class="text-3xl font-bold mb-1"><?= $total_emiten ?></h3>
            <p class="text-[10px] text-blue-100 flex items-center gap-1">
                <?php if ($total_emiten > 0): ?>
                    <i class="fa-solid fa-arrow-trend-up"></i> Perusahaan Terdaftar
                <?php else: ?>
                    <i class="fa-solid fa-info-circle"></i> Belum ada klien
                <?php endif; ?>
            </p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center text-2xl relative z-10">
            <i class="fa-solid fa-building"></i>
        </div>
    </div>

    <!-- Card 2: Total Laporan -->
    <div class="bg-emerald-500 rounded-xl p-5 text-white shadow-md flex justify-between items-center relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-[10px] text-emerald-100 font-bold uppercase tracking-wider mb-1 flex items-center gap-2">
                <i class="fa-solid fa-download"></i> Total Laporan
            </p>
            <h3 class="text-3xl font-bold mb-1"><?= $total_laporan ?></h3>
            <p class="text-[10px] text-emerald-100 flex items-center gap-1">
                <?php if ($total_laporan > 0): ?>
                    <i class="fa-solid fa-plus"></i> Data Tersimpan
                <?php else: ?>
                    <i class="fa-solid fa-info-circle"></i> Belum ada laporan
                <?php endif; ?>
            </p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center text-2xl relative z-10">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
    </div>

    <!-- Card 3: Rata-rata ETR -->
    <div class="bg-rose-500 rounded-xl p-5 text-white shadow-md flex justify-between items-center relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-[10px] text-rose-100 font-bold uppercase tracking-wider mb-1 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> Rata-rata ETR
            </p>
            <h3 class="text-3xl font-bold mb-1"><?= $has_analisis ? number_format($avg_etr, 3) : '-' ?></h3>
            <p class="text-[10px] text-rose-100 flex items-center gap-1">
                <?php if ($has_analisis): ?>
                    <i class="fa-solid fa-percent"></i> Tax Aggressiveness Indicator
                <?php else: ?>
                    <i class="fa-solid fa-info-circle"></i> Belum ada kalkulasi
                <?php endif; ?>
            </p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center text-2xl relative z-10">
            <i class="fa-solid fa-scale-unbalanced"></i>
        </div>
    </div>

    <!-- Card 4: Rata-rata Growth -->
    <div class="bg-gradient-to-r from-amber-400 to-purple-500 rounded-xl p-5 text-white shadow-md flex justify-between items-center relative overflow-hidden">
        <div class="relative z-10">
            <p class="text-[10px] text-white/80 font-bold uppercase tracking-wider mb-1 flex items-center gap-2">
                <i class="fa-solid fa-chart-line"></i> Avg Sales Growth
            </p>
            <h3 class="text-3xl font-bold mb-1"><?= $has_analisis ? number_format($avg_growth * 100, 1) . '%' : '-' ?></h3>
            <p class="text-[10px] text-white/80 flex items-center gap-1">
                <?php if ($has_analisis): ?>
                    <i class="fa-solid fa-arrow-trend-up"></i> Sales Growth
                <?php else: ?>
                    <i class="fa-solid fa-info-circle"></i> Belum ada kalkulasi
                <?php endif; ?>
            </p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center text-2xl relative z-10">
            <i class="fa-solid fa-chart-pie"></i>
        </div>
    </div>
</div>

<!-- Area Utama: Status Cards Kanan -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    
    <!-- Tabel Preview (Kiri - 2 Kolom) -->
    <!-- Area Chart (Kiri - Lebar 2 Kolom) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 lg:col-span-2 flex flex-col overflow-hidden">
        <!-- Header Chart Berwarna Biru Solid -->
        <div class="bg-blue-600 px-5 py-3">
            <h3 class="text-sm font-semibold text-white flex items-center gap-2">
                <i class="fa-solid fa-chart-column"></i> Ringkasan Keuangan
            </h3>
        </div>
        <!-- Isi Chart -->
        <div class="p-5 flex-1">
            <?php if (!empty($chart_labels)): ?>
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Grafik ETR & Sales Growth</h4>
                    <p class="text-[10px] text-gray-400 mt-1"><i class="fa-regular fa-calendar"></i> Tren Per Tahun</p>
                </div>
                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[10px] font-bold">Periode: <?= esc($tahun_range) ?></span>
            </div>
            <!-- Canvas untuk Chart.js -->
            <div class="relative w-full h-64">
                <canvas id="trenChart"></canvas>
            </div>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center h-64">
                <div class="inline-flex w-14 h-14 bg-gray-100 rounded-lg items-center justify-center mb-3">
                    <i class="fa-solid fa-chart-line text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium">Belum ada data untuk ditampilkan</p>
                <p class="text-gray-400 text-xs mt-1">Grafik akan muncul setelah data analisis dimasukkan.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Status Cards (Kanan - 1 Kolom) -->
    <div class="flex flex-col gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-teal-500 text-white flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-percent"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">RPT Ratio</p>
                <h4 class="text-xl font-bold text-gray-800"><?= $has_analisis ? number_format($avg_rpt, 3) : '-' ?></h4>
                <p class="text-[10px] text-gray-500"><?= $has_analisis ? 'Related Party Receivable Ratio' : 'Belum ada data' ?></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-rose-500 text-white flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">DER — Leverage</p>
                <h4 class="text-xl font-bold text-gray-800"><?= $has_analisis ? number_format($avg_der, 3) : '-' ?></h4>
                <p class="text-[10px] text-gray-500"><?= $has_analisis ? 'Debt-to-Equity Ratio' : 'Belum ada data' ?></p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 flex-1">
            <div class="w-12 h-12 rounded-full <?= $has_analisis ? 'bg-indigo-500' : 'bg-gray-400' ?> text-white flex items-center justify-center text-xl shadow-sm">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Avg Risk Score</p>
                <h4 class="text-xl font-bold text-gray-800"><?= $has_analisis ? $avg_risk_score : '-' ?></h4>
                <p class="text-[10px] text-gray-500"><?= $has_analisis ? 'Overall Analytics Score' : 'Belum ada data' ?></p>
            </div>
        </div>
    </div>
</div>

<?php if ($has_chart): ?>
<!-- ═══════════════════════════════════════════════════════ -->
<!-- ═══ VISUALISASI DATA ANALISIS (Dari Halaman Kalkulasi) ═══ -->
<!-- ═══════════════════════════════════════════════════════ -->
<div style="margin-bottom:24px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
        <div style="width:4px;height:28px;background:linear-gradient(180deg,#4f46e5,#7c3aed);border-radius:4px;"></div>
        <div>
            <h2 style="font-size:18px;font-weight:800;color:#0f172a;margin:0;">Visualisasi Data Analisis</h2>
            <p style="font-size:12px;color:#94a3b8;margin:0;">Dashboard interaktif untuk membantu proses analisis metrik operasional</p>
        </div>
    </div>
</div>

<!-- Row 1: Line Chart (Full Width) -->
<div class="chart-card" style="margin-bottom:20px;">
    <div class="chart-card-header" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);">
                <i class="fa-solid fa-chart-line" style="color:#fff;font-size:18px;"></i>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Tren Perbandingan Semua Indikator</h3>
                <p style="font-size:12px;color:rgba(255,255,255,0.75);margin:2px 0 0;">Overlay ETR, RPT Ratio, DER, dan Sales Growth per klien</p>
            </div>
        </div>
        <span style="background:rgba(255,255,255,0.2);color:#fff;font-size:11px;font-weight:700;padding:6px 14px;border-radius:20px;backdrop-filter:blur(8px);">
            <i class="fa-solid fa-database" style="margin-right:4px;font-size:10px;"></i> <?= $count ?> Data
        </span>
    </div>
    <div class="chart-card-body">
        <div style="height:280px;position:relative;">
            <canvas id="chartAllVars"></canvas>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:16px;justify-content:center;margin-top:16px;padding-top:14px;border-top:1px solid rgba(241,245,249,0.8);">
            <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;font-weight:500;"><span style="width:24px;height:3px;background:#e11d48;border-radius:2px;display:inline-block;"></span> ETR</span>
            <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;font-weight:500;"><span style="width:24px;height:3px;background:#3b82f6;border-radius:2px;display:inline-block;"></span> RPT Ratio</span>
            <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;font-weight:500;"><span style="width:24px;height:3px;background:#f59e0b;border-radius:2px;display:inline-block;"></span> DER</span>
            <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;font-weight:500;"><span style="width:24px;height:3px;background:#10b981;border-radius:2px;display:inline-block;"></span> Growth</span>
        </div>
        <div class="insight-box">
            <div class="insight-icon blue"><i class="fa-solid fa-layer-group"></i></div>
            <div class="insight-content">
                <h4>Analisis Komparatif</h4>
                <p>Melihat fluktuasi keempat variabel secara bersamaan. Perbedaan pola pergerakan antara ETR dan variabel lainnya memberikan petunjuk awal mengenai korelasi dan strategi pajak klien.</p>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Radar + ETR -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    <div class="chart-card">
        <div class="chart-card-header" style="background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:38px;height:38px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-chart-pie" style="color:#fff;font-size:16px;"></i>
                </div>
                <div>
                    <h3 style="font-size:14px;font-weight:700;color:#fff;margin:0;">Distribusi Rata-rata</h3>
                    <p style="font-size:11px;color:rgba(255,255,255,0.7);margin:2px 0 0;">Profil radar ETR, RPT, DER, Growth</p>
                </div>
            </div>
        </div>
        <div class="chart-card-body">
            <div style="height:240px;position:relative;">
                <canvas id="chartRadar"></canvas>
            </div>
            <div class="insight-box" style="margin-top:16px;">
                <div class="insight-icon green"><i class="fa-solid fa-bullseye"></i></div>
                <div class="insight-content">
                    <h4>Peta Profil Klien</h4>
                    <p>Diagram radar memetakan sebaran nilai rata-rata tiap variabel. Bentuk bidang yang condong ke sumbu tertentu menunjukkan karakteristik dominan klien.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header" style="background:linear-gradient(135deg,#e11d48 0%,#f43f5e 100%);">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:38px;height:38px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-chart-bar" style="color:#fff;font-size:16px;"></i>
                </div>
                <div>
                    <h3 style="font-size:14px;font-weight:700;color:#fff;margin:0;">ETR — Tax Aggressiveness</h3>
                    <p style="font-size:11px;color:rgba(255,255,255,0.7);margin:2px 0 0;">Semakin rendah ETR → semakin agresif</p>
                </div>
            </div>
        </div>
        <div class="chart-card-body">
            <div style="height:240px;position:relative;">
                <canvas id="chartEtrBar"></canvas>
            </div>
            <div class="insight-box" style="margin-top:16px;">
                <div class="insight-icon rose"><i class="fa-solid fa-scale-unbalanced"></i></div>
                <div class="insight-content">
                    <h4>Tax Aggressiveness Indicator</h4>
                    <p>ETR yang rendah (biasanya &lt; 22%) merupakan indikasi tingkat agresivitas pajak yang relatif tinggi berdasarkan threshold analitik.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: DER + RPT -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    <div class="chart-card">
        <div class="chart-card-header" style="background:linear-gradient(135deg,#d97706 0%,#f59e0b 100%);">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:38px;height:38px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-building-columns" style="color:#fff;font-size:16px;"></i>
                </div>
                <div>
                    <h3 style="font-size:14px;font-weight:700;color:#fff;margin:0;">DER — Leverage Indicator</h3>
                    <p style="font-size:11px;color:rgba(255,255,255,0.7);margin:2px 0 0;">Analytical: 🟢 ≤1 · 🟡 1–2 · 🔴 &gt;2 | Tax Ref: 4:1</p>
                </div>
            </div>
        </div>
        <div class="chart-card-body">
            <div style="height:240px;position:relative;">
                <canvas id="chartDerBar"></canvas>
            </div>
            <div class="insight-box" style="margin-top:16px;">
                <div class="insight-icon amber"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="insight-content">
                    <h4>DER Analytical Threshold</h4>
                    <p>DER &gt; 1 menandakan proporsi utang lebih besar dari ekuitas. Tax reference: PMK 169/2015 menggunakan batas 4:1 dengan perhitungan tersendiri.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header" style="background:linear-gradient(135deg,#2563eb 0%,#3b82f6 100%);">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:38px;height:38px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-money-bill-transfer" style="color:#fff;font-size:16px;"></i>
                </div>
                <div>
                    <h3 style="font-size:14px;font-weight:700;color:#fff;margin:0;">RPT Ratio — Related Party Receivable</h3>
                    <p style="font-size:11px;color:rgba(255,255,255,0.7);margin:2px 0 0;">Proporsi piutang pihak berelasi terhadap total piutang</p>
                </div>
            </div>
        </div>
        <div class="chart-card-body">
            <div style="height:240px;position:relative;">
                <canvas id="chartRptBar"></canvas>
            </div>
            <div class="insight-box" style="margin-top:16px;">
                <div class="insight-icon blue"><i class="fa-solid fa-users-viewfinder"></i></div>
                <div class="insight-content">
                    <h4>Related Party Indicator</h4>
                    <p>Proporsi RPT yang tinggi menunjukkan besarnya porsi transaksi dengan pihak berelasi. Ini merupakan indikator, bukan penilaian akhir.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Growth (Full Width) -->
<div class="chart-card" style="margin-bottom:20px;">
    <div class="chart-card-header" style="background:linear-gradient(135deg,#059669 0%,#10b981 100%);">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);">
                <i class="fa-solid fa-chart-line" style="color:#fff;font-size:18px;"></i>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Sales Growth</h3>
                <p style="font-size:12px;color:rgba(255,255,255,0.75);margin:2px 0 0;">Hijau = pertumbuhan positif · Merah = penurunan penjualan</p>
            </div>
        </div>
    </div>
    <div class="chart-card-body">
        <div style="height:260px;position:relative;">
            <canvas id="chartGrowthBar"></canvas>
        </div>
        <div class="insight-box" style="margin-top:16px;">
            <div class="insight-icon green"><i class="fa-solid fa-percent"></i></div>
            <div class="insight-content">
                <h4>Tren Pertumbuhan</h4>
                <p>Sales Growth positif mencerminkan kinerja penjualan yang sehat, sementara yang negatif perlu diwaspadai karena bisa mendistorsi kebijakan pajak perusahaan ke depannya.</p>
            </div>
        </div>
    </div>
</div>

<!-- ═══ CHART.JS SCRIPTS ═══ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    Chart.register(ChartDataLabels);
    
    var labels = <?= json_encode($cLabels) ?>;
    var etr    = <?= json_encode($cEtr) ?>;
    var rpt    = <?= json_encode($cRpt) ?>;
    var der    = <?= json_encode($cDer) ?>;
    var growth = <?= json_encode($cGrowth) ?>;

    var FONT = { family: 'Inter', size: 11, weight: '500' };
    var grid = 'rgba(241, 245, 249, 0.8)';

    // Grafik Tren Per Tahun (Kiri Atas)
    var trenCtx = document.getElementById('trenChart');
    if (trenCtx) {
        new Chart(trenCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_labels ?? []) ?>, 
                datasets: [
                    {
                        label: 'Avg ETR',
                        data: <?= json_encode($chart_etr ?? []) ?>, 
                        borderColor: '#f43f5e',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    },
                    {
                        label: 'Avg Sales Growth',
                        data: <?= json_encode($chart_growth ?? []) ?>, 
                        borderColor: '#f59e0b',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        tension: 0.4,
                        borderDash: [5, 5], 
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            font: FONT,
                            padding: 20
                        }
                    },
                    datalabels: {
                        color: '#1e293b',
                        font: { family: 'Inter', size: 10, weight: 'bold' },
                        align: 'top',
                        anchor: 'end',
                        offset: 4,
                        formatter: function(value, context) {
                            if (value === null || value === undefined) return '';
                            if (value === 0) return '0';
                            return Number(value).toFixed(3); // ETR and Growth are small decimals
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f3f4f6' },
                        ticks: { font: FONT }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: FONT }
                    }
                }
            }
        });
    }

    function createGradient(ctx, colorStart, colorEnd) {
        var gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    }

    function base(extra) {
        var defaultPlugins = {
            legend: { position: 'bottom', labels: { usePointStyle:true, boxWidth:8, font:FONT, padding:20, color: '#475569' } },
            tooltip: { 
                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                titleFont: { family: 'Inter', size: 13, weight: '700' },
                bodyFont: { family: 'Inter', size: 12 },
                padding: 12,
                cornerRadius: 8,
                displayColors: true,
                boxPadding: 6,
                usePointStyle: true
            },
            datalabels: {
                color: '#1e293b',
                font: { family: 'Inter', size: 10, weight: 'bold' },
                align: 'top',
                anchor: 'end',
                offset: 4,
                formatter: function(value, context) {
                    if (value === null || value === undefined) return '';
                    if (value === 0) return '0';
                    return Number(value).toFixed(2);
                }
            }
        };

        var mergedPlugins = Object.assign({}, defaultPlugins, (extra && extra.plugins) || {});

        return Object.assign({
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: mergedPlugins,
            scales: {
                x: { grid:{display:false}, ticks:{font:FONT, color:'#64748b'} },
                y: { border: { display: false }, grid:{color:grid, drawTicks: false}, ticks:{font:FONT, color:'#64748b', padding: 10} }
            }
        }, extra || {}, { plugins: mergedPlugins });
    }

    // 1. Line — semua variabel
    var ctxAll = document.getElementById('chartAllVars').getContext('2d');
    var gradEtr = createGradient(ctxAll, 'rgba(225,29,72,0.15)', 'rgba(225,29,72,0.0)');
    new Chart(ctxAll, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label:'ETR',        data:etr,    borderColor:'#e11d48', backgroundColor:gradEtr,  borderWidth:3, tension:0.4, fill:true,  pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#e11d48', pointBorderWidth:2 },
                { label:'RPT Ratio',  data:rpt,    borderColor:'#3b82f6', backgroundColor:'transparent', borderWidth:3, tension:0.4, fill:false, pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#3b82f6', pointBorderWidth:2 },
                { label:'DER',        data:der,    borderColor:'#f59e0b', backgroundColor:'transparent', borderWidth:3, tension:0.4, fill:false, pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#f59e0b', pointBorderWidth:2, borderDash:[5,5] },
                { label:'Growth',     data:growth, borderColor:'#10b981', backgroundColor:'transparent', borderWidth:3, tension:0.4, fill:false, pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#10b981', pointBorderWidth:2 }
            ]
        },
        options: base({ plugins:{ legend:{display:false} } })
    });

    // 2. Radar — avg variabel
    new Chart(document.getElementById('chartRadar'), {
        type: 'radar',
        data: {
            labels: ['ETR', 'RPT Ratio', 'DER', 'Growth'],
            datasets: [{
                label: 'Rata-rata',
                data: [
                    <?= round($avg_etr,4) ?>,
                    <?= round($avg_rpt,4) ?>,
                    <?= round($avg_der,4) ?>,
                    <?= round($avg_growth,4) ?>
                ],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.2)',
                borderWidth: 2,
                pointBackgroundColor: '#4f46e5',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { 
                legend:{ display:false },
                tooltip: { backgroundColor: 'rgba(15, 23, 42, 0.95)', padding: 12, cornerRadius: 8, titleFont: { family: 'Inter', size: 13 } }
            },
            scales: {
                r: {
                    angleLines: { color: 'rgba(241, 245, 249, 1)' },
                    grid: { color:'rgba(241, 245, 249, 1)' },
                    pointLabels: { font:{ family:'Inter', size:12, weight:'600' }, color:'#475569' },
                    ticks: { font:{ family:'Inter', size:10 }, color:'#94a3b8', backdropColor:'transparent', stepSize: 0.2 }
                }
            }
        }
    });

    // 3. ETR bar
    var ctxEtr = document.getElementById('chartEtrBar').getContext('2d');
    var etrGrad = createGradient(ctxEtr, 'rgba(225,29,72,0.9)', 'rgba(225,29,72,0.6)');
    var etrGradLight = createGradient(ctxEtr, 'rgba(225,29,72,0.5)', 'rgba(225,29,72,0.2)');
    new Chart(ctxEtr, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'ETR',
                data: etr,
                backgroundColor: etr.map(function(v){ return v < 0.2 ? etrGrad : etrGradLight; }),
                borderRadius: 6, borderSkipped: false,
                barPercentage: 0.6
            }]
        },
        options: base({ plugins:{ legend:{display:false} } })
    });

    // 4. DER bar + threshold
    var ctxDer = document.getElementById('chartDerBar').getContext('2d');
    new Chart(ctxDer, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'DER',
                    data: der,
                    backgroundColor: der.map(function(v){ 
                        if(v > 2) return createGradient(ctxDer, 'rgba(225,29,72,0.9)', 'rgba(225,29,72,0.5)');
                        if(v > 1) return createGradient(ctxDer, 'rgba(245,158,11,0.9)', 'rgba(245,158,11,0.5)');
                        return createGradient(ctxDer, 'rgba(16,185,129,0.9)', 'rgba(16,185,129,0.5)');
                    }),
                    borderRadius: 6, borderSkipped: false,
                    barPercentage: 0.6
                },
                {
                    label: 'Batas DER=1',
                    data: labels.map(function(){ return 1; }),
                    type: 'line',
                    borderColor: '#ef4444',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    pointRadius: 0,
                    fill: false,
                    tension: 0
                }
            ]
        },
        options: base()
    });

    // 5. RPT bar
    var ctxRpt = document.getElementById('chartRptBar').getContext('2d');
    new Chart(ctxRpt, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'RPT Ratio',
                data: rpt,
                backgroundColor: createGradient(ctxRpt, 'rgba(59,130,246,0.9)', 'rgba(59,130,246,0.5)'),
                borderRadius: 6, borderSkipped: false,
                barPercentage: 0.6
            }]
        },
        options: base({ plugins:{ legend:{display:false} } })
    });

    // 6. Growth bar positif/negatif -> Doughnut
    var ctxGrowth = document.getElementById('chartGrowthBar').getContext('2d');
    var palette = ['#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899', '#14b8a6', '#f43f5e', '#6366f1', '#f97316', '#06b6d4'];
    new Chart(ctxGrowth, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales Growth',
                data: growth.map(function(v) { return Math.abs(v); }),
                backgroundColor: growth.map(function(v, i){ return palette[i % palette.length]; }),
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: { 
                legend: {
                    display: true,
                    position: 'right',
                    labels: { font: FONT, color: '#64748b', usePointStyle: true, padding: 15 }
                }, 
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleFont: { family: 'Inter', size: 13, weight: '700' },
                    bodyFont: { family: 'Inter', size: 12 },
                    padding: 12, cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            var val = growth[context.dataIndex] * 100;
                            return context.label + ': ' + (val > 0 ? '+' : '') + val.toFixed(2) + '%';
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: { family: 'Inter', size: 11, weight: 'bold' },
                    formatter: function(value, context) {
                        var v = growth[context.dataIndex];
                        return (v * 100).toFixed(1) + '%';
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>

<?= $this->endSection(); ?>