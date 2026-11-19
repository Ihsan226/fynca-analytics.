<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- html2pdf.js CDN for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<?php
$count      = count($hasil_analisis);
$avg_etr    = $count > 0 ? array_sum(array_column($hasil_analisis,'val_etr'))        / $count : 0;
$avg_rpt    = $count > 0 ? array_sum(array_column($hasil_analisis,'val_rpt_ratio'))  / $count : 0;
$avg_der    = $count > 0 ? array_sum(array_column($hasil_analisis,'val_der'))         / $count : 0;
$avg_growth = $count > 0 ? array_sum(array_column($hasil_analisis,'val_sales_growth'))/ $count : 0;

// Chart data
$cLabels = $cEtr = $cRpt = $cDer = $cGrowth = [];
foreach ($hasil_analisis as $r) {
    $cLabels[] = htmlspecialchars($r['kode_saham']) . ' (' . $r['tahun'] . ')';
    $cEtr[]    = round((float)$r['val_etr'],          4);
    $cRpt[]    = round((float)$r['val_rpt_ratio'],    4);
    $cDer[]    = round((float)$r['val_der'],           4);
    $cGrowth[] = round((float)$r['val_sales_growth'], 4);
}
?>

<!-- ── PAGE HEADER ── -->
<div class="ph">
    <div class="ph-left">
        <h1>Tax Indicators & Analysis</h1>
        <p>Otomatis dikalkulasi dari data laporan keuangan. Hasil ini merupakan indikator analitik, bukan keputusan hukum/pajak final.</p>
    </div>
    <div class="ph-right">
        <button class="btn btn-danger" onclick="exportPDF()" id="btnExportPdf">
            <i class="fa-solid fa-file-pdf"></i> Export PDF
        </button>
        <button class="btn btn-primary" onclick="printAnalisis()">
            <i class="fa-solid fa-print"></i> Print
        </button>
        <button class="btn btn-success">
            <i class="fa-regular fa-file-excel"></i> Excel
        </button>
    </div>
</div>

<!-- Printable area wrapper -->
<div id="printableArea">

<!-- Print-only header (hidden on screen) -->
<div id="printHeader" style="display:none;">
    <div style="text-align:center;padding:20px 0 16px;border-bottom:2px solid #4f46e5;margin-bottom:20px;">
        <h1 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Tax Indicators & Analysis Results</h1>
        <p style="font-size:13px;color:#64748b;margin:6px 0 0;">Fynca Analytics — Enterprise Tax Solution</p>
        <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">Dicetak pada: <?= date('d M Y, H:i') ?> WIB</p>
    </div>
</div>

<!-- ── STAT CARDS ── -->
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff;">
            <i class="fa-solid fa-building" style="color:#3b5de7;"></i>
        </div>
        <div>
            <div class="stat-label">Total Laporan</div>
            <div class="stat-value"><?= $count ?></div>
            <div class="stat-hint">Data terkalkulasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff1f2;">
            <i class="fa-solid fa-percent" style="color:#e11d48;"></i>
        </div>
        <div>
            <div class="stat-label">Avg ETR</div>
            <div class="stat-value" style="font-size:20px;color:#e11d48;"><?= $count > 0 ? number_format($avg_etr * 100, 2) . '%' : '—' ?></div>
            <div class="stat-hint">Tax Aggressiveness Indicator</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fffbeb;">
            <i class="fa-solid fa-scale-balanced" style="color:#d97706;"></i>
        </div>
        <div>
            <div class="stat-label">Avg DER</div>
            <div class="stat-value" style="font-size:20px;color:#d97706;"><?= $count > 0 ? number_format($avg_der, 2) . 'x' : '—' ?></div>
            <div class="stat-hint">Leverage Indicator</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;">
            <i class="fa-solid fa-chart-line" style="color:#16a34a;"></i>
        </div>
        <div>
            <div class="stat-label">Avg Growth</div>
            <div class="stat-value" style="font-size:20px;color:<?= $avg_growth >= 0 ? '#16a34a' : '#dc2626' ?>;"><?= $count > 0 ? ($avg_growth >= 0 ? '+' : '') . number_format($avg_growth * 100, 2) . '%' : '—' ?></div>
            <div class="stat-hint">Sales Growth</div>
        </div>
    </div>
