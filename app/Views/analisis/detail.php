<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php
$indicatorLabels = [
    'etr'    => 'ETR — Tax Aggressiveness Indicator',
    'rpt'    => 'RPT Ratio — Related Party Receivable',
    'der'    => 'DER — Leverage Indicator',
    'growth' => 'Sales Growth',
];
$indicatorIcons = [
    'etr'    => 'fa-percent',
    'rpt'    => 'fa-money-bill-transfer',
    'der'    => 'fa-building-columns',
    'growth' => 'fa-chart-line',
];
?>

<!-- Header -->
<div class="ph">
    <div class="ph-left">
        <h1>Tax Analytics — <?= htmlspecialchars($row['nama_perusahaan']) ?></h1>
        <p><?= htmlspecialchars($row['kode_saham']) ?> · Tahun <?= $row['tahun'] ?> · <?= htmlspecialchars($row['sektor'] ?? '') ?></p>
    </div>
    <a href="/analisis" class="btn btn-ghost">
        <i class="fa-solid fa-arrow-left" style="font-size:11px;"></i> Kembali
    </a>
</div>

<!-- ══ TAX ANALYTICS SCORE ══ -->
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    <div>
        <p style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.1em;margin:0 0 6px;">TAX ANALYTICS SCORE</p>
        <div style="display:flex;align-items:baseline;gap:8px;">
            <h2 style="font-size:42px;font-weight:800;color:#0f172a;margin:0;line-height:1;"><?= $riskScore ?? 0 ?></h2>
            <span style="font-size:16px;font-weight:600;color:#94a3b8;">/ 100</span>
        </div>
    </div>
    <div style="text-align:right;">
        <span style="display:inline-block;padding:6px 16px;border-radius:99px;font-size:14px;font-weight:800;letter-spacing:0.05em;
            <?= ($riskLabel ?? '') === 'EXCELLENT' ? 'background:#dcfce7;color:#166534;' : 
               (($riskLabel ?? '') === 'MODERATE' ? 'background:#fef9c3;color:#854d0e;' : 'background:#fee2e2;color:#991b1b;') ?>">
            <?= $riskLabel ?? 'UNKNOWN' ?>
        </span>
    </div>
</div>

<!-- ══ OVERALL OBSERVATION ══ -->
<div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:16px;padding:24px 28px;margin-bottom:24px;color:#fff;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
        <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;">
            <i class="fa-solid fa-lightbulb" style="font-size:18px;"></i>
        </div>
        <div>
            <h3 style="font-size:16px;font-weight:700;margin:0;">Tax Analytics Summary</h3>
            <p style="font-size:11px;opacity:0.8;margin:2px 0 0;">Overall Observation</p>
        </div>
    </div>
    <p style="font-size:14px;line-height:1.7;opacity:0.95;margin:0;"><?= htmlspecialchars($summary) ?></p>
</div>

<!-- ══ ATTENTION & POSITIVE ITEMS ══ -->
<?php if (!empty($attentions) || !empty($positives)): ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
    
    <?php if (!empty($attentions)): ?>
    <div style="background:#fff;border-radius:14px;border:1px solid #fecdd3;overflow:hidden;">
        <div style="background:#fff1f2;padding:14px 20px;border-bottom:1px solid #fecdd3;">
            <h4 style="font-size:13px;font-weight:700;color:#be123c;margin:0;">
                <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> Attention Items
            </h4>
        </div>
        <div style="padding:16px 20px;">
            <?php foreach ($attentions as $key => $att): ?>
            <div style="display:flex;align-items:flex-start;gap:10px;<?= $key !== array_key_last($attentions) ? 'margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #f1f5f9;' : '' ?>">
                <span style="font-size:18px;"><?= $att['icon'] ?></span>
                <div>
                    <p style="font-size:12px;font-weight:700;color:#0f172a;margin:0 0 2px;"><?= $indicatorLabels[$key] ?? $key ?></p>
                    <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;"><?= htmlspecialchars($att['explanation']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($positives)): ?>
    <div style="background:#fff;border-radius:14px;border:1px solid #bbf7d0;overflow:hidden;">
        <div style="background:#f0fdf4;padding:14px 20px;border-bottom:1px solid #bbf7d0;">
            <h4 style="font-size:13px;font-weight:700;color:#15803d;margin:0;">
                <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i> Positive Items
            </h4>
        </div>
        <div style="padding:16px 20px;">
            <?php foreach ($positives as $key => $pos): ?>
            <div style="display:flex;align-items:flex-start;gap:10px;<?= $key !== array_key_last($positives) ? 'margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #f1f5f9;' : '' ?>">
                <span style="font-size:18px;"><?= $pos['icon'] ?></span>
                <div>
                    <p style="font-size:12px;font-weight:700;color:#0f172a;margin:0 0 2px;"><?= $indicatorLabels[$key] ?? $key ?></p>
                    <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;"><?= htmlspecialchars($pos['explanation']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php endif; ?>

