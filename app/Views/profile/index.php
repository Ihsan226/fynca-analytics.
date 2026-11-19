<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold text-gray-800" style="letter-spacing: -0.4px;">Profil Saya</h1>
        <p class="text-[14.5px] text-[#6B7280] mt-0.5">Kelola informasi akun Anda di Fynca Analytics.</p>
    </div>
    <a href="/dashboard" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-indigo-600 transition-colors font-medium bg-white border border-gray-200 px-3 py-1.5 rounded-lg shadow-sm mt-1">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-emerald-50 text-emerald-700 border border-emerald-200 p-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-check-circle text-emerald-500 text-lg"></i>
        <div class="text-sm font-medium"><?= session()->getFlashdata('success') ?></div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="bg-red-50 text-red-700 border border-red-200 p-4 rounded-xl mb-6 shadow-sm">
        <div class="flex items-center gap-3 font-semibold mb-2">
            <i class="fa-solid fa-triangle-exclamation text-red-500 text-lg"></i> Terdapat Kesalahan:
        </div>
        <ul class="list-disc pl-5 text-sm space-y-1">
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-[340px_1fr] gap-5">
    
    <!-- Kolom Kiri: Ringkasan Identitas -->
    <div class="bg-white rounded-[16px] border border-[#EEF0F3] p-[28px] shadow-[0_1px_3px_rgba(16,24,40,0.05)] h-fit">
        <div class="flex flex-col items-center text-center">
            <!-- Avatar -->
            <div class="relative mb-4">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="/uploads/avatars/<?= esc($user['avatar']) ?>" id="avatarPreview" class="w-[84px] h-[84px] rounded-full object-cover shadow-md border-2 border-white" alt="Avatar">
                <?php else: ?>
                    <div id="avatarFallback" class="w-[84px] h-[84px] rounded-full flex items-center justify-center text-3xl font-bold text-white shadow-md" style="background: linear-gradient(135deg, #6366F1, #8B5CF6);">
                        <?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>
                    </div>
                    <img src="" id="avatarPreview" class="w-[84px] h-[84px] rounded-full object-cover shadow-md border-2 border-white hidden" alt="Avatar">
                <?php endif; ?>
                
                <button type="button" id="btnChangeAvatar" class="absolute bottom-0 right-0 w-8 h-8 bg-white border border-[#E5E7EB] rounded-full flex items-center justify-center text-gray-500 hover:text-indigo-600 hover:border-indigo-200 transition-colors shadow-sm" title="Ubah Foto">
                    <i class="fa-solid fa-camera text-[13px]"></i>
                </button>
            </div>
            
            <!-- Nama & Role -->
            <h2 class="text-[18px] font-bold text-gray-800 mb-2"><?= esc($user['nama_lengkap']) ?></h2>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#EEF2FF] text-[#4338CA] text-xs font-semibold mb-6">
                <i class="fa-solid fa-shield-halved"></i>
                <span class="uppercase"><?= esc($user['role']) ?></span>
            </div>
            
            <!-- Info Tambahan -->
            <div class="w-full border-t border-[#F1F2F4] pt-5 space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Bergabung sejak</span>
                    <span class="font-semibold text-gray-800"><?= isset($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '-' ?></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500">Login terakhir</span>
                    <span class="font-semibold text-gray-800"><?= isset($user['last_login']) && $user['last_login'] ? date('d M Y, H:i', strtotime($user['last_login'])) : 'Hari ini' ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kolom Kanan: Form Edit -->
    <div class="bg-white rounded-[16px] border border-[#EEF0F3] p-[28px] shadow-[0_1px_3px_rgba(16,24,40,0.05)]">
        <form action="/profile/update" method="post" id="profileForm" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/png, image/jpeg, image/jpg">
            
            <!-- Section: Informasi Akun -->
            <h3 class="text-[15px] font-semibold text-gray-800 mb-4">Informasi Akun</h3>
            
            <div class="space-y-4">
                <div>
                    <label for="nama_lengkap" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fa-solid fa-user text-[#9CA3AF] text-[14px]"></i> Nama Lengkap
                    </label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>" class="w-full px-[14px] py-[11px] rounded-[10px] border border-[#E5E7EB] bg-white text-[14.5px] text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" required>
                </div>
                
                <div>
                    <label for="username" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                        <i class="fa-solid fa-at text-[#9CA3AF] text-[14px]"></i> Username
                    </label>
                    <input type="text" name="username" id="username" value="<?= old('username', $user['username']) ?>" class="w-full px-[14px] py-[11px] rounded-[10px] border border-[#E5E7EB] bg-white text-[14.5px] text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" required>
                </div>
            </div>
            
            <!-- Separator -->
            <div class="my-[24px] border-t border-[#F1F2F4]"></div>
            
            <!-- Section: Ubah Password -->
            <h3 class="text-[15px] font-semibold text-gray-800 mb-4">Ubah Password</h3>
            
            <div class="mb-8">
                <label for="password" class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-lock text-[#9CA3AF] text-[14px]"></i> Password Baru 
                    <span class="text-xs font-normal text-gray-400 ml-1">(Kosongkan jika tidak ingin mengubah)</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full pl-[14px] pr-10 py-[11px] rounded-[10px] border border-[#E5E7EB] bg-white text-[14.5px] text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
                
                <!-- Password Strength Indicator -->
                <div class="mt-2 flex gap-1 h-1 w-full max-w-[200px]" id="strengthBars">
                    <div class="h-full w-1/4 bg-gray-200 rounded-full transition-colors duration-300"></div>
                    <div class="h-full w-1/4 bg-gray-200 rounded-full transition-colors duration-300"></div>
                    <div class="h-full w-1/4 bg-gray-200 rounded-full transition-colors duration-300"></div>
                    <div class="h-full w-1/4 bg-gray-200 rounded-full transition-colors duration-300"></div>
                </div>
                <p id="strengthText" class="text-[10px] text-gray-400 mt-1 hidden"></p>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end gap-[10px]">
                <a href="/profile" class="px-[20px] py-[10px] bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-[20px] py-[10px] bg-[#4F46E5] hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Avatar Upload & Preview ---
        const btnChangeAvatar = document.getElementById('btnChangeAvatar');
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarFallback = document.getElementById('avatarFallback');

        btnChangeAvatar.addEventListener('click', function() {
            avatarInput.click();
        });

        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarPreview.classList.remove('hidden');
                    if (avatarFallback) {
                        avatarFallback.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // --- Password Fields ---
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const strengthBars = document.getElementById('strengthBars').children;
        const strengthText = document.getElementById('strengthText');
        
        // Toggle Show/Hide Password
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
        
        // Password Strength Checker
        passwordInput.addEventListener('input', function() {
            const val = passwordInput.value;
            let strength = 0;
            
            // Reset bars
            for (let i = 0; i < strengthBars.length; i++) {
                strengthBars[i].className = 'h-full w-1/4 bg-gray-200 rounded-full transition-colors duration-300';
            }
            
            if (val.length === 0) {
                strengthText.classList.add('hidden');
                return;
            }
            
            strengthText.classList.remove('hidden');
            
            if (val.length >= 1) strength += 1;
            if (val.length >= 6) strength += 1;
            if (val.match(/[a-z]/) && val.match(/[A-Z]/)) strength += 1;
            if (val.match(/\d/) || val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) strength += 1;
            
            let colorClass = 'bg-red-400';
            let txt = 'Sangat Lemah';
            let txtColor = 'text-red-500';
            
            if (strength === 2) {
                colorClass = 'bg-yellow-400';
                txt = 'Sedang';
                txtColor = 'text-yellow-600';
            } else if (strength >= 3) {
                colorClass = 'bg-emerald-500';
                txt = 'Kuat';
                txtColor = 'text-emerald-600';
            }
            
            for (let i = 0; i < strength; i++) {
                strengthBars[i].className = `h-full w-1/4 rounded-full transition-colors duration-300 ${colorClass}`;
            }
            
            strengthText.textContent = txt;
            strengthText.className = `text-[10px] mt-1 font-medium ${txtColor}`;
        });
    });
</script>

<?= $this->endSection() ?>
