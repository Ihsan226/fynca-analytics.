<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php
// Include datalabels plugin
?>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<!-- html2pdf.js CDN for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<!-- SheetJS CDN for Excel export -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
<?php
// ── Siapkan data chart dari PHP ──────────────────────────────
$chartLabels    = [];  // label emiten (kode_saham tahun)
$chartEtr       = [];  // ETR (Y)
$chartRpt       = [];  // Transfer Pricing (X1)
$chartDer       = [];  // Thin Cap (X2)
$chartGrowth    = [];  // Sales Growth (Z)
$chartPenjIni   = [];  // Penjualan tahun ini
$chartPenjLalu  = [];  // Penjualan tahun lalu
$chartPiutRel   = [];  // Piutang berelasi
$chartTotalPiut = [];  // Total piutang
$chartHutang    = [];  // Total hutang
$chartEkuitas   = [];  // Total ekuitas
$chartLaba      = [];  // Laba sebelum pajak
$chartBeban     = [];  // Beban pajak

if (!empty($laporan_lengkap)) {
    foreach ($laporan_lengkap as $r) {
        $lbl = htmlspecialchars($r['kode_saham']) . ' (' . $r['tahun'] . ')';
        $chartLabels[]    = $lbl;
        $chartEtr[]       = round((float)$r['val_etr'],      4);
        $chartRpt[]       = round((float)$r['val_rpt_ratio'], 4);
        $chartDer[]       = round((float)$r['val_der'],         4);
        $chartGrowth[]    = round((float)$r['val_sales_growth'],     4);
        $chartPenjIni[]   = (float)($r['penjualan_tahun_ini']  ?? 0);
        $chartPenjLalu[]  = (float)($r['penjualan_tahun_lalu'] ?? 0);
        $chartPiutRel[]   = (float)($r['piutang_berelasi']     ?? 0);
        $chartTotalPiut[] = (float)($r['total_piutang']        ?? 0);
        $chartHutang[]    = (float)($r['total_hutang']         ?? 0);
        $chartEkuitas[]   = (float)($r['total_ekuitas']        ?? 0);
        $chartLaba[]      = (float)($r['laba_sebelum_pajak']   ?? 0);
        $chartBeban[]     = (float)($r['beban_pajak']          ?? 0);
    }
}
$hasData = !empty($laporan_lengkap);
?>

<!-- ════ PAGE HEADER ════ -->
<div class="ph">
    <div class="ph-left">
        <h1>Laporan Analisis Data</h1>
        <p>Analisis keuangan dan rasio lengkap perusahaan properti</p>
    </div>
    <div class="ph-right">
        <button class="btn btn-danger" id="btnExportPdf" onclick="exportPDF()"><i class="fa-regular fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-success" id="btnExportExcel" onclick="exportExcel()"><i class="fa-regular fa-file-excel"></i> Export Excel</button>
        <button class="btn btn-ghost" onclick="printLaporan()"><i class="fa-solid fa-print"></i> Print</button>
    </div>
</div>

