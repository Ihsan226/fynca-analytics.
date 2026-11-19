<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Mesin Jurnal & General Ledger</h2>
        <p class="text-sm text-gray-500 mt-1">Pemrosesan data mentah yang dikirim oleh Staff menjadi entri jurnal standar.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Antrean Data Mentah dari Staff</h3>
            <p class="text-[10px] text-gray-500">Data berikut belum di-mapping ke dalam Chart of Accounts (CoA).</p>
        </div>
        <button class="px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Generate Jurnal
        </button>
    </div>
<?php if (!empty($pendingReports)): ?>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-100">
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase">Perusahaan</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase">Tahun Laporan</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="p-4 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($pendingReports as $report): ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 font-bold text-gray-800"><?= esc($report['nama_perusahaan']) ?></td>
                    <td class="p-4 text-gray-600"><?= esc($report['tahun']) ?></td>
                    <td class="p-4">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <i class="fa-solid fa-clock"></i> PENDING JURNAL
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <a href="/accounting/generate-journal/<?= $report['id_laporan'] ?>" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                            <i class="fa-solid fa-gears"></i> Buat Jurnal
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="p-8 text-center">
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
            <i class="fa-solid fa-check-double text-2xl"></i>
        </div>
        <h4 class="text-sm font-bold text-gray-700 mb-1">Seluruh data telah diproses</h4>
        <p class="text-xs text-gray-500">Saat ini tidak ada antrean data baru dari Staff Keuangan yang perlu dibuatkan jurnal.</p>
    </div>
<?php endif; ?>
</div>

<?= $this->endSection(); ?>
