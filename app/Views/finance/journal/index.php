<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph" style="display:flex; justify-content:space-between; align-items:flex-end;">
    <div class="ph-left">
        <h1>Jurnal Kas Internal</h1>
        <p>Buku besar pencatatan arus kas (pemasukan dan pengeluaran) perusahaan.</p>
    </div>
    <div>
        <button class="btn btn-primary" onclick="openModal('modalTambahKas')">
            <i class="fa-solid fa-plus"></i> Tambah Transaksi
        </button>
    </div>
</div>

<!-- SALDO SUMMARY -->
<div class="card" style="margin-bottom:20px; background:linear-gradient(135deg, #1e293b, #0f172a); color:white;">
    <div class="card-body" style="padding:30px;">
        <p style="margin:0 0 10px 0; color:#94a3b8; font-size:14px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">Total Saldo Kas</p>
        <h2 style="margin:0; font-size:36px; font-weight:700; color:#10b981;">
            Rp <?= number_format($saldo_akhir, 0, ',', '.') ?>
        </h2>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Riwayat Transaksi</div>
            <div class="card-sub">Double-entry ledger harian.</div>
        </div>
        <span class="badge badge-gray"><?= count($jurnals) ?> Transaksi</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:120px;">Tanggal</th>
                    <th style="text-align:left;">Keterangan</th>
                    <th style="text-align:center;">Jenis</th>
                    <th style="text-align:right;">Nominal (Rp)</th>
                    <th style="text-align:center;">Ref</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($jurnals)): ?>
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fa-solid fa-book"></i>
                            <p>Belum ada catatan transaksi di jurnal kas.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($jurnals as $j): ?>
                <tr>
                    <td style="font-size:13px;color:#64748b;font-weight:500;">
                        <?= date('d M Y', strtotime($j['tanggal'])) ?>
                    </td>
                    <td style="font-weight:600; color:#334155;">
                        <?= esc($j['deskripsi']) ?>
                    </td>
                    <td style="text-align:center;">
                        <?php if ($j['tipe'] == 'debit'): ?>
                            <span class="badge badge-emerald">Debit (Masuk)</span>
                        <?php else: ?>
                            <span class="badge badge-rose">Kredit (Keluar)</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right; font-weight:700; color:<?= $j['tipe'] == 'debit' ? '#10b981' : '#ef4444' ?>;">
                        <?= $j['tipe'] == 'debit' ? '+' : '-' ?> Rp <?= number_format($j['nominal'], 0, ',', '.') ?>
                    </td>
                    <td style="text-align:center;">
                        <span style="font-size:12px; color:#94a3b8; text-transform:uppercase;">
                            <?= esc($j['referensi_tipe']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: TAMBAH TRANSAKSI MANUAL -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalTambahKas">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon amber">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
            <div>
                <h3 class="modal-title">Catat Transaksi Manual</h3>
                <p class="modal-sub">Input ke Jurnal Kas Umum.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalTambahKas')">&times;</button>
        </div>
        <form action="/finance/journal/store" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-input" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Transaksi</label>
                        <select name="tipe" class="form-input" required>
                            <option value="debit">Debit (Uang Masuk / Penambahan Saldo)</option>
                            <option value="kredit">Kredit (Uang Keluar / Pengeluaran Beban)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan / Deskripsi</label>
                        <textarea name="deskripsi" class="form-input" rows="2" required placeholder="Contoh: Pembayaran tagihan server AWS"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-input" required min="1">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalTambahKas')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Transaksi
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