<!-- ════ FILTER BAR ════ -->
<div class="card" style="margin-bottom:18px;">
    <div class="card-body">
        <div style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
            <div style="width:165px;">
                <label class="form-label">Dari Tahun</label>
                <input type="number" id="filterDari" placeholder="YYYY" class="form-input" value="<?= htmlspecialchars($filter_dari ?? '') ?>">
            </div>
            <div style="width:165px;">
                <label class="form-label">Sampai Tahun</label>
                <input type="number" id="filterSampai" placeholder="YYYY" class="form-input" value="<?= htmlspecialchars($filter_sampai ?? '') ?>">
            </div>
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Klien / Perusahaan</label>
                <select id="filterEmiten" class="form-input">
                    <option value="">Semua Klien</option>
                    <?php foreach ($perusahaan as $p): ?>
                    <option value="<?= $p['id_perusahaan'] ?>" <?= ($filter_emiten ?? '') == $p['id_perusahaan'] ? 'selected' : '' ?>><?= htmlspecialchars($p['kode_saham']) ?> - <?= htmlspecialchars($p['nama_perusahaan']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn-primary" onclick="generateLaporan()">
                <i class="fa-solid fa-magnifying-glass" style="font-size:11px;"></i> Generate
            </button>
        </div>
    </div>
</div>

<!-- Printable area wrapper -->
<div id="printableArea">

<!-- Print-only header (hidden on screen) -->
<div id="printHeader" style="display:none;">
    <div style="text-align:center;padding:28px 24px 20px;border-bottom:3px solid #3b5de7;margin-bottom:24px;">
        <div style="display:flex;align-items:center;justify-content:center;gap:14px;margin-bottom:12px;">
            <div style="width:48px;height:48px;background:linear-gradient(135deg,#3b5de7,#6366f1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <span style="color:#fff;font-size:20px;font-weight:900;">FA</span>
            </div>
            <div style="text-align:left;">
                <h1 style="font-size:22px;font-weight:800;color:#0f172a;margin:0;">Laporan Analisis Data</h1>
                <p style="font-size:13px;color:#3b5de7;margin:2px 0 0;font-weight:600;">Fynca Analytics — Analyst Console</p>
            </div>
        </div>
        <p style="font-size:12px;color:#64748b;margin:8px 0 0;">Analisis keuangan dan rasio lengkap perusahaan properti</p>
        <div style="display:flex;justify-content:center;gap:24px;margin-top:12px;">
            <?php if (!empty($filter_dari) || !empty($filter_sampai)): ?>
            <span style="font-size:11px;color:#475569;background:#f1f5f9;padding:4px 12px;border-radius:6px;"><strong>Periode:</strong> <?= $filter_dari ?? '-' ?> s/d <?= $filter_sampai ?? '-' ?></span>
            <?php endif; ?>
            <span style="font-size:11px;color:#475569;background:#f1f5f9;padding:4px 12px;border-radius:6px;"><strong>Dicetak:</strong> <?= date('d M Y, H:i') ?> WIB</span>
            <span style="font-size:11px;color:#475569;background:#f1f5f9;padding:4px 12px;border-radius:6px;"><strong>Total Data:</strong> <?= count($laporan_lengkap) ?> laporan</span>
        </div>
    </div>
</div>

<!-- ════ TABS ════ -->
<div class="card" id="tabsCard">

    <!-- Tab bar -->
    <div class="tabs-bar">
        <button class="tab-btn active" id="tab-rasio" onclick="switchTab('rasio')">
            <i class="fa-solid fa-scale-balanced"></i> Analisis Rasio
        </button>
        <button class="tab-btn" id="tab-laba" onclick="switchTab('laba')">
            <i class="fa-solid fa-chart-line"></i> Analisis Agresivitas Pajak
        </button>
        <button class="tab-btn" id="tab-hutang" onclick="switchTab('hutang')">
            <i class="fa-solid fa-money-bill-transfer"></i> Hutang &amp; Piutang
        </button>
        <button class="tab-btn" id="tab-sales" onclick="switchTab('sales')">
            <i class="fa-solid fa-chart-pie"></i> Sales Growth
        </button>
    </div>


    <!-- ════ PANEL 1: ANALISIS RASIO ════ -->
    <div id="panel-rasio" style="padding:22px;">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title">Laporan Metrik Analitik</div>
            <span class="badge badge-green"><i class="fa-solid fa-circle-check" style="font-size:9px;"></i> Kalkulasi Otomatis</span>
        </div>

        <div class="info-box" style="margin-bottom:16px;">
            <i class="fa-solid fa-circle-info"></i>
            <p><strong>Rumus Otomatis:</strong> Nilai pada tabel berikut merupakan hasil kalkulasi dari data laporan keuangan mentah.</p>
        </div>

        <!-- Tabel Rasio -->
        <div style="overflow-x:auto;border:1px solid #f1f5f9;border-radius:10px;margin-bottom:24px;">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="text-align:left;">Klien &amp; Tahun</th>
                        <th style="text-align:center;">ETR (Y)</th>
                        <th style="text-align:center;">Transfer Pricing (X1)</th>
                        <th style="text-align:center;">Thin Cap (X2)</th>
                        <th style="text-align:center;">Sales Growth (Z)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$hasData): ?>
                    <tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-inbox"></i><p>Belum ada data laporan.</p></div></td></tr>
                    <?php else: foreach ($laporan_lengkap as $row): ?>
                    <tr>
                        <td><span class="tbl-link"><?= htmlspecialchars($row['kode_saham']) ?></span>
                            <span style="color:#94a3b8;font-size:11px;margin-left:4px;">(<?= $row['tahun'] ?>)</span></td>
                        <td style="text-align:center;font-weight:600;color:#e11d48;"><?= number_format($row['val_etr'],4) ?></td>
                        <td style="text-align:center;color:#374151;"><?= number_format($row['val_rpt_ratio'],4) ?></td>
                        <td style="text-align:center;color:#374151;"><?= number_format($row['val_der'],4) ?></td>
                        <td style="text-align:center;font-weight:600;color:<?= $row['val_sales_growth']>=0?'#16a34a':'#dc2626' ?>;">
                            <?= ($row['val_sales_growth']>=0?'+':'').number_format($row['val_sales_growth'],4) ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Grafik Rasio: Line chart semua variabel -->
        <?php if ($hasData): ?>
        <div class="chart-card">
            <div class="chart-card-header" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);">
                        <i class="fa-solid fa-chart-line" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Grafik Perbandingan Semua Variabel</h3>
                        <p style="font-size:12px;color:rgba(255,255,255,0.75);margin:2px 0 0;">ETR (Y), RPT (X1), DER (X2), Growth (Z) per klien</p>
                    </div>
                </div>
                <span style="background:rgba(255,255,255,0.2);color:#fff;font-size:11px;font-weight:700;padding:6px 14px;border-radius:20px;backdrop-filter:blur(8px);">
                    <i class="fa-solid fa-database" style="margin-right:4px;font-size:10px;"></i> <?= count($laporan_lengkap) ?> Data
                </span>
            </div>
            <div class="chart-card-body">
                <div style="height:280px;position:relative;">
                    <canvas id="chartRasio"></canvas>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:16px;justify-content:center;margin-top:16px;padding-top:14px;border-top:1px solid rgba(241,245,249,0.8);">
                    <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;font-weight:500;"><span style="width:24px;height:3px;background:#e11d48;border-radius:2px;display:inline-block;"></span> ETR — Agresivitas Pajak (Y)</span>
                    <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;font-weight:500;"><span style="width:24px;height:3px;background:#3b82f6;border-radius:2px;display:inline-block;"></span> RPT — Transfer Pricing (X1)</span>
                    <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;font-weight:500;"><span style="width:24px;height:3px;background:#f59e0b;border-radius:2px;display:inline-block;"></span> DER — Thin Capitalization (X2)</span>
                    <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;font-weight:500;"><span style="width:24px;height:3px;background:#10b981;border-radius:2px;display:inline-block;"></span> Sales Growth (Z)</span>
                </div>
                
                <div class="insight-box">
                    <div class="insight-icon blue"><i class="fa-solid fa-lightbulb"></i></div>
                    <div class="insight-content">
                        <h4>Insight & Analisis</h4>
                        <p>Grafik di atas membandingkan keempat variabel utama untuk setiap klien secara bersamaan. Perhatikan tren garis <b>Sales Growth (hijau)</b> dibandingkan dengan <b>ETR (merah)</b> untuk melihat apakah pertumbuhan penjualan sejalan dengan tingkat agresivitas pajak perusahaan.</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>


    <!-- ════ PANEL 2: AGRESIVITAS PAJAK ════ -->
    <div id="panel-laba" style="padding:22px;display:none;">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title">Komponen Agresivitas Pajak (Y) — ETR</div>
            <span class="badge badge-rose">Variabel Dependen (Y)</span>
        </div>

        <?php if (!$hasData): ?>
            <div class="empty-state"><i class="fa-solid fa-inbox"></i><p>Belum ada data laporan.</p></div>
        <?php else: ?>

        <?php foreach ($laporan_lengkap as $row): ?>
        <div style="border:1px solid #f1f5f9;border-radius:12px;overflow:hidden;margin-bottom:14px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
            <div style="background:#fafafa;padding:11px 18px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f1f5f9;">
                <span style="font-size:13px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:7px;">
                    <i class="fa-solid fa-building" style="color:#94a3b8;font-size:11px;"></i>
                    <?= htmlspecialchars($row['nama_perusahaan']) ?>
                    <span style="color:#3b5de7;">(<?= htmlspecialchars($row['kode_saham']) ?>)</span>
                </span>
                <span class="badge badge-gray">Tahun: <?= $row['tahun'] ?></span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;border-bottom:1px solid #f1f5f9;">
                <div style="padding:16px 18px;border-right:1px solid #f1f5f9;">
                    <p style="font-size:10px;font-weight:700;color:#16a34a;text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px;display:flex;align-items:center;gap:5px;">
                        <i class="fa-solid fa-circle-plus"></i> Laba Sebelum Pajak
                    </p>
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:4px 0;">
                        <span style="color:#64748b;">Total Laba</span>
                        <span style="font-weight:700;color:#1e293b;">Rp <?= number_format($row['laba_sebelum_pajak'],0,',','.') ?></span>
                    </div>
                </div>
                <div style="padding:16px 18px;">
                    <p style="font-size:10px;font-weight:700;color:#dc2626;text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px;display:flex;align-items:center;gap:5px;">
                        <i class="fa-solid fa-circle-minus"></i> Beban Pajak
                    </p>
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:4px 0;">
                        <span style="color:#64748b;">Total Beban</span>
                        <span style="font-weight:700;color:#1e293b;">Rp <?= number_format($row['beban_pajak'],0,',','.') ?></span>
                    </div>
                </div>
            </div>
            <div style="background:#eff6ff;padding:10px 18px;text-align:center;font-size:13px;font-weight:600;color:#3b5de7;">
                Nilai ETR: <?= number_format($row['val_etr'],4) ?>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Grafik Laba: Grouped bar laba vs beban pajak -->
        <div class="chart-card" style="margin-top:24px;">
            <div class="chart-card-header" style="background:linear-gradient(135deg,#e11d48 0%,#f43f5e 100%);">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-chart-bar" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Grafik Laba vs Beban Pajak per Klien</h3>
                        <p style="font-size:12px;color:rgba(255,255,255,0.75);margin:2px 0 0;">Perbandingan laba sebelum pajak dan beban pajak</p>
                    </div>
                </div>
            </div>
            <div class="chart-card-body">
                <div style="height:280px;position:relative;">
                    <canvas id="chartLaba"></canvas>
                </div>
                <!-- Grafik ETR line -->
                <div style="margin-top:24px;padding-top:20px;border-top:1px solid rgba(241,245,249,0.8);">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                        <div style="width:32px;height:32px;background:rgba(225,29,72,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-chart-line" style="color:#e11d48;font-size:14px;"></i>
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#0f172a;margin:0;">Tren Nilai ETR (Effective Tax Rate)</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">Pergerakan ETR per klien</p>
                        </div>
                    </div>
                    <div style="height:180px;position:relative;">
                        <canvas id="chartEtr"></canvas>
                    </div>
                    
                    <div class="insight-box" style="margin-top:20px;">
                        <div class="insight-icon rose"><i class="fa-solid fa-scale-unbalanced"></i></div>
                        <div class="insight-content">
                            <h4>Indikasi Agresivitas Pajak</h4>
                            <p>Nilai ETR yang lebih rendah dari tarif pajak yang berlaku mengindikasikan adanya beban pajak yang lebih kecil secara proporsional terhadap laba, yang dapat menjadi sinyal tingginya tingkat agresivitas pajak pada klien tersebut.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>
    </div>


    <!-- ════ PANEL 3: HUTANG & PIUTANG ════ -->
    <div id="panel-hutang" style="padding:22px;display:none;">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title">Komponen Transfer Pricing &amp; Thin Capitalization</div>
            <span class="badge badge-blue">Data Mentah Laporan</span>
        </div>

        <div class="info-box" style="background:#fffbeb;border-color:#fde68a;margin-bottom:16px;">
            <i class="fa-solid fa-circle-info" style="color:#d97706;"></i>
            <p style="color:#92400e;"><strong>Keterangan:</strong> Transfer Pricing (X1) = <em>Piutang Berelasi / Total Piutang</em>. Thin Capitalization (X2) = <em>Total Hutang / Total Ekuitas (DER)</em>.</p>
        </div>

        <?php if (!$hasData): ?>
            <div class="empty-state"><i class="fa-solid fa-inbox"></i><p>Belum ada data laporan.</p></div>
        <?php else: ?>

        <?php foreach ($laporan_lengkap as $row): ?>
        <div style="border:1px solid #f1f5f9;border-radius:12px;overflow:hidden;margin-bottom:14px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
            <div style="background:#fafafa;padding:11px 18px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f1f5f9;">
                <span style="font-size:13px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:7px;">
                    <i class="fa-solid fa-building" style="color:#94a3b8;font-size:11px;"></i>
                    <?= htmlspecialchars($row['nama_perusahaan']) ?>
                    <span style="color:#3b5de7;">(<?= htmlspecialchars($row['kode_saham']) ?>)</span>
                </span>
                <span class="badge badge-gray">Tahun: <?= $row['tahun'] ?></span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;border-bottom:1px solid #f1f5f9;">
                <!-- Transfer Pricing -->
                <div style="padding:16px 18px;border-right:1px solid #f1f5f9;">
                    <p style="font-size:10px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:.07em;margin-bottom:10px;display:flex;align-items:center;gap:5px;">
                        <i class="fa-solid fa-money-bill-transfer"></i> RPT Ratio (X1)
                    </p>
                    <?php $rpt_pct = min(100, ($row['val_rpt_ratio'] * 100)); ?>
                    <div style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0;border-bottom:1px solid #f8fafc;">
                        <span style="color:#64748b;">Piutang Berelasi</span>
                        <span style="font-weight:600;">Rp <?= number_format($row['piutang_berelasi']??0,0,',','.') ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0;border-bottom:1px solid #f8fafc;">
                        <span style="color:#64748b;">Total Piutang</span>
                        <span style="font-weight:600;">Rp <?= number_format($row['total_piutang']??0,0,',','.') ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;">
                        <span style="color:#374151;font-weight:600;">Rasio RPT (X1)</span>
                        <span style="font-weight:800;color:#2563eb;font-size:15px;"><?= number_format($row['val_rpt_ratio'],4) ?></span>
                    </div>
                    <div style="background:#dbeafe;border-radius:99px;height:6px;margin-top:6px;overflow:hidden;">
                        <div style="background:#3b82f6;height:6px;border-radius:99px;width:<?= number_format($rpt_pct,1) ?>%;transition:width .4s;"></div>
                    </div>
                    <p style="font-size:10px;color:#94a3b8;margin-top:4px;"><?= number_format($rpt_pct,1) ?>% dari total piutang</p>
                </div>
                <!-- Thin Capitalization -->
                <div style="padding:16px 18px;">
                    <p style="font-size:10px;font-weight:700;color:#e11d48;text-transform:uppercase;letter-spacing:.07em;margin-bottom:10px;display:flex;align-items:center;gap:5px;">
                        <i class="fa-solid fa-building-columns"></i> DER / Leverage (X2)
                    </p>
                    <div style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0;border-bottom:1px solid #f8fafc;">
                        <span style="color:#64748b;">Total Hutang (Liabilitas)</span>
                        <span style="font-weight:600;">Rp <?= number_format($row['total_hutang']??0,0,',','.') ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;padding:3px 0;border-bottom:1px solid #f8fafc;">
                        <span style="color:#64748b;">Total Ekuitas</span>
                        <span style="font-weight:600;">Rp <?= number_format($row['total_ekuitas']??0,0,',','.') ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;">
                        <span style="color:#374151;font-weight:600;">Rasio DER (X2)</span>
                        <span style="font-weight:800;font-size:15px;color:<?= $row['val_der']>1?'#e11d48':'#16a34a' ?>;"><?= number_format($row['val_der'],4) ?></span>
                    </div>
                    <?php if($row['val_der']>2): ?>
                        <span class="badge badge-rose"><i class="fa-solid fa-triangle-exclamation" style="font-size:9px;"></i> DER Tinggi (&gt;2)</span>
                    <?php elseif($row['val_der']>1): ?>
                        <span class="badge badge-amber"><i class="fa-solid fa-circle-exclamation" style="font-size:9px;"></i> DER Moderat (1–2)</span>
                    <?php else: ?>
                        <span class="badge badge-green"><i class="fa-solid fa-circle-check" style="font-size:9px;"></i> DER Sehat (&lt;1)</span>
                    <?php endif; ?>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;border-top:1px solid #f1f5f9;">
                <div style="background:#eff6ff;padding:10px 18px;text-align:center;border-right:1px solid #dbeafe;">
                    <p style="font-size:10px;color:#93c5fd;font-weight:700;text-transform:uppercase;letter-spacing:.06em;">RPT Ratio</p>
                    <p style="font-size:16px;font-weight:800;color:#2563eb;"><?= number_format($row['val_rpt_ratio'],4) ?></p>
                </div>
                <div style="background:#fff1f2;padding:10px 18px;text-align:center;">
                    <p style="font-size:10px;color:#fca5a5;font-weight:700;text-transform:uppercase;letter-spacing:.06em;">DER / Leverage</p>
                    <p style="font-size:16px;font-weight:800;color:#e11d48;"><?= number_format($row['val_der'],4) ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- ── GRAFIK HUTANG PIUTANG ── -->
        <div class="chart-card" style="margin-top:24px;">
            <div class="chart-card-header" style="background:linear-gradient(135deg,#2563eb 0%,#6366f1 100%);">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-chart-bar" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Perbandingan Hutang &amp; Ekuitas per Klien</h3>
                        <p style="font-size:12px;color:rgba(255,255,255,0.75);margin:2px 0 0;">Total hutang (liabilitas) vs total ekuitas</p>
                    </div>
                </div>
            </div>
            <div class="chart-card-body">
                <div style="height:280px;position:relative;">
                    <canvas id="chartHutangEkuitas"></canvas>
                </div>
                
                <div class="insight-box">
                    <div class="insight-icon blue"><i class="fa-solid fa-building-columns"></i></div>
                    <div class="insight-content">
                        <h4>Struktur Modal Klien</h4>
                        <p>Perbandingan ini menunjukkan sumber pendanaan perusahaan. Jika garis hutang (merah) jauh lebih tinggi daripada ekuitas (biru), hal ini mengindikasikan risiko keuangan yang lebih besar (Thin Capitalization) karena tingginya ketergantungan pada dana eksternal.</p>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:20px;">
            <!-- Grafik RPT -->
            <div class="chart-card">
                <div class="chart-card-header" style="background:linear-gradient(135deg,#2563eb 0%,#3b82f6 100%);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-money-bill-transfer" style="color:#fff;font-size:14px;"></i>
                        </div>
                        <div>
                            <h3 style="font-size:13px;font-weight:700;color:#fff;margin:0;">Rasio RPT — Transfer Pricing (X1)</h3>
                            <p style="font-size:10px;color:rgba(255,255,255,0.7);margin:1px 0 0;">Piutang berelasi vs total piutang</p>
                        </div>
                    </div>
                </div>
                <div class="chart-card-body">
                    <div style="height:220px;position:relative;">
                        <canvas id="chartRptPiutang"></canvas>
                    </div>
                    
                    <div class="insight-box" style="margin-top:16px;">
                        <div class="insight-icon blue"><i class="fa-solid fa-users-viewfinder"></i></div>
                        <div class="insight-content">
                            <h4>Analisis Transfer Pricing (RPT)</h4>
                            <p>Semakin besar proporsi Piutang Berelasi terhadap Total Piutang, semakin tinggi kemungkinan terjadinya indikasi Transfer Pricing antar pihak berelasi.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Grafik DER -->
            <div class="chart-card">
                <div class="chart-card-header" style="background:linear-gradient(135deg,#d97706 0%,#f59e0b 100%);">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-building-columns" style="color:#fff;font-size:14px;"></i>
                        </div>
                        <div>
                            <h3 style="font-size:13px;font-weight:700;color:#fff;margin:0;">Rasio DER — Thin Capitalization (X2)</h3>
                            <p style="font-size:10px;color:rgba(255,255,255,0.7);margin:1px 0 0;">Batas referensi DER=1</p>
                        </div>
                    </div>
                </div>
                <div class="chart-card-body">
                    <div style="height:220px;position:relative;">
                        <canvas id="chartDer"></canvas>
                    </div>
                    
                    <div class="insight-box" style="margin-top:16px;">
                        <div class="insight-icon amber"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="insight-content">
                            <h4>Ambang Batas DER</h4>
                            <p>Batas wajar Debt-to-Equity Ratio (DER) menurut PMK adalah 4:1. Namun, rasio hutang yang sangat dominan (di atas 1.0) sudah patut menjadi perhatian karena beban bunga yang dapat menggerus laba kena pajak.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>
    </div>


    <!-- ════ PANEL 4: SALES GROWTH ════ -->
    <div id="panel-sales" style="padding:22px;display:none;">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
            <div class="card-title">Komponen Sales Growth (Z)</div>
            <span class="badge badge-green">Variabel Moderasi</span>
        </div>

        <div class="info-box" style="background:#f0fdf4;border-color:#bbf7d0;margin-bottom:16px;">
            <i class="fa-solid fa-circle-info" style="color:#16a34a;"></i>
            <p style="color:#166534;"><strong>Rumus:</strong> Sales Growth (Z) = <em>(Penjualan Tahun Ini − Tahun Lalu) / Tahun Lalu</em>. Nilai positif = pertumbuhan, negatif = penurunan.</p>
        </div>

        <?php if (!$hasData): ?>
            <div class="empty-state"><i class="fa-solid fa-inbox"></i><p>Belum ada data laporan.</p></div>
        <?php else: ?>

        <?php foreach ($laporan_lengkap as $row):
              $growth = $row['val_sales_growth'];
              $isPos  = $growth >= 0; ?>
        <div style="border:1px solid #f1f5f9;border-radius:12px;overflow:hidden;margin-bottom:14px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
            <div style="background:#fafafa;padding:11px 18px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f1f5f9;">
                <span style="font-size:13px;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:7px;">
                    <i class="fa-solid fa-building" style="color:#94a3b8;font-size:11px;"></i>
                    <?= htmlspecialchars($row['nama_perusahaan']) ?>
                    <span style="color:#3b5de7;">(<?= htmlspecialchars($row['kode_saham']) ?>)</span>
                </span>
                <span class="badge badge-gray">Tahun: <?= $row['tahun'] ?></span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;border-bottom:1px solid #f1f5f9;">
                <div style="padding:14px 18px;border-right:1px solid #f1f5f9;">
                    <p style="font-size:10px;font-weight:700;color:#3b82f6;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                        <i class="fa-solid fa-calendar-check"></i> Penjualan Tahun Ini
                    </p>
                    <p style="font-size:16px;font-weight:800;color:#1e293b;">Rp <?= number_format($row['penjualan_tahun_ini']??0,0,',','.') ?></p>
                    <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Periode <?= $row['tahun'] ?></p>
                </div>
                <div style="padding:14px 18px;border-right:1px solid #f1f5f9;">
                    <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                        <i class="fa-solid fa-calendar"></i> Penjualan Tahun Lalu
                    </p>
                    <p style="font-size:16px;font-weight:800;color:#1e293b;">Rp <?= number_format($row['penjualan_tahun_lalu']??0,0,',','.') ?></p>
                    <p style="font-size:11px;color:#94a3b8;margin-top:2px;">Periode <?= $row['tahun']-1 ?></p>
                </div>
                <div style="padding:14px 18px;">
                    <p style="font-size:10px;font-weight:700;color:<?= $isPos?'#16a34a':'#dc2626' ?>;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px;display:flex;align-items:center;gap:5px;">
                        <i class="fa-solid <?= $isPos?'fa-arrow-trend-up':'fa-arrow-trend-down' ?>"></i> Sales Growth (Z)
                    </p>
                    <p style="font-size:16px;font-weight:800;color:<?= $isPos?'#16a34a':'#dc2626' ?>;"><?= ($isPos?'+':'').number_format($growth,4) ?></p>
                    <p style="font-size:11px;color:<?= $isPos?'#16a34a':'#dc2626' ?>;margin-top:2px;"><?= $isPos?'Pertumbuhan Positif':'Pertumbuhan Negatif' ?> (<?= number_format(abs($growth)*100,2) ?>%)</p>
                </div>
            </div>
            <div style="background:<?= $isPos?'#f0fdf4':'#fff1f2' ?>;padding:10px 18px;text-align:center;">
                <p style="font-size:13px;font-weight:600;color:<?= $isPos?'#166534':'#be123c' ?>;">
                    <i class="fa-solid <?= $isPos?'fa-arrow-trend-up':'fa-arrow-trend-down' ?>" style="font-size:11px;margin-right:4px;"></i>
                    Selisih: Rp <?= number_format(abs(($row['penjualan_tahun_ini']??0)-($row['penjualan_tahun_lalu']??0)),0,',','.') ?>
                    (<?= $isPos?'Naik':'Turun' ?> <?= number_format(abs($growth)*100,2) ?>%)
                </p>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- ── GRAFIK SALES GROWTH ── -->
        <div class="chart-card" style="margin-top:24px;">
            <div class="chart-card-header" style="background:linear-gradient(135deg,#059669 0%,#10b981 100%);">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-chart-bar" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Perbandingan Penjualan Tahun Ini vs Tahun Lalu</h3>
                        <p style="font-size:12px;color:rgba(255,255,255,0.75);margin:2px 0 0;">Grouped bar chart untuk setiap klien</p>
                    </div>
                </div>
            </div>
            <div class="chart-card-body">
                <div style="height:280px;position:relative;">
                    <canvas id="chartPenjualan"></canvas>
                </div>
                
                <div class="insight-box">
                    <div class="insight-icon green"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="insight-content">
                        <h4>Kinerja Penjualan</h4>
                        <p>Membandingkan secara absolut total pendapatan yang diperoleh tahun ini dengan tahun sebelumnya. Kenaikan memberikan sinyal pertumbuhan operasional yang sehat.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="chart-card" style="margin-top:20px;">
            <div class="chart-card-header" style="background:linear-gradient(135deg,#047857 0%,#059669 100%);">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-chart-line" style="color:#fff;font-size:18px;"></i>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:#fff;margin:0;">Nilai Sales Growth (Z) per Klien</h3>
                        <p style="font-size:12px;color:rgba(255,255,255,0.75);margin:2px 0 0;">Bar positif = pertumbuhan, bar negatif = penurunan</p>
                    </div>
                </div>
            </div>
            <div class="chart-card-body">
                <div style="height:240px;position:relative;">
                    <canvas id="chartGrowthBar"></canvas>
                </div>
                
                <div class="insight-box" style="margin-top:16px;">
                    <div class="insight-icon green"><i class="fa-solid fa-percent"></i></div>
                    <div class="insight-content">
                        <h4>Persentase Sales Growth</h4>
                        <p>Grafik ini menunjukkan laju pertumbuhan secara persentase. Pertumbuhan penjualan yang fluktuatif bisa menjadi salah satu variabel pendorong manajemen untuk mengatur skema pajak.</p>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>
    </div>