</div>

<!-- ── TABLE ── -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div>
            <div class="card-title">Tax Indicator Results</div>
            <div class="card-sub">Semua nilai dihitung otomatis dari data laporan keuangan.</div>
        </div>
        <span class="badge badge-gray"><?= $count ?> laporan</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:36px;">No</th>
                    <th style="text-align:left;width:72px;">Kode</th>
                    <th style="text-align:left;">Perusahaan</th>
                    <th style="text-align:center;width:72px;">Tahun</th>
                    <th style="text-align:right;">ETR</th>
                    <th style="text-align:right;">RPT Ratio</th>
                    <th style="text-align:right;">DER</th>
                    <th style="text-align:right;">Sales Growth</th>
                    <th style="text-align:center;">Score</th>
                    <th style="text-align:center;width:80px;">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hasil_analisis)): ?>
                <tr>
                    <td colspan="10" style="padding:40px 20px;text-align:center;">
                        <div style="display:flex;flex-direction:column;align-items:center;">
                            <i class="fa-solid fa-inbox" style="font-size:32px;color:#cbd5e1;margin-bottom:12px;"></i>
                            <p style="color:#64748b;font-size:14px;margin:0;">Belum ada hasil kalkulasi.</p>
                        </div>
                    </td>
                </tr>
                <?php else: $no = 1; foreach ($hasil_analisis as $row): ?>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;"><?= $no++ ?></td>
                    <td><a href="/analisis/detail/<?= $row['id_analisis'] ?>" class="tbl-link"><?= htmlspecialchars($row['kode_saham']) ?></a></td>
                    <td style="font-weight:500;"><?= htmlspecialchars($row['nama_perusahaan']) ?></td>
                    <td style="text-align:center;"><span class="badge badge-gray"><?= $row['tahun'] ?></span></td>
                    <td style="text-align:right;">
                        <span style="display:inline-flex;align-items:center;gap:4px;font-weight:700;color:<?= $row['explain_etr']['color'] ?>;">
                            <?= $row['explain_etr']['icon'] ?> <?= $row['explain_etr']['formatted'] ?>
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <?php if ($row['explain_rpt']['level'] === 'na'): ?>
                            <span style="color:#94a3b8;font-size:12px;">— N/A</span>
                        <?php else: ?>
                            <span style="display:inline-flex;align-items:center;gap:4px;color:<?= $row['explain_rpt']['color'] ?>;">
                                <?= $row['explain_rpt']['icon'] ?> <?= $row['explain_rpt']['formatted'] ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right;">
                        <span style="display:inline-flex;align-items:center;gap:4px;font-weight:600;color:<?= $row['explain_der']['color'] ?>;">
                            <?= $row['explain_der']['icon'] ?> <?= $row['explain_der']['formatted'] ?>
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <span style="display:inline-flex;align-items:center;gap:4px;font-weight:600;color:<?= $row['explain_growth']['color'] ?>;">
                            <?= $row['explain_growth']['icon'] ?> <?= $row['explain_growth']['formatted'] ?>
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span style="display:inline-block;padding:2px 8px;border-radius:12px;font-size:10px;font-weight:800;letter-spacing:0.05em;
                            <?= $row['risk_label'] === 'EXCELLENT' ? 'background:#dcfce7;color:#166534;' : 
                               ($row['risk_label'] === 'MODERATE' ? 'background:#fef9c3;color:#854d0e;' : 'background:#fee2e2;color:#991b1b;') ?>">
                            <?= $row['risk_score'] ?>
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <a href="/analisis/detail/<?= $row['id_analisis'] ?>" class="btn btn-primary btn-sm" title="Lihat Detail Analisis">
                            <i class="fa-solid fa-magnifying-glass-chart"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($count > 0): ?>

