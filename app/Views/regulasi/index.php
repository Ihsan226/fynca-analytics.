<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Referensi Regulasi Pajak</h1>
        <p>Kelola daftar peraturan perpajakan yang digunakan sebagai dasar referensi analisis indikator klien.</p>
    </div>
    <?php if (session('role') !== 'analyst'): ?>
    <button class="btn btn-primary" onclick="openModal('modalTambah')">
        <i class="fa-solid fa-plus"></i> Tambah Regulasi
    </button>
    <?php endif; ?>
</div>

<?php if (empty($regulasi)): ?>
    <div class="empty-state card" style="padding:60px 20px;">
        <div class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
            <i class="fa-solid fa-scale-balanced text-3xl text-slate-300"></i>
        </div>
        <p class="text-lg font-bold text-slate-700 mb-1">Belum ada referensi regulasi</p>
        <p class="text-sm text-slate-500 max-w-md mx-auto">Tambahkan undang-undang, PMK, atau aturan lainnya untuk memperkuat landasan analisis sistem dan memberikan referensi akurat pada klien.</p>
        <?php if (session('role') !== 'analyst'): ?>
        <button class="btn btn-primary mt-6" onclick="openModal('modalTambah')">
            <i class="fa-solid fa-plus"></i> Tambah Regulasi Pertama
        </button>
        <?php endif; ?>
    </div>
<?php else: ?>

    <div class="space-y-10">
        <?php foreach ($grouped as $indikator => $items): ?>
        <div>
            <!-- Group Header -->
            <div class="flex items-center gap-3 mb-5 border-b border-slate-200/60 pb-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100/50">
                    <i class="fa-solid fa-bookmark text-sm"></i>
                </div>
                <h2 class="text-[1.05rem] font-bold text-slate-800 tracking-tight">
                    <?= $indikator === 'general' ? 'Regulasi Umum / Semua Indikator' : 'Indikator: ' . esc($indikator) ?>
                </h2>
                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-md text-[10px] font-bold tracking-wider">
                    <?= count($items) ?> DATA
                </span>
            </div>

            <!-- Group List (Horizontal List View) -->
            <div class="flex flex-col gap-3">
                <?php foreach ($items as $r): ?>
                <div class="group relative bg-white border border-slate-200/70 rounded-xl p-4 shadow-sm hover:shadow-md hover:border-indigo-300/50 transition-all duration-300 flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-6 overflow-hidden">
                    
                    <!-- Left: Status & Tags (Fixed Width) -->
                    <div class="w-full lg:w-48 shrink-0 flex flex-row lg:flex-col items-center lg:items-start justify-between lg:justify-center gap-2">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100/80 text-slate-700 text-[11px] font-bold tracking-wide border border-slate-200/50">
                            <i class="fa-solid fa-hashtag text-slate-400"></i>
                            <?= esc($r['kode_regulasi']) ?>
                        </div>
                        
                        <div>
                            <?php if ($r['status'] === 'active'): ?>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-widest border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Berlaku
                                </span>
                            <?php elseif ($r['status'] === 'amended'): ?>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold uppercase tracking-widest border border-amber-200/60">
                                    <i class="fa-solid fa-pen-nib"></i> Diubah
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-bold uppercase tracking-widest border border-rose-200/60">
                                    <i class="fa-solid fa-xmark"></i> Dicabut
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Middle: Content (Flexible) -->
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <h3 class="text-base font-bold text-slate-800 leading-snug mb-1 group-hover:text-indigo-600 transition-colors truncate">
                            <?= esc($r['judul']) ?>
                        </h3>
                        <p class="text-sm text-slate-500 truncate" title="<?= esc($r['ringkasan']) ?>">
                            <?= esc($r['ringkasan']) ?>
                        </p>
                    </div>

                    <!-- Right: Actions & Meta (Fixed Width) -->
                    <div class="w-full lg:w-auto shrink-0 flex items-center justify-between lg:justify-end gap-6 border-t lg:border-t-0 pt-3 lg:pt-0 mt-2 lg:mt-0 border-slate-100">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500 whitespace-nowrap" title="Berlaku Sejak">
                                <i class="fa-regular fa-calendar text-slate-400"></i> 
                                <?= $r['berlaku_sejak'] ? date('M Y', strtotime($r['berlaku_sejak'])) : '—' ?>
                            </span>
                            <?php if (!empty($r['url'])): ?>
                            <a href="<?= esc($r['url']) ?>" target="_blank" class="flex items-center gap-1 text-[12px] font-medium text-indigo-500 hover:text-indigo-700 hover:underline transition-all">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Dokumen
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Actions -->
                        <?php if (session('role') !== 'analyst'): ?>
                        <div class="flex items-center gap-1 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity duration-300">
                            <button class="w-8 h-8 rounded-md bg-slate-50 hover:bg-amber-50 text-slate-400 hover:text-amber-600 border border-slate-200 flex items-center justify-center transition-colors" onclick='openEditModal(<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>)' title="Edit">
                                <i class="fa-solid fa-pen text-[11px]"></i>
                            </button>
                            <button class="w-8 h-8 rounded-md bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 flex items-center justify-center transition-colors" onclick="openDeleteModal(<?= $r['id'] ?>, '<?= esc($r['kode_regulasi']) ?>')" title="Hapus">
                                <i class="fa-solid fa-trash text-[11px]"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<!-- ============================================= -->
