<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Parameter Rasio (Tax Settings)</h1>
        <p>Atur ambang batas indikator analitik dan threshold regulasi pajak secara dinamis.</p>
    </div>
    <?php if (session('role') !== 'analyst'): ?>
    <button class="btn btn-primary" onclick="openModal('modalTambah')">
        <i class="fa-solid fa-plus"></i> Tambah Parameter
    </button>
    <?php endif; ?>
</div>

<div class="info-box" style="background:#fffbeb;border-color:#fde68a;margin-bottom:20px;">
    <i class="fa-solid fa-triangle-exclamation" style="color:#d97706;"></i>
    <p style="color:#92400e;"><strong>Penting:</strong> Mengubah parameter ini akan mempengaruhi klasifikasi warna dan badge pada seluruh halaman analisis dan laporan.</p>
</div>

<!-- ANALYTICAL PARAMETERS -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <div>
            <div class="card-title">Parameter Analitik (Analytical Thresholds)</div>
            <div class="card-sub">Batas nilai untuk klasifikasi risiko analitik internal.</div>
        </div>
        <span class="badge badge-gray"><?= count($analytical) ?> parameter</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:40px;">No</th>
                    <th style="text-align:left;">Nama Parameter</th>
                    <th style="text-align:left;">Indikator</th>
                    <th style="text-align:center;">Batas Bawah</th>
                    <th style="text-align:center;">Batas Atas</th>
                    <th style="text-align:left;">Keterangan</th>
                    <?php if (session('role') !== 'analyst'): ?>
                    <th style="text-align:center;width:90px;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($analytical)): ?>
                <tr><td colspan="7" class="text-center text-gray-500 py-8">Belum ada parameter analitik.</td></tr>
                <?php else: foreach ($analytical as $i => $p): ?>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;"><?= $i + 1 ?></td>
                    <td><span class="badge badge-<?= $p['warna'] ?>"><?= esc($p['nama']) ?></span></td>
                    <td style="font-weight:500;color:#374151;"><?= esc($p['variabel']) ?></td>
                    <td style="text-align:center;font-family:monospace;color:#3b5de7;"><?= number_format($p['batas_bawah'], 2) ?></td>
                    <td style="text-align:center;font-family:monospace;color:#e11d48;"><?= $p['batas_atas'] >= 999 ? '∞' : number_format($p['batas_atas'], 2) ?></td>
                    <td style="font-size:12px;color:#64748b;"><?= esc($p['keterangan']) ?></td>
                    <?php if (session('role') !== 'analyst'): ?>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button class="btn btn-primary btn-sm" onclick="openEditModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="openDeleteModal(<?= $p['id'] ?>, '<?= esc($p['nama']) ?>')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- REGULATORY PARAMETERS -->
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Parameter Regulasi Pajak (Tax Regulatory)</div>
            <div class="card-sub">Ambang batas (safe harbor) yang diatur oleh peraturan perpajakan.</div>
        </div>
        <span class="badge badge-gray"><?= count($regulatory) ?> parameter</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:40px;">No</th>
                    <th style="text-align:left;">Nama Regulasi</th>
                    <th style="text-align:left;">Indikator</th>
                    <th style="text-align:center;">Batas Bawah</th>
                    <th style="text-align:center;">Batas Atas</th>
                    <th style="text-align:left;">Referensi Regulasi</th>
                    <?php if (session('role') !== 'analyst'): ?>
                    <th style="text-align:center;width:90px;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($regulatory)): ?>
                <tr><td colspan="7" class="text-center text-gray-500 py-8">Belum ada parameter regulasi.</td></tr>
                <?php else: foreach ($regulatory as $i => $p): ?>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;"><?= $i + 1 ?></td>
                    <td>
                        <span style="font-weight:600;color:#0f172a;"><?= esc($p['nama']) ?></span>
                        <div style="font-size:11px;color:#64748b;margin-top:2px;">
                            Berlaku: <?= $p['berlaku_sejak'] ? date('Y', strtotime($p['berlaku_sejak'])) : '—' ?> 
                            s/d <?= $p['berlaku_hingga'] ? date('Y', strtotime($p['berlaku_hingga'])) : 'Sekarang' ?>
                        </div>
                    </td>
                    <td style="font-weight:500;color:#374151;"><?= esc($p['variabel']) ?></td>
                    <td style="text-align:center;font-family:monospace;color:#3b5de7;"><?= number_format($p['batas_bawah'], 2) ?></td>
                    <td style="text-align:center;font-family:monospace;color:#e11d48;"><?= $p['batas_atas'] >= 999 ? '∞' : number_format($p['batas_atas'], 2) ?></td>
                    <td style="font-size:12px;">
                        <span style="display:block;font-weight:600;color:#1e40af;"><?= esc($p['regulasi_ref']) ?></span>
                        <?php if (!empty($p['regulasi_url'])): ?>
                        <a href="<?= esc($p['regulasi_url']) ?>" target="_blank" style="color:#3b82f6;text-decoration:none;"><i class="fa-solid fa-link"></i> Link</a>
                        <?php endif; ?>
                    </td>
                    <?php if (session('role') !== 'analyst'): ?>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button class="btn btn-primary btn-sm" onclick="openEditModal(<?= htmlspecialchars(json_encode($p), ENT_QUOTES, 'UTF-8') ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="openDeleteModal(<?= $p['id'] ?>, '<?= esc($p['nama']) ?>')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: TAMBAH PARAMETER -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalTambah">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-header-icon blue">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <h3 class="modal-title">Tambah Parameter Baru</h3>
            </div>
            <button class="modal-close" onclick="closeModal('modalTambah')">&times;</button>
        </div>
        <form action="/parameter-rasio/store" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Tipe Parameter</label>
                        <select name="tipe" id="tipeTambah" class="form-input" required onchange="toggleRegulasiFields('Tambah')">
                            <option value="analytical">Parameter Analitik (Analytical Threshold)</option>
                            <option value="regulatory">Parameter Regulasi (Tax Regulatory)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Parameter</label>
                        <input type="text" name="nama" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Indikator Terkait</label>
                        <select name="variabel" class="form-input" required>
                            <option value="">-- Pilih Indikator --</option>
                            <option value="ETR">ETR (Tax Aggressiveness)</option>
                            <option value="RPT Ratio">RPT Ratio</option>
                            <option value="DER">DER (Leverage)</option>
                            <option value="Sales Growth">Sales Growth</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Bawah</label>
                        <input type="number" name="batas_bawah" class="form-input" step="0.0001" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Atas</label>
                        <input type="number" name="batas_atas" class="form-input" step="0.0001" required>
                    </div>
                    <div class="form-group full-width analitik-only-tambah">
                        <label class="form-label">Warna Badge (Khusus Analitik)</label>
                        <select name="warna" class="form-input">
                            <option value="green">🟢 Hijau (Aman/Normal)</option>
                            <option value="amber">🟡 Kuning (Perhatian)</option>
                            <option value="rose">🔴 Merah (Risiko Tinggi)</option>
                            <option value="blue">🔵 Biru (Informasi)</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-input">
                    </div>
                    
                    <!-- Regulatory Fields -->
                    <div class="form-group full-width regulasi-only-tambah" style="display:none;background:#f8fafc;padding:12px;border:1px solid #e2e8f0;border-radius:8px;">
                        <label class="form-label">Referensi Regulasi</label>
                        <input type="text" name="regulasi_ref" class="form-input mb-3" placeholder="Contoh: PMK 169/2015">
                        <label class="form-label">URL Regulasi (Opsional)</label>
                        <input type="url" name="regulasi_url" class="form-input mb-3" placeholder="https://...">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div>
                                <label class="form-label">Berlaku Sejak</label>
                                <input type="date" name="berlaku_sejak" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Berlaku Hingga</label>
                                <input type="date" name="berlaku_hingga" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: EDIT PARAMETER -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-header-icon amber">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h3 class="modal-title">Edit Parameter</h3>
            </div>
            <button class="modal-close" onclick="closeModal('modalEdit')">&times;</button>
        </div>
        <form id="formEdit" action="" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Tipe Parameter</label>
                        <select name="tipe" id="editTipe" class="form-input" required onchange="toggleRegulasiFields('Edit')">
                            <option value="analytical">Parameter Analitik (Analytical Threshold)</option>
                            <option value="regulatory">Parameter Regulasi (Tax Regulatory)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Parameter</label>
                        <input type="text" name="nama" id="editNama" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Indikator Terkait</label>
                        <select name="variabel" id="editVariabel" class="form-input" required>
                            <option value="ETR">ETR (Tax Aggressiveness)</option>
                            <option value="RPT Ratio">RPT Ratio</option>
                            <option value="DER">DER (Leverage)</option>
                            <option value="Sales Growth">Sales Growth</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Bawah</label>
                        <input type="number" name="batas_bawah" id="editBatasBawah" class="form-input" step="0.0001" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Batas Atas</label>
                        <input type="number" name="batas_atas" id="editBatasAtas" class="form-input" step="0.0001" required>
                    </div>
                    <div class="form-group full-width analitik-only-edit">
                        <label class="form-label">Warna Badge (Khusus Analitik)</label>
                        <select name="warna" id="editWarna" class="form-input">
                            <option value="green">🟢 Hijau (Aman/Normal)</option>
                            <option value="amber">🟡 Kuning (Perhatian)</option>
                            <option value="rose">🔴 Merah (Risiko Tinggi)</option>
                            <option value="blue">🔵 Biru (Informasi)</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" id="editKeterangan" class="form-input">
                    </div>
                    
                    <!-- Regulatory Fields -->
                    <div class="form-group full-width regulasi-only-edit" style="display:none;background:#f8fafc;padding:12px;border:1px solid #e2e8f0;border-radius:8px;">
                        <label class="form-label">Referensi Regulasi</label>
                        <input type="text" name="regulasi_ref" id="editRegRef" class="form-input mb-3">
                        <label class="form-label">URL Regulasi (Opsional)</label>
                        <input type="url" name="regulasi_url" id="editRegUrl" class="form-input mb-3">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div>
                                <label class="form-label">Berlaku Sejak</label>
                                <input type="date" name="berlaku_sejak" id="editBerlakuSejak" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Berlaku Hingga</label>
                                <input type="date" name="berlaku_hingga" id="editBerlakuHingga" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
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
                Anda yakin ingin menghapus parameter <strong id="deleteParamName" style="color:#e11d48;"></strong>?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost" onclick="closeModal('modalHapus')">Batal</button>
            <a id="deleteLink" href="#" class="btn btn-danger">Hapus</a>
        </div>
    </div>