</div><!-- end card tabsCard -->

<!-- Print-only footer -->
<div id="printFooter" style="display:none;">
    <div style="border-top:2px solid #e2e8f0;padding:16px 24px;margin-top:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <p style="font-size:10px;color:#94a3b8;margin:0;"><strong>Disclaimer:</strong> Hasil yang ditampilkan merupakan analisis/indikasi dan alat bantu, bukan keputusan hukum atau pajak final.</p>
                <p style="font-size:10px;color:#94a3b8;margin:2px 0 0;">Untuk keperluan pelaporan pajak, silakan merujuk pada regulasi yang berlaku dan berkonsultasi dengan konsultan pajak profesional.</p>
            </div>
            <div style="text-align:right;">
                <p style="font-size:10px;color:#94a3b8;margin:0;">Fynca Analytics &copy; <?= date('Y') ?></p>
            </div>
        </div>
    </div>
</div>

</div><!-- end #printableArea -->


<script>
// ── Data dari PHP ──────────────────────────────────────────
var labels    = <?= json_encode($chartLabels) ?>;
var etrData   = <?= json_encode($chartEtr) ?>;
var rptData   = <?= json_encode($chartRpt) ?>;
var derData   = <?= json_encode($chartDer) ?>;
var growData  = <?= json_encode($chartGrowth) ?>;
var penjIni   = <?= json_encode($chartPenjIni) ?>;
var penjLalu  = <?= json_encode($chartPenjLalu) ?>;
var piutRel   = <?= json_encode($chartPiutRel) ?>;
var totPiut   = <?= json_encode($chartTotalPiut) ?>;
var hutang    = <?= json_encode($chartHutang) ?>;
var ekuitas   = <?= json_encode($chartEkuitas) ?>;
var labaData  = <?= json_encode($chartLaba) ?>;
var bebanData = <?= json_encode($chartBeban) ?>;

