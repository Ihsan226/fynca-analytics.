<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Support Tickets</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola tiket bantuan dan keluhan dari staf atau analis.</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 text-sm font-semibold border border-green-100 flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50/50 text-gray-500 text-xs uppercase font-bold tracking-wider border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">ID Tiket</th>
                    <th class="px-6 py-4">Pengirim</th>
                    <th class="px-6 py-4">Subjek</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Dibuat Pada</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if(empty($tickets)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-check-circle text-4xl mb-3 text-emerald-300"></i>
                            <p>Tidak ada tiket bantuan yang terbuka.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tickets as $t): ?>
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-800">#<?= str_pad($t['id'], 4, '0', STR_PAD_LEFT) ?></td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-700"><?= esc($t['sender_name']) ?></span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800"><?= esc($t['subject']) ?></td>
                            <td class="px-6 py-4">
                                <?php if($t['status'] == 'open'): ?>
                                    <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-full text-xs font-bold">Open</span>
                                <?php elseif($t['status'] == 'in_progress'): ?>
                                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">In Progress</span>
                                <?php else: ?>
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Resolved</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs"><?= date('d M Y, H:i', strtotime($t['created_at'])) ?></td>
                            <td class="px-6 py-4 text-right">
                                <a href="/tickets/<?= $t['id'] ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 text-indigo-600 rounded-lg text-xs font-semibold transition-all">
                                    <i class="fa-regular fa-comment-dots"></i> Balas
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>