<!-- ═══ VISUALISASI DATA ANALISIS ═══ -->
<style>
.viz-section { margin-bottom: 28px; }
.viz-title-bar { display:flex; align-items:center; gap:12px; margin-bottom:22px; }
.viz-title-pill { width:5px; height:32px; background:linear-gradient(180deg,#6366f1,#8b5cf6); border-radius:6px; flex-shrink:0; }
.viz-title h2 { font-size:17px; font-weight:800; color:#0f172a; margin:0; }
.viz-title p  { font-size:12px; color:#94a3b8; margin:2px 0 0; }

.bc { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,.07),0 4px 16px rgba(0,0,0,.05); overflow:hidden; }
.bc-head { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid #f1f5f9; }
.bc-head-left { display:flex; align-items:center; gap:12px; }
.bc-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.bc-title { font-size:14px; font-weight:700; color:#0f172a; margin:0; }
.bc-sub   { font-size:11px; color:#94a3b8; margin:3px 0 0; }
.bc-badge { font-size:10px; font-weight:700; padding:4px 12px; border-radius:20px; white-space:nowrap; }

.bc-body  { padding:20px 20px 16px; }
.bc-stats { display:flex; gap:12px; margin-bottom:16px; flex-wrap:wrap; }
.bc-stat  { flex:1; min-width:90px; background:#f8fafc; border-radius:10px; padding:10px 14px; }
.bc-stat-label { font-size:10px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px; }
.bc-stat-value { font-size:16px; font-weight:800; color:#0f172a; }

.bc-legend { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-top:14px; padding-top:12px; border-top:1px solid #f1f5f9; }
.bc-leg-item { display:flex; align-items:center; gap:6px; font-size:11px; color:#64748b; font-weight:500; }
.bc-leg-dot  { width:10px; height:10px; border-radius:3px; flex-shrink:0; }
</style>

<div class="viz-section">
    <div class="viz-title-bar">
        <div class="viz-title-pill"></div>
        <div class="viz-title">
            <h2>Tax Indicator Visualization</h2>
            <p>Grafik batang interaktif untuk analisis indikator pajak</p>
        </div>
    </div>
</div>

<!-- ── Chart 1: Grouped Bar All Indicators (Full Width) ── -->
<div class="bc" style="margin-bottom:20px;">
    <div class="bc-head">
        <div class="bc-head-left">
            <div class="bc-icon" style="background:#eff6ff;">
                <i class="fa-solid fa-chart-column" style="color:#3b82f6;font-size:17px;"></i>
            </div>
            <div>
                <p class="bc-title">Perbandingan Semua Indikator</p>
                <p class="bc-sub">ETR, RPT Ratio, DER, dan Sales Growth per klien</p>
            </div>
        </div>
        <span class="bc-badge" style="background:#eff6ff;color:#3b82f6;">
            <i class="fa-solid fa-database" style="margin-right:4px;"></i><?= $count ?> laporan
        </span>
    </div>
    <div class="bc-body">
        <div class="bc-stats">
            <div class="bc-stat">
                <div class="bc-stat-label">Avg ETR</div>
                <div class="bc-stat-value" style="color:#e11d48;"><?= number_format($avg_etr * 100, 2) ?>%</div>
            </div>
            <div class="bc-stat">
                <div class="bc-stat-label">Avg RPT Ratio</div>
                <div class="bc-stat-value" style="color:#3b82f6;"><?= number_format($avg_rpt * 100, 2) ?>%</div>
            </div>
            <div class="bc-stat">
                <div class="bc-stat-label">Avg DER</div>
                <div class="bc-stat-value" style="color:#f59e0b;"><?= number_format($avg_der, 2) ?>x</div>
            </div>
            <div class="bc-stat">
                <div class="bc-stat-label">Avg Growth</div>
                <div class="bc-stat-value" style="color:#10b981;"><?= ($avg_growth>=0?'+':'').number_format($avg_growth*100,2).'%' ?></div>
            </div>
        </div>
        <div style="height:300px;position:relative;">
            <canvas id="chartGrouped"></canvas>
        </div>
        <div class="bc-legend">
            <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#e11d48;"></span> ETR — Tax Aggressiveness</span>
            <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#3b82f6;"></span> RPT Ratio — Related Party</span>
            <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#f59e0b;"></span> DER — Leverage</span>
            <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#10b981;"></span> Sales Growth</span>
        </div>
    </div>
</div>

<!-- ── Row 2: ETR + RPT ── -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

    <!-- ETR Bar -->
    <div class="bc">
        <div class="bc-head">
            <div class="bc-head-left">
                <div class="bc-icon" style="background:#fff1f2;">
                    <i class="fa-solid fa-percent" style="color:#e11d48;font-size:16px;"></i>
                </div>
                <div>
                    <p class="bc-title">ETR — Tax Aggressiveness</p>
                    <p class="bc-sub">Semakin rendah ETR → semakin agresif</p>
                </div>
            </div>
            <span class="bc-badge" style="background:#fff1f2;color:#e11d48;">Indicator</span>
        </div>
        <div class="bc-body">
            <div style="height:240px;position:relative;">
                <canvas id="chartEtrBar"></canvas>
            </div>
            <div class="bc-legend" style="margin-top:10px;">
                <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#e11d48;"></span> Agresif (ETR &lt; 20%)</span>
                <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#fda4af;"></span> Normal</span>
            </div>
        </div>
    </div>

    <!-- RPT Bar -->
    <div class="bc">
        <div class="bc-head">
            <div class="bc-head-left">
                <div class="bc-icon" style="background:#eff6ff;">
                    <i class="fa-solid fa-money-bill-transfer" style="color:#3b82f6;font-size:16px;"></i>
                </div>
                <div>
                    <p class="bc-title">RPT Ratio — Related Party Receivable</p>
                    <p class="bc-sub">Rasio piutang pihak berelasi terhadap total piutang</p>
                </div>
            </div>
            <span class="bc-badge" style="background:#eff6ff;color:#3b82f6;">Indicator</span>
        </div>
        <div class="bc-body">
            <div style="height:240px;position:relative;">
                <canvas id="chartRptBar"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- ── Row 3: DER + Sales Growth ── -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

    <!-- DER Bar -->
    <div class="bc">
        <div class="bc-head">
            <div class="bc-head-left">
                <div class="bc-icon" style="background:#fffbeb;">
                    <i class="fa-solid fa-building-columns" style="color:#d97706;font-size:16px;"></i>
                </div>
                <div>
                    <p class="bc-title">DER — Leverage Indicator</p>
                    <p class="bc-sub">Analytical: 🟢 ≤1 · 🟡 1–2 · 🔴 >2 | Tax Ref: 4:1</p>
                </div>
            </div>
            <span class="bc-badge" style="background:#fffbeb;color:#d97706;">Indicator</span>
        </div>
        <div class="bc-body">
            <div style="height:240px;position:relative;">
                <canvas id="chartDerBar"></canvas>
            </div>
            <div class="bc-legend" style="margin-top:10px;">
                <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#10b981;"></span> Low (≤1)</span>
                <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#f59e0b;"></span> Moderate (1–2)</span>
                <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#e11d48;"></span> High (&gt;2)</span>
            </div>
        </div>
    </div>

    <!-- Sales Growth Bar -->
    <div class="bc">
        <div class="bc-head">
            <div class="bc-head-left">
                <div class="bc-icon" style="background:#f0fdf4;">
                    <i class="fa-solid fa-arrow-trend-up" style="color:#16a34a;font-size:16px;"></i>
                </div>
                <div>
                    <p class="bc-title">Sales Growth</p>
                    <p class="bc-sub">Hijau = pertumbuhan positif · Merah = penurunan</p>
                </div>
            </div>
            <span class="bc-badge" style="background:#f0fdf4;color:#16a34a;">Indicator</span>
        </div>
        <div class="bc-body">
            <div style="height:240px;position:relative;">
                <canvas id="chartGrowthBar"></canvas>
            </div>
            <div class="bc-legend" style="margin-top:10px;">
                <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#10b981;"></span> Positif</span>
                <span class="bc-leg-item"><span class="bc-leg-dot" style="background:#ef4444;"></span> Negatif</span>
            </div>
        </div>
    </div>

</div>

<!-- Disclaimer -->
<div class="info-box" style="background:#f0f9ff;border-color:#bae6fd;margin-bottom:20px;">
    <i class="fa-solid fa-circle-info" style="color:#0284c7;"></i>
    <p style="color:#0c4a6e;font-size:12px;"><strong>Disclaimer:</strong> Hasil yang ditampilkan merupakan analisis/indikasi dan alat bantu, bukan keputusan hukum atau pajak final. Untuk keperluan pelaporan pajak, silakan merujuk pada regulasi yang berlaku dan berkonsultasi dengan konsultan pajak profesional.</p>
</div>

<!-- ═══ CHART.JS SCRIPTS ═══ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var labels = <?= json_encode($cLabels) ?>;
    var etr    = <?= json_encode($cEtr) ?>;
    var rpt    = <?= json_encode($cRpt) ?>;
    var der    = <?= json_encode($cDer) ?>;
    var growth = <?= json_encode($cGrowth) ?>;

    var FONT = { family: 'Inter', size: 11, weight: '500' };
    var gridColor = 'rgba(241,245,249,1)';
    var borderR = 8;

    function gradRgba(ctx, r, g2, b, a1, a2) {
        var gr = ctx.createLinearGradient(0, 0, 0, 320);
        gr.addColorStop(0, 'rgba('+r+','+g2+','+b+','+a1+')');
        gr.addColorStop(1, 'rgba('+r+','+g2+','+b+','+a2+')');
        return gr;
    }

    function base(scaleOverrides) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family:'Inter', size:12, weight:'700' },
                    bodyFont:  { family:'Inter', size:11 },
                    padding: 12, cornerRadius: 10,
                    displayColors: true, boxPadding: 5, usePointStyle: true
                }
            },
            scales: Object.assign({
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: FONT, color: '#64748b', maxRotation: 35 }
                },
                y: {
                    border: { display: false },
                    grid: { color: gridColor, drawTicks: false },
                    ticks: { font: FONT, color: '#94a3b8', padding: 8 }
                }
            }, scaleOverrides || {})
        };
    }

    // ── 1. Grouped Bar ──
    var ctxG = document.getElementById('chartGrouped').getContext('2d');
    new Chart(ctxG, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label:'ETR',        data:etr,    backgroundColor:gradRgba(ctxG,225,29,72,0.85,0.5),  borderRadius:borderR, borderSkipped:false, barPercentage:0.75, categoryPercentage:0.7 },
                { label:'RPT Ratio',  data:rpt,    backgroundColor:gradRgba(ctxG,59,130,246,0.85,0.5), borderRadius:borderR, borderSkipped:false, barPercentage:0.75, categoryPercentage:0.7 },
                { label:'DER',        data:der,    backgroundColor:gradRgba(ctxG,245,158,11,0.85,0.5), borderRadius:borderR, borderSkipped:false, barPercentage:0.75, categoryPercentage:0.7 },
                { label:'Growth',     data:growth, backgroundColor:gradRgba(ctxG,16,185,129,0.85,0.5), borderRadius:borderR, borderSkipped:false, barPercentage:0.75, categoryPercentage:0.7 }
            ]
        },
        options: base()
    });

    // ── 2. ETR Bar ──
    var ctxE = document.getElementById('chartEtrBar').getContext('2d');
    new Chart(ctxE, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'ETR',
                data: etr,
                backgroundColor: etr.map(function(v){ return v < 0.2 ? gradRgba(ctxE,225,29,72,0.9,0.6) : gradRgba(ctxE,253,164,175,0.8,0.4); }),
                borderRadius: borderR, borderSkipped: false,
                barPercentage: 0.65, categoryPercentage: 0.8
            }]
        },
        options: base({ y: { border:{display:false}, grid:{color:gridColor,drawTicks:false}, ticks:{font:FONT,color:'#94a3b8',padding:8}, suggestedMin:0 } })
    });

    // ── 3. RPT Bar ──
    var ctxR = document.getElementById('chartRptBar').getContext('2d');
    new Chart(ctxR, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'RPT Ratio',
                data: rpt,
                backgroundColor: gradRgba(ctxR,59,130,246,0.88,0.5),
                borderRadius: borderR, borderSkipped: false,
                barPercentage: 0.65, categoryPercentage: 0.8
            }]
        },
        options: base()
    });

    // ── 4. DER Bar + threshold ──
    var ctxD = document.getElementById('chartDerBar').getContext('2d');
    new Chart(ctxD, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'DER',
                    data: der,
                    backgroundColor: der.map(function(v){
                        if (v > 2) return gradRgba(ctxD,225,29,72,0.9,0.55);
                        if (v > 1) return gradRgba(ctxD,245,158,11,0.9,0.55);
                        return gradRgba(ctxD,16,185,129,0.9,0.55);
                    }),
                    borderRadius: borderR, borderSkipped: false,
                    barPercentage: 0.65, categoryPercentage: 0.8,
                    order: 2
                },
                {
                    label: 'Analytical Threshold = 1',
                    data: labels.map(function(){ return 1; }),
                    type: 'line',
                    borderColor: '#ef4444',
                    borderDash: [6, 4],
                    borderWidth: 2,
                    pointRadius: 0,
                    fill: false,
                    tension: 0,
                    order: 1
                }
            ]
        },
        options: base()
    });

    // ── 5. Sales Growth Bar (Doughnut) ──
    var ctxGr = document.getElementById('chartGrowthBar').getContext('2d');
    var palette = ['#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899', '#14b8a6', '#f43f5e', '#6366f1', '#f97316', '#06b6d4'];
    new Chart(ctxGr, {
        type: 'doughnut',
        plugins: [ChartDataLabels],
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
                    backgroundColor: '#0f172a',
                    titleFont: { family:'Inter', size:12, weight:'700' },
                    bodyFont:  { family:'Inter', size:11 },
                    padding: 12, cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            var v = growth[context.dataIndex];
                            return context.label + ': ' + (v*100).toFixed(2) + '%';
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: { family: 'Inter', size: 11, weight: 'bold' },
                    formatter: function(value, context) {
                        var v = growth[context.dataIndex];
                        return (v*100).toFixed(1) + '%';
                    }
                }
            }
        }
    });
});
</script>
<?php endif; ?>