<!-- ══ INDICATOR DETAIL CARDS ══ -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
    <?php foreach ($explanations as $key => $exp): ?>
    <div style="background:#fff;border-radius:14px;border:1px solid #e2e8f0;overflow:hidden;">
        <div style="padding:18px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:38px;height:38px;border-radius:10px;background:<?= $exp['bg'] ?>;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid <?= $indicatorIcons[$key] ?? 'fa-chart-bar' ?>" style="color:<?= $exp['color'] ?>;font-size:16px;"></i>
                </div>
                <div>
                    <p style="font-size:13px;font-weight:700;color:#0f172a;margin:0;"><?= $indicatorLabels[$key] ?? $key ?></p>
                    <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Indicator</p>
                </div>
            </div>
            <span style="background:<?= $exp['bg'] ?>;color:<?= $exp['color'] ?>;font-size:10px;font-weight:700;padding:4px 12px;border-radius:20px;">
                <?= $exp['icon'] ?> <?= $exp['label'] ?>
            </span>
        </div>
        <div style="padding:20px;">
            <!-- Value -->
            <div style="text-align:center;margin-bottom:16px;">
                <p style="font-size:32px;font-weight:800;color:<?= $exp['color'] ?>;margin:0;"><?= $exp['formatted'] ?></p>
                <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">Analytical Risk Level: <?= $exp['label'] ?></p>
            </div>
            <!-- Explanation -->
            <div style="background:#f8fafc;border-radius:10px;padding:14px 16px;">
                <p style="font-size:12px;color:#475569;line-height:1.6;margin:0;">
                    <?= htmlspecialchars($exp['explanation']) ?>
                </p>
            </div>
            <?php if (isset($exp['tax_ref'])): ?>
            <!-- Tax Regulatory Reference -->
            <div style="margin-top:12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 16px;">
                <p style="font-size:10px;font-weight:700;color:#1e40af;text-transform:uppercase;letter-spacing:0.5px;margin:0 0 6px;">
                    <i class="fa-solid fa-scale-balanced" style="margin-right:4px;"></i> Tax Regulatory Reference
                </p>
                <p style="font-size:13px;font-weight:700;color:#1e3a8a;margin:0 0 4px;">Maximum: <?= $exp['tax_ref']['value'] ?></p>
                <p style="font-size:11px;color:#3b82f6;margin:0;"><?= htmlspecialchars($exp['tax_ref']['source']) ?></p>
                <p style="font-size:11px;color:#64748b;margin:4px 0 0;"><?= htmlspecialchars($exp['tax_ref']['note']) ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ══ RAW DATA ══ -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <div>
            <div class="card-title">Data Keuangan</div>
            <div class="card-sub">Data mentah yang digunakan untuk kalkulasi indikator.</div>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
            <div style="background:#f8fafc;border-radius:10px;padding:14px;">
                <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin:0 0 8px;">ETR Data</p>
                <p style="font-size:12px;color:#475569;margin:0 0 4px;">Laba Sebelum Pajak: <strong>Rp <?= number_format($row['laba_sebelum_pajak'] ?? 0, 0, ',', '.') ?></strong></p>
                <p style="font-size:12px;color:#475569;margin:0;">Beban Pajak: <strong>Rp <?= number_format($row['beban_pajak'] ?? 0, 0, ',', '.') ?></strong></p>
            </div>
            <div style="background:#f8fafc;border-radius:10px;padding:14px;">
                <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin:0 0 8px;">RPT Data</p>
                <p style="font-size:12px;color:#475569;margin:0 0 4px;">Piutang Berelasi: <strong>Rp <?= number_format($row['piutang_berelasi'] ?? 0, 0, ',', '.') ?></strong></p>
                <p style="font-size:12px;color:#475569;margin:0;">Total Piutang: <strong>Rp <?= number_format($row['total_piutang'] ?? 0, 0, ',', '.') ?></strong></p>
            </div>
            <div style="background:#f8fafc;border-radius:10px;padding:14px;">
                <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin:0 0 8px;">DER Data</p>
                <p style="font-size:12px;color:#475569;margin:0 0 4px;">Total Hutang: <strong>Rp <?= number_format($row['total_hutang'] ?? 0, 0, ',', '.') ?></strong></p>
                <p style="font-size:12px;color:#475569;margin:0;">Total Ekuitas: <strong>Rp <?= number_format($row['total_ekuitas'] ?? 0, 0, ',', '.') ?></strong></p>
            </div>
            <div style="background:#f8fafc;border-radius:10px;padding:14px;">
                <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin:0 0 8px;">Sales Data</p>
                <p style="font-size:12px;color:#475569;margin:0 0 4px;">Penjualan Tahun Ini: <strong>Rp <?= number_format($row['penjualan_tahun_ini'] ?? 0, 0, ',', '.') ?></strong></p>
                <p style="font-size:12px;color:#475569;margin:0;">Penjualan Tahun Lalu: <strong>Rp <?= number_format($row['penjualan_tahun_lalu'] ?? 0, 0, ',', '.') ?></strong></p>
            </div>
        </div>
    </div>
