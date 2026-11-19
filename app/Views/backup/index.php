<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Backup & Restore Database</h1>
        <p class="text-sm text-gray-500 mt-1">Unduh salinan data sistem untuk keamanan atau pulihkan dari file backup sebelumnya.</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm font-semibold border border-green-100 flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm font-semibold border border-red-100 flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation text-red-500 text-lg"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Backup Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
        <div class="w-20 h-20 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-3xl mb-4">
            <i class="fa-solid fa-download"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Backup Database</h3>
        <p class="text-sm text-gray-500 mb-6">Proses ini akan mengunduh seluruh data sistem saat ini ke dalam format .sql. Lakukan secara berkala untuk mencegah kehilangan data.</p>
        
        <a href="/backup/download" class="w-full max-w-xs bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-md flex items-center justify-center gap-2">
            <i class="fa-solid fa-cloud-arrow-down"></i> Unduh File Backup
        </a>
    </div>

    <!-- Restore Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center opacity-70">
        <div class="w-20 h-20 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-3xl mb-4">
            <i class="fa-solid fa-upload"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800 mb-2">Restore Database</h3>
        <p class="text-sm text-gray-500 mb-6">Unggah file .sql backup Anda untuk memulihkan sistem ke titik waktu sebelumnya. <strong>Hati-hati: Data saat ini akan tertimpa!</strong></p>
        
        <!-- Currently disabled for safety in UI, need manual DB intervention for safer restore -->
        <button disabled class="w-full max-w-xs bg-gray-200 text-gray-500 font-bold py-3 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
            <i class="fa-solid fa-lock"></i> Fitur Restore Dikunci
        </button>
        <p class="text-[10px] text-amber-600 mt-3 font-semibold"><i class="fa-solid fa-info-circle"></i> Hubungi tim teknis server (CPanel/PMA) untuk melakukan restore demi keamanan data.</p>
    </div>
</div>

<?= $this->endSection(); ?>