</div><!-- end #printableArea -->

<!-- ── EXPORT & PRINT SCRIPTS ── -->
<style>
@media print {
    aside, header, .topbar, nav,
    .ph, .alert-message,
    #btnExportPdf, .btn { display: none !important; }
    #printHeader { display: block !important; }
    body, main {
        margin: 0 !important; padding: 0 !important;
        background: white !important; overflow: visible !important; height: auto !important;
    }
    .flex, .flex-1 { display: block !important; overflow: visible !important; }
    main { padding: 16px !important; }
    #printableArea { width: 100% !important; }
    .card, .bc, .stat-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; break-inside: avoid; }
    canvas { max-width: 100% !important; height: auto !important; }
    .stat-grid { grid-template-columns: repeat(4, 1fr) !important; }
    .tbl { font-size: 11px; }
    .tbl td, .tbl th { padding: 8px 10px !important; }
}
</style>

<script>
function exportPDF() {
    var btn = document.getElementById('btnExportPdf');
    var originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...';
    btn.disabled = true;
    var printHeader = document.getElementById('printHeader');
    printHeader.style.display = 'block';
    var element = document.getElementById('printableArea');
    var opt = {
        margin: [10, 10, 10, 10],
        filename: 'Tax_Indicators_' + new Date().toISOString().slice(0,10) + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, logging: false, scrollY: 0 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };
    html2pdf().set(opt).from(element).save().then(function() {
        btn.innerHTML = originalText; btn.disabled = false;
        printHeader.style.display = 'none';
    }).catch(function() {
        btn.innerHTML = originalText; btn.disabled = false;
        printHeader.style.display = 'none';
    });
}

function printAnalisis() {
    var printHeader = document.getElementById('printHeader');
    printHeader.style.display = 'block';
    setTimeout(function() {
        window.print();
        setTimeout(function() { printHeader.style.display = 'none'; }, 500);
    }, 200);
}
</script>

<?= $this->endSection(); ?>
