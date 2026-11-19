<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Pengumuman Sistem</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi atau pengumuman yang akan disiarkan ke seluruh pengguna.</p>
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
        <i class="fa-solid fa-circle-xmark text-red-500 text-lg"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm font-semibold border border-red-100">
        <ul class="list-disc pl-5">
            <?php foreach(session()->getFlashdata('errors') as $err): ?>
                <li><?= $err ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:col-span-1 h-fit">
        <h3 class="text-md font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <i class="fa-solid fa-bullhorn text-indigo-500"></i> Buat Pengumuman Baru
        </h3>
        <form action="/broadcasts/store" method="post">
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Judul</label>
                <input type="text" name="title" value="<?= old('title') ?>" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm" placeholder="Contoh: Maintenance Server Malam Ini">
            </div>
            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Pesan</label>
                <textarea name="message" rows="4" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm" placeholder="Isi pesan pengumuman..."><?= old('message') ?></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors shadow-sm flex justify-center items-center gap-2">
                <i class="fa-solid fa-paper-plane"></i> Siarkan Sekarang
            </button>
        </form>
    </div>

    <!-- Riwayat Pengumuman -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-0 lg:col-span-2 overflow-hidden flex flex-col">
        <div class="bg-gray-50 px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-md font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> Riwayat Pengumuman
            </h3>
        </div>
        
        <div class="p-5 flex-1">
            <?php if(empty($broadcasts)): ?>
                <div class="text-center py-10">
                    <i class="fa-solid fa-folder-open text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada pengumuman yang dibuat.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach($broadcasts as $b): ?>
                        <div class="border <?= $b['status'] == 'active' ? 'border-indigo-200 bg-indigo-50/30' : 'border-gray-200 bg-gray-50' ?> rounded-xl p-4 flex gap-4 transition-all">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 <?= $b['status'] == 'active' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-200 text-gray-500' ?>">
                                <i class="fa-solid fa-bullhorn"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-gray-800 <?= $b['status'] == 'inactive' ? 'line-through text-gray-500' : '' ?>"><?= esc($b['title']) ?></h4>
                                    <span class="text-xs text-gray-400 font-semibold bg-white px-2 py-0.5 rounded border border-gray-200">
                                        <?= date('d M Y, H:i', strtotime($b['created_at'])) ?>
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3"><?= nl2br(esc($b['message'])) ?></p>
                                
                                <div class="flex gap-2">
                                    <a href="/broadcasts/toggle/<?= $b['id'] ?>" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors <?= $b['status'] == 'active' ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' ?>">
                                        <?= $b['status'] == 'active' ? '<i class="fa-solid fa-eye-slash mr-1"></i> Nonaktifkan' : '<i class="fa-solid fa-eye mr-1"></i> Aktifkan' ?>
                                    </a>
                                    <a href="/broadcasts/delete/<?= $b['id'] ?>" onclick="return confirm('Hapus pengumuman ini secara permanen?')" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors">
                                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