var hasData = labels.length > 0;

// ── Helpers ────────────────────────────────────────────────
var FONT = { family: 'Inter', size: 11, weight: '500' };
var gridColor = 'rgba(241, 245, 249, 0.8)';

function createGradient(ctx, colorStart, colorEnd) {
    var gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, colorStart);
    gradient.addColorStop(1, colorEnd);
    return gradient;
}

function formatCurrencyShort(v) {
    if (v >= 1e12) return (v / 1e12).toFixed(1) + 'T';
    if (v >= 1e9) return (v / 1e9).toFixed(1) + 'M';
    if (v >= 1e6) return (v / 1e6).toFixed(0) + 'jt';
    return v;
}

function baseOpts(opts) {
    var defaultPlugins = { 
        legend: { position:'bottom', labels:{ usePointStyle:true, boxWidth:8, font:FONT, padding:20, color: '#475569' } },
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
                // Handle different magnitudes intelligently
                if (Math.abs(value) >= 1000000) {
                    return formatCurrencyShort(value);
                } else if (Math.abs(value) < 10 && Math.abs(value) > 0) {
                    // Small decimals (like ETR, DER)
                    return value.toFixed(2);
                } else if (value === 0) {
                    return '0';
                }
                return value;
            }
        }
    };

    var mergedPlugins = Object.assign({}, defaultPlugins, (opts && opts.plugins) || {});
    
    return Object.assign({
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: mergedPlugins,
        scales: {
            x: { grid:{display:false}, ticks:{font:FONT,color:'#64748b'} },
            y: { border: { display: false }, grid:{color:gridColor, drawTicks: false}, ticks:{font:FONT,color:'#64748b', padding: 10} }
        }
    }, opts || {}, { plugins: mergedPlugins });
}