</div>

<!-- ══ REGULATORY REFERENCES ══ -->
<?php if (!empty($regulasi)): ?>
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <div>
            <div class="card-title">Referensi Regulasi Terkait</div>
            <div class="card-sub">Regulasi pajak yang relevan dengan indikator yang dianalisis.</div>
        </div>
    </div>
    <div style="padding:20px;">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
            <?php foreach ($regulasi as $reg): ?>
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-size:12px;font-weight:700;color:#0f172a;"><?= esc($reg['kode_regulasi']) ?></span>
                    <span style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:12px;
                        <?= $reg['status'] === 'active' ? 'background:#f0fdf4;color:#16a34a;' : ($reg['status'] === 'amended' ? 'background:#fffbeb;color:#d97706;' : 'background:#fff1f2;color:#e11d48;') ?>">
                        <?= ucfirst($reg['status']) ?>
                    </span>
                </div>
                <p style="font-size:12px;color:#475569;margin:0 0 6px;line-height:1.5;"><?= esc($reg['judul']) ?></p>
                <?php if (!empty($reg['url'])): ?>
                <a href="<?= esc($reg['url']) ?>" target="_blank" style="font-size:11px;color:#3b82f6;text-decoration:none;font-weight:600;">
                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;margin-right:3px;"></i> View Regulation
                </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Disclaimer -->
<div class="info-box" style="background:#f0f9ff;border-color:#bae6fd;">
    <i class="fa-solid fa-circle-info" style="color:#0284c7;"></i>
    <p style="color:#0c4a6e;font-size:12px;"><strong>Disclaimer:</strong> Hasil yang ditampilkan merupakan analisis/indikasi dan alat bantu, bukan keputusan hukum atau pajak final. Untuk keperluan pelaporan pajak, silakan merujuk pada regulasi yang berlaku dan berkonsultasi dengan konsultan pajak profesional.</p>
</div>

<?= $this->endSection(); ?>