<!-- MODAL: TAMBAH REGULASI -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalTambah">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-header-icon blue">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <h3 class="modal-title">Tambah Regulasi Baru</h3>
            </div>
            <button class="modal-close" onclick="closeModal('modalTambah')">&times;</button>
        </div>
        <form action="/regulasi/store" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Kode Regulasi</label>
                        <input type="text" name="kode_regulasi" class="form-input" placeholder="Contoh: PMK-169/PMK.010/2015" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Indikator Terkait</label>
                        <select name="indikator_terkait" class="form-input" required>
                            <option value="general">Umum / Semua Indikator</option>
                            <option value="ETR">ETR (Tax Aggressiveness)</option>
                            <option value="RPT Ratio">RPT Ratio</option>
                            <option value="DER">DER (Leverage)</option>
                            <option value="Sales Growth">Sales Growth</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Judul Regulasi</label>
                        <input type="text" name="judul" class="form-input" placeholder="Penentuan Besarnya Perbandingan Antara Utang Dan Modal..." required>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Ringkasan Inti</label>
                        <textarea name="ringkasan" class="form-input" rows="3" placeholder="Poin penting regulasi ini terkait indikator analitik..."></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">URL / Link Dokumen Resmi (Opsional)</label>
                        <input type="url" name="url" class="form-input" placeholder="https://jdih.kemenkeu.go.id/...">
                    </div>
                    <div class="full-width form-row-3">
                        <div class="form-group">
                            <label class="form-label">Status Regulasi</label>
                            <select name="status" class="form-input" required>
                                <option value="active">Berlaku (Active)</option>
                                <option value="amended">Diubah (Amended)</option>
                                <option value="revoked">Dicabut (Revoked)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Berlaku Sejak (Opsional)</label>
                            <input type="date" name="berlaku_sejak" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Berlaku Hingga (Opsional)</label>
                            <input type="date" name="berlaku_hingga" class="form-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Regulasi</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: EDIT REGULASI -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-header-icon amber">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h3 class="modal-title">Edit Regulasi</h3>
            </div>
            <button class="modal-close" onclick="closeModal('modalEdit')">&times;</button>
        </div>
        <form id="formEdit" action="" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Kode Regulasi</label>
                        <input type="text" name="kode_regulasi" id="editKode" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Indikator Terkait</label>
                        <select name="indikator_terkait" id="editIndikator" class="form-input" required>
                            <option value="general">Umum / Semua Indikator</option>
                            <option value="ETR">ETR (Tax Aggressiveness)</option>
                            <option value="RPT Ratio">RPT Ratio</option>
                            <option value="DER">DER (Leverage)</option>
                            <option value="Sales Growth">Sales Growth</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Judul Regulasi</label>
                        <input type="text" name="judul" id="editJudul" class="form-input" required>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Ringkasan Inti</label>
                        <textarea name="ringkasan" id="editRingkasan" class="form-input" rows="3"></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">URL / Link Dokumen Resmi (Opsional)</label>
                        <input type="url" name="url" id="editUrl" class="form-input">
                    </div>
                    <div class="full-width form-row-3">
                        <div class="form-group">
                            <label class="form-label">Status Regulasi</label>
                            <select name="status" id="editStatus" class="form-input" required>
                                <option value="active">Berlaku (Active)</option>
                                <option value="amended">Diubah (Amended)</option>
                                <option value="revoked">Dicabut (Revoked)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Berlaku Sejak (Opsional)</label>
                            <input type="date" name="berlaku_sejak" id="editBerlakuSejak" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Berlaku Hingga (Opsional)</label>
                            <input type="date" name="berlaku_hingga" id="editBerlakuHingga" class="form-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
            </div>
            <button class="modal-close" onclick="closeModal('modalHapus')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="color:#64748b;font-size:0.875rem;line-height:1.6;">
                Anda yakin ingin menghapus regulasi <strong id="deleteRegKode" style="color:#e11d48;"></strong>?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost" onclick="closeModal('modalHapus')">Batal</button>
            <a id="deleteLink" href="#" class="btn btn-danger">Hapus</a>
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

function openEditModal(reg) {
    document.getElementById('formEdit').action = '/regulasi/update/' + reg.id;
    document.getElementById('editKode').value = reg.kode_regulasi;
    document.getElementById('editIndikator').value = reg.indikator_terkait || 'general';
    document.getElementById('editJudul').value = reg.judul;
    document.getElementById('editRingkasan').value = reg.ringkasan || '';
    document.getElementById('editUrl').value = reg.url || '';
    document.getElementById('editStatus').value = reg.status || 'active';
    document.getElementById('editBerlakuSejak').value = reg.berlaku_sejak ? reg.berlaku_sejak.split(' ')[0] : '';
    document.getElementById('editBerlakuHingga').value = reg.berlaku_hingga ? reg.berlaku_hingga.split(' ')[0] : '';
    
    openModal('modalEdit');
}

function openDeleteModal(id, kode) {
    document.getElementById('deleteRegKode').textContent = kode;
    document.getElementById('deleteLink').href = '/regulasi/delete/' + id;
    openModal('modalHapus');
}

// Close modals on overlay click or Escape key
document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});
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
