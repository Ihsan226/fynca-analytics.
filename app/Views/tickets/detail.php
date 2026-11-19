<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <a href="/tickets" class="text-sm font-semibold text-gray-400 hover:text-indigo-600 mb-2 inline-flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tiket
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Tiket #<?= str_pad($ticket['id'], 4, '0', STR_PAD_LEFT) ?></h1>
        <p class="text-sm text-gray-500 mt-1">Subjek: <span class="font-bold text-gray-700"><?= esc($ticket['subject']) ?></span></p>
    </div>
    
    <?php if($ticket['status'] != 'resolved'): ?>
        <a href="/tickets/resolve/<?= $ticket['id'] ?>" onclick="return confirm('Apakah Anda yakin tiket ini sudah selesai dan ingin ditutup?')" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 font-bold py-2.5 px-5 rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-check-double"></i> Tandai Selesai (Resolved)
        </a>
    <?php else: ?>
        <span class="bg-emerald-100 text-emerald-700 font-bold py-2.5 px-5 rounded-xl text-sm flex items-center gap-2 border border-emerald-200">
            <i class="fa-solid fa-lock"></i> Tiket Selesai
        </span>
    <?php endif; ?>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chat Area -->
    <div class="lg:col-span-2 flex flex-col gap-4">
        
        <!-- Pesan Asli / Pertama -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold">
                    <?= strtoupper(substr($ticket['sender_name'], 0, 1)) ?>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800"><?= esc($ticket['sender_name']) ?> <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full ml-1 uppercase"><?= esc($ticket['sender_role']) ?></span></p>
                    <p class="text-[11px] text-gray-400 font-semibold"><?= date('d M Y, H:i', strtotime($ticket['created_at'])) ?></p>
                </div>
            </div>
            <!-- Subjek as initial message representation, ideally there should be initial message but for simple design let's just show subject as initial ticket -->
            <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl rounded-tl-none text-sm text-gray-700 mt-2">
                <strong>Subjek:</strong> <?= esc($ticket['subject']) ?><br><br>
                <em>(Tiket dibuat oleh pengguna)</em>
            </div>
        </div>

        <!-- Replies -->
        <?php foreach($replies as $reply): ?>
            <?php $isAdmin = ($reply['replier_role'] == 'admin'); ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative <?= $isAdmin ? 'ml-8 md:ml-16 border-indigo-100' : 'mr-8 md:mr-16' ?>">
                <div class="flex items-center gap-3 mb-3 <?= $isAdmin ? 'flex-row-reverse text-right' : '' ?>">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold shrink-0 <?= $isAdmin ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500' ?>">
                        <?= strtoupper(substr($reply['replier_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <p class="text-sm font-bold <?= $isAdmin ? 'text-indigo-800' : 'text-gray-800' ?>">
                            <?= esc($reply['replier_name']) ?> 
                            <span class="text-[10px] <?= $isAdmin ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500' ?> px-2 py-0.5 rounded-full <?= $isAdmin ? 'mr-1' : 'ml-1' ?> uppercase">
                                <?= esc($reply['replier_role']) ?>
                            </span>
                        </p>
                        <p class="text-[11px] text-gray-400 font-semibold"><?= date('d M Y, H:i', strtotime($reply['created_at'])) ?></p>
                    </div>
                </div>
                
                <div class="<?= $isAdmin ? 'bg-indigo-50 border-indigo-100 rounded-tr-none' : 'bg-gray-50 border-gray-100 rounded-tl-none' ?> border p-4 rounded-xl text-sm text-gray-700 mt-2">
                    <?= nl2br(esc($reply['message'])) ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Reply Form -->
        <?php if($ticket['status'] != 'resolved'): ?>
            <div class="bg-white rounded-xl shadow-sm border border-indigo-100 p-5 mt-4">
                <form action="/tickets/reply/<?= $ticket['id'] ?>" method="post">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2"><i class="fa-solid fa-reply mr-1"></i> Balas Tiket</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm mb-3" placeholder="Ketik balasan Anda di sini..."></textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-sm transition-colors shadow-md flex items-center gap-2">
                            Kirim Balasan <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 sticky top-6">
            <h3 class="text-sm font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-500"></i> Informasi Tiket
            </h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status Saat Ini</p>
                    <?php if($ticket['status'] == 'open'): ?>
                        <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-full text-xs font-bold inline-block">Open</span>
                    <?php elseif($ticket['status'] == 'in_progress'): ?>
                        <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold inline-block">In Progress</span>
                    <?php else: ?>
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold inline-block">Resolved</span>
                    <?php endif; ?>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Pengirim</p>
                    <p class="text-sm font-bold text-gray-800"><?= esc($ticket['sender_name']) ?></p>
                    <p class="text-xs text-gray-500 capitalize"><?= esc($ticket['sender_role']) ?></p>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Waktu Dibuat</p>
                    <p class="text-sm font-semibold text-gray-700"><?= date('d F Y, H:i', strtotime($ticket['created_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
