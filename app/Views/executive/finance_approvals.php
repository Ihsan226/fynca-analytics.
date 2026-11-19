<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Finance Approvals</h1>
        <p>Tinjau dan setujui pencairan dana payroll yang diajukan oleh Bendahara.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Pengajuan Payroll</div>
            <div class="card-sub">Menunggu otorisasi pencairan dari Executive/Owner.</div>
        </div>
        <span class="badge badge-amber"><?= count($pendingBatches) ?> Menunggu</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:120px;">Periode</th>
                    <th style="text-align:center;">Total Karyawan</th>
                    <th style="text-align:right;">Total Beban Gaji (THP)</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pendingBatches)): ?>
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="fa-solid fa-file-circle-check"></i>
                            <p>Tidak ada pengajuan payroll yang menunggu persetujuan.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($pendingBatches as $b): ?>
                <tr>
                    <td style="font-weight:600; color:#334155;">
                        <?= date('F Y', mktime(0, 0, 0, $b['periode_bulan'], 1, $b['periode_tahun'])) ?>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge badge-gray"><?= $b['total_karyawan'] ?> Orang</span>
                    </td>
                    <td style="text-align:right; font-weight:700; color:#4f46e5; font-size:16px;">
                        Rp <?= number_format($b['sum_thp'], 0, ',', '.') ?>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex; gap:10px; justify-content:center;">
                            <form action="/executive/finance/approve-payroll/<?= $b['periode_bulan'] ?>/<?= $b['periode_tahun'] ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-primary btn-sm" style="background:#10b981;border:none;" onclick="return confirm('Peringatan: Menyetujui payroll ini akan otomatis merilis dana dan mencatatnya sebagai PENGELUARAN di Jurnal Kas. Lanjutkan?')">
                                    <i class="fa-solid fa-check"></i> ACC & Cairkan
                                </button>
                            </form>
                            <form action="/executive/finance/reject-payroll/<?= $b['periode_bulan'] ?>/<?= $b['periode_tahun'] ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak dan kembalikan draf payroll ini ke Bendahara?')">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>
