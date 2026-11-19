<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Log Aktivitas Sistem</h1>
        <p>Audit trail untuk melacak seluruh aktivitas pengguna dan sistem.</p>
    </div>
    <?php if(!empty($logs)): ?>
    <button class="btn btn-danger" onclick="openModal('modalHapusLog')">
        <i class="fa-solid fa-trash-can"></i> Bersihkan Log
    </button>
    <?php else: ?>
    <button class="btn btn-gray" disabled>
        <i class="fa-solid fa-trash-can"></i> Log Bersih
    </button>
    <?php endif; ?>
</div>

<!-- Stat Cards -->
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff;"><i class="fa-solid fa-list-check" style="color:#3b5de7;"></i></div>
        <div>
            <div class="stat-label">Total Aktivitas</div>
            <div class="stat-value"><?= count($logs) ?></div>
            <div class="stat-hint">Tercatat di sistem</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4;"><i class="fa-solid fa-plus-circle" style="color:#16a34a;"></i></div>
        <div>
            <div class="stat-label">Input Data</div>
            <div class="stat-value"><?= count(array_filter($logs, fn($l) => $l['tipe'] === 'create')) ?></div>
            <div class="stat-hint">Entri baru</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fdf4ff;"><i class="fa-solid fa-microchip" style="color:#a855f7;"></i></div>
        <div>
            <div class="stat-label">Sistem</div>
            <div class="stat-value"><?= count(array_filter($logs, fn($l) => $l['tipe'] === 'system')) ?></div>
            <div class="stat-hint">Kalkulasi otomatis</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff1f2;"><i class="fa-solid fa-right-to-bracket" style="color:#e11d48;"></i></div>
        <div>
            <div class="stat-label">Login</div>
            <div class="stat-value"><?= count(array_filter($logs, fn($l) => $l['tipe'] === 'auth')) ?></div>
            <div class="stat-hint">Autentikasi</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Riwayat Aktivitas</div>
            <div class="card-sub">Daftar kronologis seluruh aksi yang terjadi di dalam sistem.</div>
        </div>
    </div>
    <div style="padding:20px 24px;">
        <?php if(empty($logs)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-list-check" style="font-size:36px;color:#cbd5e1;margin-bottom:12px;display:block;"></i>
                <p>Belum ada aktivitas yang tercatat saat ini.</p>
            </div>
        <?php else: ?>
            <?php foreach ($logs as $log): ?>
            <div style="display:flex;align-items:flex-start;gap:14px;padding:14px 0;border-bottom:1px solid #f1f5f9;">
                <?php
                    $iconMap = ['create' => 'fa-circle-plus', 'system' => 'fa-microchip', 'auth' => 'fa-right-to-bracket', 'update' => 'fa-pen-to-square', 'delete' => 'fa-trash-can'];
                    $colorMap = ['create' => '#16a34a', 'system' => '#a855f7', 'auth' => '#3b82f6', 'update' => '#f59e0b', 'delete' => '#e11d48'];
                    $bgMap = ['create' => '#f0fdf4', 'system' => '#fdf4ff', 'auth' => '#eff6ff', 'update' => '#fffbeb', 'delete' => '#fff1f2'];
                ?>
                <div style="width:36px;height:36px;border-radius:10px;background:<?= $bgMap[$log['tipe']] ?? '#f8fafc' ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid <?= $iconMap[$log['tipe']] ?? 'fa-circle' ?>" style="color:<?= $colorMap[$log['tipe']] ?? '#94a3b8' ?>;font-size:14px;"></i>
                </div>
                <div style="flex:1;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <p style="font-size:13px;font-weight:600;color:#0f172a;"><?= esc($log['aksi']) ?></p>
                        <span style="font-size:11px;color:#94a3b8;"><?= date('d M Y, H:i', strtotime($log['created_at'])) ?></span>
                    </div>
                    <p style="font-size:12px;color:#64748b;margin-top:2px;"><?= esc($log['detail']) ?></p>
                    <span style="font-size:10px;color:#94a3b8;margin-top:4px;display:inline-block;">
                        <i class="fa-solid fa-user" style="margin-right:3px;"></i> <?= esc($log['user']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: KONFIRMASI HAPUS LOG -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalHapusLog">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon rose">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="modal-title">Bersihkan Log</h3>
                <p class="modal-sub">Konfirmasi penghapusan data.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalHapusLog')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="color:#64748b;font-size:0.875rem;line-height:1.6;">
                Anda yakin ingin membersihkan <strong>seluruh riwayat aktivitas sistem</strong>? Tindakan ini akan menghapus data audit secara permanen dan tidak dapat dibatalkan.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost" onclick="closeModal('modalHapusLog')">Batal</button>
            <a href="/aktivitas/clear" class="btn btn-danger">
                <i class="fa-solid fa-trash-can"></i> Ya, Bersihkan Log
            </a>
        </div>
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

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(function(modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
});
</script>

<?= $this->endSection(); ?>