document.addEventListener('DOMContentLoaded', function() {
    if (!hasData) return;
    
    Chart.register(ChartDataLabels);

    // ── 1. Chart Rasio (multi-line) ──────────────────────────
    var ctxRasio = document.getElementById('chartRasio').getContext('2d');
    var gradEtr = createGradient(ctxRasio, 'rgba(225,29,72,0.15)', 'rgba(225,29,72,0.0)');
    new Chart(ctxRasio, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label:'ETR (Y)',    data:etrData,  borderColor:'#e11d48', backgroundColor:gradEtr,  borderWidth:3, tension:0.4, fill:true,  pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#e11d48', pointBorderWidth:2 },
                { label:'RPT (X1)',   data:rptData,  borderColor:'#3b82f6', backgroundColor:'transparent', borderWidth:3, tension:0.4, fill:false, pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#3b82f6', pointBorderWidth:2 },
                { label:'DER (X2)',   data:derData,  borderColor:'#f59e0b', backgroundColor:'transparent', borderWidth:3, tension:0.4, fill:false, pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#f59e0b', pointBorderWidth:2, borderDash:[5,5] },
                { label:'Growth (Z)', data:growData, borderColor:'#10b981', backgroundColor:'transparent', borderWidth:3, tension:0.4, fill:false, pointRadius:4, pointHoverRadius:6, pointBackgroundColor:'#fff', pointBorderColor:'#10b981', pointBorderWidth:2 }
            ]
        },
        options: baseOpts({ plugins:{ legend:{display:false} } })
    });

    // ── 2. Chart Laba vs Beban Pajak (grouped bar) ──────────
    var ctxLaba = document.getElementById('chartLaba').getContext('2d');
    new Chart(ctxLaba, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label:'Laba Sebelum Pajak', data:labaData,  backgroundColor:createGradient(ctxLaba, 'rgba(16,185,129,0.9)', 'rgba(16,185,129,0.5)'), borderRadius:6, borderSkipped:false, barPercentage:0.6 },
                { label:'Beban Pajak',         data:bebanData, backgroundColor:createGradient(ctxLaba, 'rgba(225,29,72,0.9)', 'rgba(225,29,72,0.5)'),  borderRadius:6, borderSkipped:false, barPercentage:0.6 }
            ]
        },
        options: baseOpts({ scales:{ x:{grid:{display:false},ticks:{font:FONT,color:'#64748b'}}, y:{border: { display: false }, grid:{color:gridColor, drawTicks: false},ticks:{font:FONT,color:'#64748b',padding: 10,callback:function(v){return v>=1e9?'Rp '+(v/1e9).toFixed(1)+'M':v>=1e6?'Rp '+(v/1e6).toFixed(1)+'jt':'Rp '+v;}}} } })
    });

    // ── 3. Chart ETR line ────────────────────────────────────
    var ctxEtrLine = document.getElementById('chartEtr').getContext('2d');
    new Chart(ctxEtrLine, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label:'ETR (Y)',
                data: etrData,
                borderColor:'#e11d48', backgroundColor:createGradient(ctxEtrLine, 'rgba(225,29,72,0.15)', 'rgba(225,29,72,0.0)'),
                borderWidth:3, tension:0.4, fill:true,
                pointRadius:5, pointHoverRadius:7, pointBackgroundColor:'#fff', pointBorderColor:'#e11d48', pointBorderWidth:2.5
            }]
        },
        options: baseOpts({ plugins:{ legend:{display:false} } })
    });

    // ── 4. Chart Hutang vs Ekuitas ───────────────────────────
    var ctxHutang = document.getElementById('chartHutangEkuitas').getContext('2d');
    new Chart(ctxHutang, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label:'Total Hutang (Liabilitas)', data:hutang,   backgroundColor:createGradient(ctxHutang, 'rgba(225,29,72,0.9)', 'rgba(225,29,72,0.5)'),  borderRadius:6, borderSkipped:false, barPercentage:0.6 },
                { label:'Total Ekuitas',              data:ekuitas,  backgroundColor:createGradient(ctxHutang, 'rgba(59,130,246,0.9)', 'rgba(59,130,246,0.5)'), borderRadius:6, borderSkipped:false, barPercentage:0.6 }
            ]
        },
        options: baseOpts({ scales:{ x:{grid:{display:false},ticks:{font:FONT,color:'#64748b'}}, y:{border: { display: false }, grid:{color:gridColor, drawTicks: false},ticks:{font:FONT,color:'#64748b',padding: 10,callback:function(v){return v>=1e12?'Rp '+(v/1e12).toFixed(1)+'T':v>=1e9?'Rp '+(v/1e9).toFixed(1)+'M':v>=1e6?'Rp '+(v/1e6).toFixed(0)+'jt':'Rp '+v;}}} } })
    });

    // ── 5. Chart RPT & Piutang ───────────────────────────────
    var ctxRptPiut = document.getElementById('chartRptPiutang').getContext('2d');
    new Chart(ctxRptPiut, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label:'Piutang Berelasi', data:piutRel,  backgroundColor:createGradient(ctxRptPiut, 'rgba(59,130,246,0.9)', 'rgba(59,130,246,0.5)'), borderRadius:5, borderSkipped:false, barPercentage:0.6 },
                { label:'Total Piutang',    data:totPiut,  backgroundColor:'rgba(59,130,246,0.15)',  borderRadius:5, borderSkipped:false, barPercentage:0.6 }
            ]
        },
        options: baseOpts({ scales:{ x:{grid:{display:false},ticks:{font:FONT,color:'#64748b'}}, y:{border: { display: false }, grid:{color:gridColor, drawTicks: false},ticks:{font:FONT,color:'#64748b',padding: 10,callback:function(v){return v>=1e12?'Rp '+(v/1e12).toFixed(1)+'T':v>=1e9?'Rp '+(v/1e9).toFixed(1)+'M':v>=1e6?'Rp '+(v/1e6).toFixed(0)+'jt':'Rp '+v;}}} } })
    });

    // ── 6. Chart DER ─────────────────────────────────────────
    var ctxDer = document.getElementById('chartDer').getContext('2d');
    new Chart(ctxDer, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { 
                    label:'DER (X2)', 
                    data:derData, 
                    backgroundColor:derData.map(function(v){ 
                        if(v > 2) return createGradient(ctxDer, 'rgba(225,29,72,0.9)', 'rgba(225,29,72,0.5)');
                        if(v > 1) return createGradient(ctxDer, 'rgba(245,158,11,0.9)', 'rgba(245,158,11,0.5)');
                        return createGradient(ctxDer, 'rgba(16,185,129,0.9)', 'rgba(16,185,129,0.5)');
                    }), 
                    borderRadius:6, borderSkipped:false, barPercentage: 0.6 
                },
                { label:'Batas DER=1', data:labels.map(function(){return 1;}), type:'line', borderColor:'#ef4444', borderDash:[5,5], borderWidth:2, pointRadius:0, fill:false, tension: 0 }
            ]
        },
        options: baseOpts()
    });

    // ── 7. Chart Penjualan (grouped bar) ─────────────────────
    var ctxPenjualan = document.getElementById('chartPenjualan').getContext('2d');
    new Chart(ctxPenjualan, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label:'Penjualan Tahun Ini',  data:penjIni,  backgroundColor:createGradient(ctxPenjualan, 'rgba(16,185,129,0.9)', 'rgba(16,185,129,0.5)'), borderRadius:6, borderSkipped:false, barPercentage:0.6 },
                { label:'Penjualan Tahun Lalu', data:penjLalu, backgroundColor:'rgba(16,185,129,0.15)', borderRadius:6, borderSkipped:false, barPercentage:0.6 }
            ]
        },
        options: baseOpts({ scales:{ x:{grid:{display:false},ticks:{font:FONT,color:'#64748b'}}, y:{border: { display: false }, grid:{color:gridColor, drawTicks: false},ticks:{font:FONT,color:'#64748b',padding: 10,callback:function(v){return v>=1e12?'Rp '+(v/1e12).toFixed(1)+'T':v>=1e9?'Rp '+(v/1e9).toFixed(1)+'M':v>=1e6?'Rp '+(v/1e6).toFixed(0)+'jt':'Rp '+v;}}} } })
    });

    // ── 8. Chart Growth Bar (positif/negatif - Doughnut) ────────────────
    var ctxGrow = document.getElementById('chartGrowthBar').getContext('2d');
    var palette = ['#3b82f6', '#f59e0b', '#10b981', '#8b5cf6', '#ec4899', '#14b8a6', '#f43f5e', '#6366f1', '#f97316', '#06b6d4'];
    new Chart(ctxGrow, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales Growth (Z)',
                data: growData.map(function(v) { return Math.abs(v); }),
                backgroundColor: growData.map(function(v, i){ return palette[i % palette.length]; }),
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
                            var val = growData[context.dataIndex] * 100;
                            return context.label + ': ' + (val > 0 ? '+' : '') + val.toFixed(2) + '%';
                        }
                    }
                },
                datalabels: {
                    color: '#fff',
                    font: { family: 'Inter', size: 11, weight: 'bold' },
                    formatter: function(value, context) {
                        var v = growData[context.dataIndex];
                        return (v * 100).toFixed(1) + '%';
                    }
                }
            }
        }
    });

}); // end DOMContentLoaded

