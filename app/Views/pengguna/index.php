<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Manajemen User</h1>
        <p>Kelola akun pengguna yang memiliki akses ke Fynca Analytics.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalTambah')">
        <i class="fa-solid fa-user-plus"></i> Tambah User
    </button>
</div>

<!-- Stat Cards -->
<div class="stat-grid" style="grid-template-columns:repeat(2,1fr);margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff;"><i class="fa-solid fa-users" style="color:#3b5de7;"></i></div>
        <div>
            <div class="stat-label">Total User</div>
            <div class="stat-value"><?= count($users) ?></div>
            <div class="stat-hint">Pengguna terdaftar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fdf4ff;"><i class="fa-solid fa-user-shield" style="color:#9333ea;"></i></div>
        <div>
            <div class="stat-label">Admin</div>
            <div class="stat-value"><?= count(array_filter($users, fn($u) => strtolower($u['role']) === 'admin')) ?></div>
            <div class="stat-hint">Akses penuh</div>
        </div>
    </div>
</div>

<!-- Tabel User -->
<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Daftar Pengguna</div>
            <div class="card-sub">Semua akun yang terdaftar dalam sistem.</div>
        </div>
        <span class="badge badge-gray"><?= count($users) ?> user</span>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:40px;">No</th>
                    <th style="text-align:left;">Nama Lengkap</th>
                    <th style="text-align:left;">Username</th>
                    <th style="text-align:center;">Role</th>
                    <th style="text-align:left;">Dibuat Pada</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fa-solid fa-users"></i>
                            <p>Belum ada user yang terdaftar.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $i => $u): ?>
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
                    <td style="font-size:13px;color:#64748b;">@<?= esc($u['username']) ?></td>
                    <td style="text-align:center;">
                        <?php 
                            $r = strtolower($u['role']); 
                            $badgeClass = 'badge-gray';
                            if($r === 'admin') $badgeClass = 'badge-solid';
                            elseif($r === 'owner') $badgeClass = 'badge-amber';
                            elseif($r === 'analyst') $badgeClass = 'badge-blue';
                            elseif($r === 'bendahara') $badgeClass = 'badge-emerald';
                            elseif($r === 'akuntansi') $badgeClass = 'badge-rose';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= strtoupper(esc($u['role'])) ?></span>
                    </td>
                    <td style="font-size:12px;color:#94a3b8;"><?= date('d M Y, H:i', strtotime($u['created_at'])) ?></td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <button class="btn btn-primary btn-sm" onclick="openEditModal(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <?php if ($u['id_user'] != session()->get('id_user')): ?>
                            <button class="btn btn-danger btn-sm" onclick="openDeleteModal(<?= $u['id_user'] ?>, '<?= esc($u['nama_lengkap']) ?>')">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            <?php else: ?>
                            <button class="btn btn-gray btn-sm" title="Anda tidak dapat menghapus akun Anda sendiri" disabled>
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: TAMBAH USER -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalTambah">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon blue">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h3 class="modal-title">Tambah User Baru</h3>
                <p class="modal-sub">Buat akun untuk akses sistem.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalTambah')">&times;</button>
        </div>
        <form action="/pengguna/store" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-input" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-input" placeholder="Username untuk login" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role Akses</label>
                        <select name="role" class="form-input" required>
                            <option value="staff">Staff</option>
                            <option value="analyst">Analyst</option>
                            <option value="akuntansi">Akuntansi</option>
                            <option value="bendahara">Bendahara</option>
                            <option value="owner">Owner</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal('modalTambah')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: EDIT USER -->
<!-- ============================================= -->
<div class="modal-overlay" id="modalEdit">
    <div class="modal-container modal-sm">
        <div class="modal-header">
            <div class="modal-header-icon amber">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
                <h3 class="modal-title">Edit User</h3>
                <p class="modal-sub">Perbarui informasi akun pengguna.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalEdit')">&times;</button>
        </div>
        <form id="formEdit" action="" method="post">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="editNama" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" id="editUsername" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role Akses</label>
                        <select name="role" id="editRole" class="form-input" required>
                            <option value="staff">Staff</option>
                            <option value="analyst">Analyst</option>
                            <option value="akuntansi">Akuntansi</option>
                            <option value="bendahara">Bendahara</option>
                            <option value="owner">Owner</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru <span style="font-weight:normal;color:#94a3b8;font-size:11px;">(Kosongkan jika tidak ingin mengubah)</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Opsional" minlength="6">
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
                <i class="fa-solid fa-user-xmark"></i>
            </div>
            <div>
                <h3 class="modal-title">Hapus User</h3>
                <p class="modal-sub">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <button class="modal-close" onclick="closeModal('modalHapus')">&times;</button>
        </div>
        <div class="modal-body">
            <p style="color:#64748b;font-size:0.875rem;line-height:1.6;">
                Anda yakin ingin menghapus akun <strong id="deleteNamaUser" style="color:#e11d48;"></strong>? User ini tidak akan dapat login ke sistem lagi.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost" onclick="closeModal('modalHapus')">Batal</button>
            <a id="deleteLink" href="#" class="btn btn-danger">
                <i class="fa-solid fa-trash-can"></i> Ya, Hapus User
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

function openEditModal(user) {
    document.getElementById('formEdit').action = '/pengguna/update/' + user.id_user;
    document.getElementById('editNama').value = user.nama_lengkap;
    document.getElementById('editUsername').value = user.username;
    document.getElementById('editRole').value = user.role.toLowerCase();
    openModal('modalEdit');
}

function openDeleteModal(id, nama) {
    document.getElementById('deleteNamaUser').textContent = nama;
    document.getElementById('deleteLink').href = '/pengguna/delete/' + id;
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
