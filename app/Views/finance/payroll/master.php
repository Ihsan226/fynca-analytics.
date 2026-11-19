<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Data Karyawan & Gaji</h1>
        <p>Atur komponen gaji pokok, tunjangan, dan potongan wajib untuk setiap karyawan.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Master Komponen Gaji</div>
            <div class="card-sub">Daftar staf, analis, dan bendahara.</div>
        </div>
        <span class="badge badge-gray"><?= count($users) ?> Karyawan</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:40px;">No</th>
                    <th style="text-align:left;">Nama Karyawan</th>
                    <th style="text-align:center;">Role</th>
                    <th style="text-align:right;">Gaji Pokok</th>
                    <th style="text-align:right;">Tunjangan</th>
                    <th style="text-align:right;">Potongan BPJS/Pajak</th>
                    <th style="text-align:right;">THP Estimasi</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fa-solid fa-users-slash"></i>
                            <p>Belum ada karyawan yang terdaftar.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $i => $u): 
                    $gaji = $u['gaji_pokok'] ?? 0;
                    $tunjangan = $u['tunjangan'] ?? 0;
                    $potongan = $u['potongan_bpjs'] ?? 0;
                    $thp = $gaji + $tunjangan - $potongan;
                ?>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;"><?= $i + 1 ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;">
                                <?= strtoupper(substr($u['nama_lengkap'], 0, 1)) ?>
                            </div>
                            <span style="font-weight:600;"><?= esc($u['nama_lengkap']) ?></span>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <span class="badge badge-gray"><?= strtoupper(esc($u['role'])) ?></span>
                    </td>
                    <td style="text-align:right; font-weight:600; color:#334155;">
                        Rp <?= number_format($gaji, 0, ',', '.') ?>
                    </td>
                    <td style="text-align:right; font-weight:600; color:#10b981;">
                        Rp <?= number_format($tunjangan, 0, ',', '.') ?>
                    </td>
                    <td style="text-align:right; font-weight:600; color:#ef4444;">
                        Rp <?= number_format($potongan, 0, ',', '.') ?>
                    </td>
                    <td style="text-align:right; font-weight:700; color:#4f46e5;">
                        Rp <?= number_format($thp, 0, ',', '.') ?>
                    </td>
                    <td style="text-align:center;">
                        <button class="btn btn-primary btn-sm" onclick="openEditModal(<?= htmlspecialchars(json_encode([
                            'id_user' => $u['id_user'],
                            'nama_lengkap' => $u['nama_lengkap'],
                            'gaji_pokok' => (int)$gaji,
                            'tunjangan' => (int)$tunjangan,
                            'potongan_bpjs' => (int)$potongan
                        ]), ENT_QUOTES, 'UTF-8') ?>)">
                            <i class="fa-solid fa-pen-to-square"></i> Set Gaji
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: SET GAJI -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon amber">
                <i class="fa-solid fa-money-check-dollar"></i>
            </div>
            <div>
                <h3 class="modal-title">Set Komponen Gaji</h3>
                <p class="modal-sub" id="editNamaKaryawan">Nama Karyawan</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalEdit')">&times;</button>
        </div>
        <form action="/finance/payroll/master-update" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_user" id="editIdUser">
            <div class="modal-body">
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Gaji Pokok (Rp)</label>
                        <input type="number" name="gaji_pokok" id="editGajiPokok" class="form-input" required min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tunjangan Tetap (Rp)</label>
                        <input type="number" name="tunjangan" id="editTunjangan" class="form-input" required min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Potongan Wajib / BPJS (Rp)</label>
                        <input type="number" name="potongan_bpjs" id="editPotongan" class="form-input" required min="0">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalEdit')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan
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

function openEditModal(data) {
    document.getElementById('editIdUser').value = data.id_user;
    document.getElementById('editNamaKaryawan').textContent = data.nama_lengkap;
    document.getElementById('editGajiPokok').value = data.gaji_pokok;
    document.getElementById('editTunjangan').value = data.tunjangan;
    document.getElementById('editPotongan').value = data.potongan_bpjs;
    openModal('modalEdit');
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