// ── Tab switching ────────────────────────────────────────────
function switchTab(name) {
    ['rasio','laba','hutang','sales'].forEach(function(t) {
        document.getElementById('panel-'+t).style.display = 'none';
        document.getElementById('tab-'+t).classList.remove('active');
    });
    document.getElementById('panel-'+name).style.display = 'block';
    document.getElementById('tab-'+name).classList.add('active');
}

// ══════════════════════════════════════════════════════════════
// ── Generate / Filter ────────────────────────────────────────
// ══════════════════════════════════════════════════════════════
function generateLaporan() {
    var dari   = document.getElementById('filterDari').value.trim();
    var sampai = document.getElementById('filterSampai').value.trim();
    var emiten = document.getElementById('filterEmiten').value;

    var params = [];
    if (dari)   params.push('dari=' + encodeURIComponent(dari));
    if (sampai) params.push('sampai=' + encodeURIComponent(sampai));
    if (emiten) params.push('emiten=' + encodeURIComponent(emiten));

    var url = '/laporan';
    if (params.length > 0) url += '?' + params.join('&');
    window.location.href = url;
}

// ══════════════════════════════════════════════════════════════
// ── Export PDF (Profesional) ─────────────────────────────────
// ══════════════════════════════════════════════════════════════
function exportPDF() {
    var btn = document.getElementById('btnExportPdf');
    var originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...';
    btn.disabled = true;

    // Build professional PDF content
    var pdfContent = document.createElement('div');
    pdfContent.style.fontFamily = 'Inter, Helvetica, Arial, sans-serif';
    pdfContent.style.color = '#1e293b';
    pdfContent.style.fontSize = '11px';
    pdfContent.style.lineHeight = '1.5';
    pdfContent.style.padding = '0';

    // Get filter info
    var filterDari = document.getElementById('filterDari').value || '-';
    var filterSampai = document.getElementById('filterSampai').value || '-';
    var filterEmiten = document.getElementById('filterEmiten');
    var emitenText = filterEmiten.selectedIndex > 0 ? filterEmiten.options[filterEmiten.selectedIndex].text : 'Semua Klien';
    var now = new Date();
    var dateStr = now.toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'}) + ', ' + now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}) + ' WIB';

    // ── Header ──
    pdfContent.innerHTML = '<div style="margin-bottom:24px;">' +
        '<table style="width:100%;border-collapse:collapse;">' +
            '<tr>' +
                '<td style="vertical-align:middle;width:60px;">' +
                    '<div style="width:48px;height:48px;background:linear-gradient(135deg,#3b5de7,#6366f1);border-radius:12px;display:flex;align-items:center;justify-content:center;">' +
                        '<span style="color:#fff;font-size:18px;font-weight:900;line-height:48px;text-align:center;display:block;width:100%;">FA</span>' +
                    '</div>' +
                '</td>' +
                '<td style="vertical-align:middle;padding-left:12px;">' +
                    '<div style="font-size:20px;font-weight:800;color:#0f172a;margin:0;">Laporan Analisis Data</div>' +
                    '<div style="font-size:12px;color:#3b5de7;font-weight:600;margin-top:2px;">Fynca Analytics — Analyst Console</div>' +
                '</td>' +
                '<td style="text-align:right;vertical-align:middle;">' +
                    '<div style="font-size:10px;color:#64748b;">Dicetak pada:</div>' +
                    '<div style="font-size:11px;font-weight:600;color:#374151;">' + dateStr + '</div>' +
                '</td>' +
            '</tr>' +
        '</table>' +
        '<div style="border-bottom:3px solid #3b5de7;margin-top:12px;"></div>' +
        '<div style="display:flex;gap:16px;margin-top:10px;">' +
            '<span style="font-size:10px;color:#475569;background:#f1f5f9;padding:3px 10px;border-radius:4px;"><strong>Periode:</strong> ' + filterDari + ' s/d ' + filterSampai + '</span>' +
            '<span style="font-size:10px;color:#475569;background:#f1f5f9;padding:3px 10px;border-radius:4px;"><strong>Klien:</strong> ' + emitenText + '</span>' +
        '</div>' +
    '</div>';

    // ── Table: Metrik Analitik ──
    var tableRows = '';
    var rasioTable = document.querySelector('#panel-rasio table.tbl');
    if (rasioTable) {
        var rows = rasioTable.querySelectorAll('tbody tr');
        var no = 1;
        for (var i = 0; i < rows.length; i++) {
            var cells = rows[i].querySelectorAll('td');
            if (cells.length >= 5) {
                var bgColor = no % 2 === 0 ? '#f8fafc' : '#ffffff';
                tableRows += '<tr style="background:' + bgColor + ';">' +
                    '<td style="padding:8px 10px;border-bottom:1px solid #f1f5f9;text-align:center;color:#94a3b8;font-size:10px;width:30px;">' + no + '</td>' +
                    '<td style="padding:8px 10px;border-bottom:1px solid #f1f5f9;font-weight:600;">' + cells[0].textContent.trim() + '</td>' +
                    '<td style="padding:8px 10px;border-bottom:1px solid #f1f5f9;text-align:center;font-weight:700;color:#e11d48;">' + cells[1].textContent.trim() + '</td>' +
                    '<td style="padding:8px 10px;border-bottom:1px solid #f1f5f9;text-align:center;">' + cells[2].textContent.trim() + '</td>' +
                    '<td style="padding:8px 10px;border-bottom:1px solid #f1f5f9;text-align:center;">' + cells[3].textContent.trim() + '</td>' +
                    '<td style="padding:8px 10px;border-bottom:1px solid #f1f5f9;text-align:center;font-weight:600;">' + cells[4].textContent.trim() + '</td>' +
                '</tr>';
                no++;
            }
        }
    }

    pdfContent.innerHTML += '<div style="margin-bottom:20px;">' +
        '<div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:10px;display:flex;align-items:center;gap:8px;">' +
            '<div style="width:4px;height:20px;background:linear-gradient(180deg,#3b5de7,#6366f1);border-radius:3px;"></div>' +
            'Tabel Metrik Analitik — Rasio Keuangan' +
        '</div>' +
        '<table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">' +
            '<thead>' +
                '<tr style="background:linear-gradient(135deg,#3b5de7,#6366f1);">' +
                    '<th style="padding:10px 10px;color:#fff;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;text-align:center;width:30px;">No</th>' +
                    '<th style="padding:10px 10px;color:#fff;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;text-align:left;">Klien & Tahun</th>' +
                    '<th style="padding:10px 10px;color:#fff;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;text-align:center;">ETR (Y)</th>' +
                    '<th style="padding:10px 10px;color:#fff;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;text-align:center;">Transfer Pricing (X1)</th>' +
                    '<th style="padding:10px 10px;color:#fff;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;text-align:center;">Thin Cap (X2)</th>' +
                    '<th style="padding:10px 10px;color:#fff;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;text-align:center;">Sales Growth (Z)</th>' +
                '</tr>' +
            '</thead>' +
            '<tbody>' + tableRows + '</tbody>' +
        '</table>' +
    '</div>';

    // ── Summary Statistics ──
    if (typeof etrData !== 'undefined' && etrData.length > 0) {
        var avgEtr = etrData.reduce(function(a,b){return a+b;},0) / etrData.length;
        var avgRpt = rptData.reduce(function(a,b){return a+b;},0) / rptData.length;
        var avgDer = derData.reduce(function(a,b){return a+b;},0) / derData.length;
        var avgGrow = growData.reduce(function(a,b){return a+b;},0) / growData.length;

        pdfContent.innerHTML += '<div style="margin-bottom:20px;">' +
            '<div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:10px;display:flex;align-items:center;gap:8px;">' +
                '<div style="width:4px;height:20px;background:linear-gradient(180deg,#10b981,#059669);border-radius:3px;"></div>' +
                'Ringkasan Statistik' +
            '</div>' +
            '<table style="width:100%;border-collapse:collapse;">' +
                '<tr>' +
                    '<td style="width:25%;padding:12px 16px;background:#fff1f2;border-radius:8px;text-align:center;">' +
                        '<div style="font-size:10px;font-weight:700;color:#e11d48;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Rata-rata ETR (Y)</div>' +
                        '<div style="font-size:18px;font-weight:800;color:#be123c;">' + (avgEtr * 100).toFixed(2) + '%</div>' +
                    '</td>' +
                    '<td style="width:4px;"></td>' +
                    '<td style="width:25%;padding:12px 16px;background:#eff6ff;border-radius:8px;text-align:center;">' +
                        '<div style="font-size:10px;font-weight:700;color:#3b82f6;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Rata-rata RPT (X1)</div>' +
                        '<div style="font-size:18px;font-weight:800;color:#1d4ed8;">' + (avgRpt * 100).toFixed(2) + '%</div>' +
                    '</td>' +
                    '<td style="width:4px;"></td>' +
                    '<td style="width:25%;padding:12px 16px;background:#fffbeb;border-radius:8px;text-align:center;">' +
                        '<div style="font-size:10px;font-weight:700;color:#d97706;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Rata-rata DER (X2)</div>' +
                        '<div style="font-size:18px;font-weight:800;color:#b45309;">' + avgDer.toFixed(4) + 'x</div>' +
                    '</td>' +
                    '<td style="width:4px;"></td>' +
                    '<td style="width:25%;padding:12px 16px;background:#f0fdf4;border-radius:8px;text-align:center;">' +
                        '<div style="font-size:10px;font-weight:700;color:#16a34a;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Rata-rata Growth (Z)</div>' +
                        '<div style="font-size:18px;font-weight:800;color:#15803d;">' + (avgGrow >= 0 ? '+' : '') + (avgGrow * 100).toFixed(2) + '%</div>' +
                    '</td>' +
                '</tr>' +
            '</table>' +
        '</div>';
    }

    // ── Footer ──
    pdfContent.innerHTML += '<div style="border-top:2px solid #e2e8f0;padding-top:14px;margin-top:20px;">' +
        '<table style="width:100%;border-collapse:collapse;">' +
            '<tr>' +
                '<td style="vertical-align:top;">' +
                    '<div style="font-size:9px;color:#94a3b8;line-height:1.5;"><strong>Disclaimer:</strong> Hasil yang ditampilkan merupakan analisis/indikasi dan alat bantu, bukan keputusan hukum atau pajak final. Untuk keperluan pelaporan pajak, silakan merujuk pada regulasi yang berlaku dan berkonsultasi dengan konsultan pajak profesional.</div>' +
                '</td>' +
                '<td style="width:120px;text-align:right;vertical-align:top;">' +
                    '<div style="font-size:9px;color:#94a3b8;">Fynca Analytics &copy; ' + now.getFullYear() + '</div>' +
                '</td>' +
            '</tr>' +
        '</table>' +
    '</div>';

    // Generate PDF
    var opt = {
        margin:      [12, 14, 12, 14],
        filename:    'Laporan_Analisis_' + now.toISOString().slice(0,10) + '.pdf',
        image:       { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, logging: false, scrollY: 0 },
        jsPDF:       { unit: 'mm', format: 'a4', orientation: 'landscape' },
        pagebreak:   { mode: ['avoid-all', 'css', 'legacy'] }
    };

    html2pdf().set(opt).from(pdfContent).save().then(function() {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }).catch(function(err) {
        console.error('PDF export error:', err);
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Gagal mengekspor PDF. Silakan coba lagi.');
    });
}