</div>

<script>
function toggleRegulasiFields(mode) {
    const tipe = document.getElementById('tipe' + mode) || document.getElementById('editTipe');
    const regFields = document.querySelector('.regulasi-only-' + mode.toLowerCase());
    const anaFields = document.querySelector('.analitik-only-' + mode.toLowerCase());
    if (tipe.value === 'regulatory') {
        if(regFields) regFields.style.display = 'block';
        if(anaFields) anaFields.style.display = 'none';
    } else {
        if(regFields) regFields.style.display = 'none';
        if(anaFields) anaFields.style.display = 'block';
    }
}

function openModal(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}

function openEditModal(param) {
    document.getElementById('formEdit').action = '/parameter-rasio/update/' + param.id;
    document.getElementById('editTipe').value = param.tipe || 'analytical';
    document.getElementById('editNama').value = param.nama;
    document.getElementById('editVariabel').value = param.variabel;
    document.getElementById('editBatasBawah').value = parseFloat(param.batas_bawah);
    document.getElementById('editBatasAtas').value = parseFloat(param.batas_atas);
    document.getElementById('editKeterangan').value = param.keterangan || '';
    document.getElementById('editWarna').value = param.warna;
    
    document.getElementById('editRegRef').value = param.regulasi_ref || '';
    document.getElementById('editRegUrl').value = param.regulasi_url || '';
    document.getElementById('editBerlakuSejak').value = param.berlaku_sejak ? param.berlaku_sejak.split(' ')[0] : '';
    document.getElementById('editBerlakuHingga').value = param.berlaku_hingga ? param.berlaku_hingga.split(' ')[0] : '';
    
    toggleRegulasiFields('Edit');
    openModal('modalEdit');
}

function openDeleteModal(id, nama) {
    document.getElementById('deleteParamName').textContent = nama;
    document.getElementById('deleteLink').href = '/parameter-rasio/delete/' + id;
    openModal('modalHapus');
}
</script>

<?= $this->endSection(); ?>
