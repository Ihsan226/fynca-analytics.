<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Kategori Sektor</h1>
        <p>Kelola sektor industri untuk klasifikasi klien yang dianalisis.</p>
    </div>
    <?php if (session('role') !== 'analyst'): ?>
    <button class="btn btn-primary" onclick="openModal('modalTambah')">
        <i class="fa-solid fa-plus"></i> Tambah Sektor
    </button>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Sektor Industri</div>
            <div class="card-sub">Sektor yang tersedia untuk klasifikasi klien dalam sistem.</div>
        </div>
        <span class="badge badge-gray"><?= count($sektors) ?> sektor</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:40px;">No</th>
                    <th style="text-align:left;">Nama Sektor</th>
                    <th style="text-align:center;">Kode</th>
                    <th style="text-align:center;">Indikator Aktif</th>
                    <th style="text-align:center;">Jumlah Klien</th>
                    <th style="text-align:center;">Status</th>
                    <?php if (session('role') !== 'analyst'): ?>
                    <th style="text-align:center;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sektors)): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fa-solid fa-industry"></i>
                            <p>Belum ada sektor yang ditambahkan.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($sektors as $i => $s): ?>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;"><?= $i + 1 ?></td>
                    <td style="font-weight:600;">
                        <?= esc($s['nama']) ?>
                        <?php if (!empty($s['deskripsi'])): ?>
                            <div style="font-size:11px;color:#94a3b8;font-weight:400;margin-top:2px;"><?= esc($s['deskripsi']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center;"><span class="badge badge-solid"><?= esc($s['kode']) ?></span></td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:4px;justify-content:center;">
                            <?php if ($s['enable_etr']): ?><span class="badge badge-gray" style="font-size:9px;" title="ETR">ETR</span><?php endif; ?>
                            <?php if ($s['enable_rpt']): ?><span class="badge badge-gray" style="font-size:9px;" title="RPT">RPT</span><?php endif; ?>
                            <?php if ($s['enable_der']): ?><span class="badge badge-gray" style="font-size:9px;" title="DER">DER</span><?php endif; ?>
                            <?php if ($s['enable_sales_growth']): ?><span class="badge badge-gray" style="font-size:9px;" title="Growth">GRO</span><?php endif; ?>
                        </div>
                    </td>
                    <td style="text-align:center;font-weight:700;color:#3b5de7;"><?= $s['jumlah_klien'] ?></td>
                    <td style="text-align:center;">
                        <span class="badge badge-<?= $s['status'] === 'Aktif' ? 'green' : 'gray' ?>"><?= esc($s['status']) ?></span>
                    </td>
                    <?php if (session('role') !== 'analyst'): ?>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button class="btn btn-primary btn-sm" onclick="openEditModal(<?= htmlspecialchars(json_encode($s), ENT_QUOTES, 'UTF-8') ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="openDeleteModal(<?= $s['id'] ?>, '<?= esc($s['nama']) ?>', <?= $s['jumlah_klien'] ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="insight-box" style="margin-top:20px;">
    <div class="insight-icon blue"><i class="fa-solid fa-circle-info"></i></div>
    <div class="insight-content">
        <h4>Tentang Kategori Sektor</h4>
        <p>Saat ini sistem berfokus pada sektor <b>Properti & Real Estate</b>. Ke depannya, Anda dapat menambahkan sektor lain seperti Perbankan, Manufaktur, atau Pertambangan untuk memperluas cakupan analisis pajak.</p>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: TAMBAH SEKTOR -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalTambah">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon blue">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <h3 class="modal-title">Tambah Sektor Baru</h3>
                <p class="modal-sub">Tambahkan sektor industri untuk klasifikasi klien.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalTambah')">&times;</button>
        </div>
        <form action="/sektor/store" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Sektor</label>
                        <input type="text" name="nama" class="form-input" placeholder="Contoh: Perbankan" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Singkat</label>
                        <input type="text" name="deskripsi" class="form-input" placeholder="Keterangan sektor">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Kode Sektor</label>
                            <input type="text" name="kode" class="form-input" placeholder="Contoh: BANK" maxlength="10" style="text-transform:uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-input" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:16px;">
                        <p style="font-size:12px;font-weight:700;color:#0f172a;margin:0 0 12px;">Indikator yang Berlaku (Applicable)</p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                                <input type="checkbox" name="enable_etr" value="1" checked> ETR (Tax Aggressiveness)
                            </label>
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                                <input type="checkbox" name="enable_rpt" value="1" checked> RPT Ratio
                            </label>
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                                <input type="checkbox" name="enable_der" value="1" checked> DER (Leverage)
                            </label>
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                                <input type="checkbox" name="enable_sales_growth" value="1" checked> Sales Growth
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Aturan Khusus (Opsional)</label>
                        <textarea name="special_rules" class="form-input" rows="2" placeholder="Catatan aturan pajak khusus untuk sektor ini..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Simpan Sektor
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: EDIT SEKTOR -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon amber">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h3 class="modal-title">Edit Sektor</h3>
                <p class="modal-sub">Ubah informasi sektor industri.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalEdit')">&times;</button>
        </div>
        <form id="formEdit" action="" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Sektor</label>
                        <input type="text" name="nama" id="editNama" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Singkat</label>
                        <input type="text" name="deskripsi" id="editDeskripsi" class="form-input">
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group">
                            <label class="form-label">Kode Sektor</label>
                            <input type="text" name="kode" id="editKode" class="form-input" maxlength="10" style="text-transform:uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" id="editStatus" class="form-input" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:16px;">
                        <p style="font-size:12px;font-weight:700;color:#0f172a;margin:0 0 12px;">Indikator yang Berlaku (Applicable)</p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                                <input type="checkbox" name="enable_etr" id="editEnableEtr" value="1"> ETR (Tax Aggressiveness)
                            </label>
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                                <input type="checkbox" name="enable_rpt" id="editEnableRpt" value="1"> RPT Ratio
                            </label>
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                                <input type="checkbox" name="enable_der" id="editEnableDer" value="1"> DER (Leverage)
                            </label>
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
                                <input type="checkbox" name="enable_sales_growth" id="editEnableGrowth" value="1"> Sales Growth
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Aturan Khusus (Opsional)</label>
                        <textarea name="special_rules" id="editSpecialRules" class="form-input" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: KONFIRMASI HAPUS -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalHapus">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon rose">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="modal-title">Konfirmasi Hapus</h3>
                <p class="modal-sub">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalHapus')">&times;</button>
        </div>
        <div class="modal-body">
            <p id="deleteMessage" style="color:#64748b;font-size:0.875rem;line-height:1.6;"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost" onclick="closeModal('modalHapus')">Batal</button>
            <a id="deleteLink" href="#" class="btn btn-danger">
                <i class="fa-solid fa-trash-can"></i> Ya, Hapus
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

function openEditModal(sektor) {
    document.getElementById('formEdit').action = '/sektor/update/' + sektor.id;
    document.getElementById('editNama').value = sektor.nama;
    document.getElementById('editDeskripsi').value = sektor.deskripsi || '';
    document.getElementById('editKode').value = sektor.kode;
    document.getElementById('editStatus').value = sektor.status;
    document.getElementById('editEnableEtr').checked = sektor.enable_etr == 1;
    document.getElementById('editEnableRpt').checked = sektor.enable_rpt == 1;
    document.getElementById('editEnableDer').checked = sektor.enable_der == 1;
    document.getElementById('editEnableGrowth').checked = sektor.enable_sales_growth == 1;
    document.getElementById('editSpecialRules').value = sektor.special_rules || '';
    openModal('modalEdit');
}

function openDeleteModal(id, nama, klienCount) {
    var msg;
    if (klienCount > 0) {
        msg = 'Sektor <strong style="color:#e11d48;">' + nama + '</strong> masih memiliki <strong>' + klienCount + ' klien</strong> yang terkait. Anda harus memindahkan atau menghapus klien terlebih dahulu sebelum menghapus sektor ini.';
    } else {
        msg = 'Anda yakin ingin menghapus sektor <strong style="color:#e11d48;">' + nama + '</strong>? Tindakan ini tidak dapat dibatalkan.';
    }
    document.getElementById('deleteMessage').innerHTML = msg;
    document.getElementById('deleteLink').href = '/sektor/delete/' + id;
    openModal('modalHapus');
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