// ══════════════════════════════════════════════════════════════
// ── Export Excel (Profesional) ───────────────────────────────
// ══════════════════════════════════════════════════════════════
function exportExcel() {
    var btn = document.getElementById('btnExportExcel');
    var originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...';
    btn.disabled = true;

    try {
        var wb = XLSX.utils.book_new();
        var now = new Date();
        var dateStr = now.toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'});

        // Get filter info
        var filterDari = document.getElementById('filterDari').value || '-';
        var filterSampai = document.getElementById('filterSampai').value || '-';
        var filterEmiten = document.getElementById('filterEmiten');
        var emitenText = filterEmiten.selectedIndex > 0 ? filterEmiten.options[filterEmiten.selectedIndex].text : 'Semua Klien';

        // ── Sheet 1: Rasio Analitik ──
        var wsData = [
            ['LAPORAN ANALISIS DATA — FYNCA ANALYTICS'],
            ['Analisis keuangan dan rasio lengkap perusahaan properti'],
            [''],
            ['Periode', filterDari + ' s/d ' + filterSampai, '', 'Klien', emitenText],
            ['Tanggal Cetak', dateStr, '', 'Total Data', labels.length + ' laporan'],
            [''],
            ['No', 'Kode Saham & Tahun', 'ETR (Y)', 'Transfer Pricing (X1)', 'Thin Cap / DER (X2)', 'Sales Growth (Z)']
        ];

        // Get data from table
        var rasioTable = document.querySelector('#panel-rasio table.tbl');
        if (rasioTable) {
            var rows = rasioTable.querySelectorAll('tbody tr');
            var no = 1;
            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].querySelectorAll('td');
                if (cells.length >= 5) {
                    wsData.push([
                        no,
                        cells[0].textContent.trim(),
                        parseFloat(cells[1].textContent.trim()) || 0,
                        parseFloat(cells[2].textContent.trim()) || 0,
                        parseFloat(cells[3].textContent.trim()) || 0,
                        parseFloat(cells[4].textContent.trim()) || 0
                    ]);
                    no++;
                }
            }
        }

        // Add summary row
        if (typeof etrData !== 'undefined' && etrData.length > 0) {
            var avgEtr = etrData.reduce(function(a,b){return a+b;},0) / etrData.length;
            var avgRpt = rptData.reduce(function(a,b){return a+b;},0) / rptData.length;
            var avgDer = derData.reduce(function(a,b){return a+b;},0) / derData.length;
            var avgGrow = growData.reduce(function(a,b){return a+b;},0) / growData.length;
            wsData.push([]);
            wsData.push(['', 'RATA-RATA', avgEtr, avgRpt, avgDer, avgGrow]);
        }

        // Add disclaimer
        wsData.push([]);
        wsData.push(['Disclaimer: Hasil yang ditampilkan merupakan analisis/indikasi dan alat bantu, bukan keputusan hukum atau pajak final.']);

        var ws1 = XLSX.utils.aoa_to_sheet(wsData);

        // Set column widths
        ws1['!cols'] = [
            {wch: 5},   // No
            {wch: 30},  // Kode Saham & Tahun
            {wch: 15},  // ETR
            {wch: 22},  // Transfer Pricing
            {wch: 22},  // Thin Cap
            {wch: 18}   // Sales Growth
        ];

        // Merge header cells
        ws1['!merges'] = [
            {s:{r:0,c:0}, e:{r:0,c:5}},  // Title
            {s:{r:1,c:0}, e:{r:1,c:5}},  // Subtitle
        ];

        XLSX.utils.book_append_sheet(wb, ws1, 'Rasio Analitik');

        // ── Sheet 2: Data Keuangan Detail ──
        var wsDetail = [
            ['DETAIL DATA KEUANGAN — FYNCA ANALYTICS'],
            [''],
            ['No', 'Kode Saham & Tahun', 'Laba Sebelum Pajak (Rp)', 'Beban Pajak (Rp)', 'Piutang Berelasi (Rp)', 'Total Piutang (Rp)', 'Total Hutang (Rp)', 'Total Ekuitas (Rp)', 'Penjualan Thn Ini (Rp)', 'Penjualan Thn Lalu (Rp)']
        ];

        if (typeof labels !== 'undefined') {
            for (var j = 0; j < labels.length; j++) {
                wsDetail.push([
                    j + 1,
                    labels[j],
                    labaData[j] || 0,
                    bebanData[j] || 0,
                    piutRel[j] || 0,
                    totPiut[j] || 0,
                    hutang[j] || 0,
                    ekuitas[j] || 0,
                    penjIni[j] || 0,
                    penjLalu[j] || 0
                ]);
            }
        }

        var ws2 = XLSX.utils.aoa_to_sheet(wsDetail);
        ws2['!cols'] = [
            {wch: 5},   // No
            {wch: 25},  // Kode
            {wch: 22},  // Laba
            {wch: 18},  // Beban
            {wch: 22},  // Piutang Berelasi
            {wch: 20},  // Total Piutang
            {wch: 20},  // Total Hutang
            {wch: 20},  // Total Ekuitas
            {wch: 22},  // Penjualan Thn Ini
            {wch: 22}   // Penjualan Thn Lalu
        ];
        ws2['!merges'] = [{s:{r:0,c:0}, e:{r:0,c:9}}];

        XLSX.utils.book_append_sheet(wb, ws2, 'Data Keuangan');

        // Save file
        var filename = 'Laporan_Analisis_' + now.toISOString().slice(0,10) + '.xlsx';
        XLSX.writeFile(wb, filename);

        btn.innerHTML = originalText;
        btn.disabled = false;
    } catch(err) {
        console.error('Excel export error:', err);
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Gagal mengekspor Excel. Silakan coba lagi.');
    }
}

