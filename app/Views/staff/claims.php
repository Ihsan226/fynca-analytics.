<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph" style="display:flex; justify-content:space-between; align-items:flex-end;">
    <div class="ph-left">
        <h1>Klaim & Reimbursement</h1>
        <p>Ajukan klaim pengeluaran operasional kepada Bendahara.</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="openModal('modalAjukan')">
            <i class="fa-solid fa-plus"></i> Ajukan Reimbursement
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Riwayat Pengajuan</div>
            <div class="card-sub">Daftar klaim yang pernah Anda ajukan.</div>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:120px;">Tanggal</th>
                    <th style="text-align:left;">Keterangan</th>
                    <th style="text-align:right;">Nominal</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($claims)): ?>
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <i class="fa-solid fa-file-invoice"></i>
                            <p>Anda belum pernah mengajukan klaim reimbursement.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($claims as $c): ?>
                <tr>
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
                            } else {
                                echo '<span class="badge badge-rose">Ditolak</span>';
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: AJUKAN KLAIM -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalAjukan">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon blue">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
            <div>
                <h3 class="modal-title">Form Reimbursement</h3>
                <p class="modal-sub">Ajukan penggantian dana operasional.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalAjukan')">&times;</button>
        </div>
        <form action="/staff/claims/store" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-input" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan / Deskripsi</label>
                        <textarea name="deskripsi" class="form-input" rows="3" required placeholder="Contoh: Tiket KAI bertemu klien PT ABC"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-input" required min="1">
                    </div>
                    <p style="font-size:12px; color:#94a3b8; margin:0;">*Fitur upload bukti belum diaktifkan, pastikan Anda menyerahkan struk fisik ke Bendahara jika diminta.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalAjukan')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Ajukan Klaim
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});
</script>

<?= $this->endSection(); ?>
