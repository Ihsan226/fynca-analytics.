<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Riwayat Slip Gaji</h1>
        <p>Arsip seluruh slip gaji yang sudah diotorisasi oleh Executive.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Arsip Slip Gaji</div>
            <div class="card-sub">Gaji bersih yang telah didistribusikan.</div>
        </div>
        <span class="badge badge-gray"><?= count($payrolls) ?> Slip</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:120px;">No Slip</th>
                    <th style="text-align:left;">Nama Karyawan</th>
                    <th style="text-align:center;">Periode</th>
                    <th style="text-align:right;">Gaji Bersih (THP)</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($payrolls)): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fa-solid fa-box-archive"></i>
                            <p>Belum ada riwayat slip gaji yang disetujui.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($payrolls as $p): ?>
                <tr>
                    <td style="font-size:13px;font-weight:600;color:#64748b;">
                        SL-<?= $p['periode_tahun'] ?><?= str_pad($p['periode_bulan'], 2, '0', STR_PAD_LEFT) ?>-<?= str_pad($p['id_user'], 3, '0', STR_PAD_LEFT) ?>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">
                                <?= strtoupper(substr($p['nama_lengkap'], 0, 1)) ?>
                            </div>
                            <span style="font-weight:600;"><?= esc($p['nama_lengkap']) ?></span>
                        </div>
                    </td>
                    <td style="text-align:center; font-size:13px; font-weight:500;">
                        <?= date('M Y', mktime(0, 0, 0, $p['periode_bulan'], 1, $p['periode_tahun'])) ?>
                    </td>
                    <td style="text-align:right; font-weight:700; color:#4f46e5;">
                        Rp <?= number_format($p['total_bersih'], 0, ',', '.') ?>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge badge-emerald">Dibayarkan</span>
                    </td>
                    <td style="text-align:center;">
                        <button class="btn btn-ghost btn-sm" onclick="alert('Fitur preview/download PDF sedang dibangun')">
                            <i class="fa-solid fa-file-pdf text-red-500"></i> Detail
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>