// ══════════════════════════════════════════════════════════════
// ── Print (Format mengikuti PDF) ─────────────────────────────
// ══════════════════════════════════════════════════════════════
function printLaporan() {
    var printHeader = document.getElementById('printHeader');
    var printFooter = document.getElementById('printFooter');
    printHeader.style.display = 'block';
    printFooter.style.display = 'block';
    setTimeout(function() {
        window.print();
        setTimeout(function() {
            printHeader.style.display = 'none';
            printFooter.style.display = 'none';
        }, 800);
    }, 300);
}
</script>

<!-- ═══ PRINT STYLES ═══ -->
<style>
@media print {
    /* Hide non-printable elements */
    aside, header, .topbar, nav,
    .ph, .alert-message,
    #btnExportPdf, #btnExportExcel,
    .btn, .tabs-bar, .chart-card,
    .chart-card-header, .chart-card-body,
    canvas, .insight-box, .info-box,
    #btn-scroll-top {
        display: none !important;
    }

    /* Show print header & footer */
    #printHeader { display: block !important; }
    #printFooter { display: block !important; }

    /* Reset layout */
    body, main {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        overflow: visible !important;
        height: auto !important;
        font-family: 'Inter', Helvetica, Arial, sans-serif !important;
    }
    .flex, .flex-1 {
        display: block !important;
        overflow: visible !important;
    }
    main {
        padding: 16px !important;
    }
    #printableArea {
        width: 100% !important;
    }

    /* Card styling */
    .card, #tabsCard {
        box-shadow: none !important;
        border: none !important;
        break-inside: avoid;
    }

    /* Show all panels for print */
    #panel-rasio { display: block !important; }
    #panel-laba { display: block !important; }
    #panel-hutang { display: block !important; }
    #panel-sales { display: block !important; }

    /* Hide chart-related elements within panels */
    #panel-laba .chart-card,
    #panel-hutang .chart-card,
    #panel-sales .chart-card { display: none !important; }

    /* Table styling */
    .tbl {
        font-size: 11px !important;
        width: 100% !important;
    }
    .tbl td, .tbl th {
        padding: 8px 10px !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .tbl thead tr {
        background: #f8fafc !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .tbl th {
        font-weight: 700 !important;
        font-size: 10px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #475569 !important;
    }

    /* Badge styling */
    .badge {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Page breaks */
    #panel-laba, #panel-hutang, #panel-sales {
        page-break-before: always;
    }

    /* Panel titles */
    .card-title {
        font-size: 16px !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        border-bottom: 2px solid #3b5de7 !important;
        padding-bottom: 8px !important;
        margin-bottom: 12px !important;
    }
}
</style>

<?= $this->endSection(); ?>
