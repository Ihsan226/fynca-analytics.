<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Klaim & Reimbursement</h1>
        <p>Review pengajuan dana operasional dari staff dan analyst.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Klaim</div>
            <div class="card-sub">Persetujuan klaim dipisahkan dari pembayaran dan pencatatan Jurnal Kas Internal.</div>
        </div>
        <span class="badge badge-gray"><?= count($claims) ?> Pengajuan</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;">Pengaju</th>
                    <th style="text-align:left;width:120px;">Tanggal</th>
                    <th style="text-align:left;">Keterangan</th>
                    <th style="text-align:right;">Nominal</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($claims)): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fa-solid fa-check-double"></i>
                            <p>Tidak ada pengajuan reimbursement saat ini.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($claims as $c): ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">
                                <?= strtoupper(substr($c['nama_lengkap'], 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:600; font-size:13px;"><?= esc($c['nama_lengkap']) ?></div>
                                <div style="font-size:11px; color:#94a3b8;"><?= strtoupper(esc($c['role'])) ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:#64748b;">
                        <?= date('d M Y', strtotime($c['tanggal'])) ?>
                    </td>
                    <td style="font-weight:500;">
                        <?= esc($c['deskripsi']) ?>
                    </td>
                    <td style="text-align:right; font-weight:600; color:#334155;">
                        Rp <?= number_format($c['nominal'], 0, ',', '.') ?>
                    </td>
                    <td style="text-align:center;">
                        <?php 
                            if ($c['status'] == 'pending') {
                                echo '<span class="badge badge-amber">Pending</span>';
                            } elseif ($c['status'] == 'approved') {
                                echo '<span class="badge badge-emerald">Disetujui</span>';
                            } elseif ($c['status'] == 'paid') {
                                echo '<span class="badge badge-emerald">Dibayar</span>';
                            } elseif ($c['status'] == 'processing') {
                                echo '<span class="badge badge-amber">Diproses</span>';
                            } else {
                                echo '<span class="badge badge-rose">Ditolak</span>';
                            }
                        ?>
                    </td>
                    <td style="text-align:center;">
                        <?php if ($c['status'] == 'pending'): ?>
                        <div style="display:flex; gap:5px; justify-content:center;">
                            <form action="/finance/claims/approve/<?= $c['id'] ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-primary btn-sm" style="background:#10b981;border:none;" onclick="return confirm('Setujui klaim ini? Klaim akan masuk antrean pembayaran.')">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            <form action="/finance/claims/reject/<?= $c['id'] ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak klaim ini?')">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                        <?php elseif ($c['status'] == 'approved'): ?>
                        <form action="/finance/claims/pay/<?= $c['id'] ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required style="max-width:130px;font-size:11px;">
                            <button type="submit" class="btn btn-primary btn-sm" style="background:#4f46e5;border:none;margin-top:4px;">
                                <i class="fa-solid fa-money-bill-transfer"></i> Bayar
                            </button>
                        </form>
                        <?php else: ?>
                        <span style="font-size:12px; color:#94a3b8;"><i class="fa-solid fa-lock"></i> Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>
