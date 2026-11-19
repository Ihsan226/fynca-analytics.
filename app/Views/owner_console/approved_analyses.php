<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-6 sm:mb-8">
    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-600 shrink-0"></i>
        <span class="truncate">Approved Analyses</span>
    </h1>
    <p class="text-gray-500 mt-1 text-xs sm:text-sm">Archive of all tax analysis reports that have been approved by the Executive</p>
</div>

<div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-base sm:text-lg font-bold text-gray-700">Approved Reports Archive</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" style="min-width: 600px;">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="py-4 px-6 font-semibold">Report Title</th>
                    <th class="py-4 px-6 font-semibold">Client & Year</th>
                    <th class="py-4 px-6 font-semibold">Analyst</th>
                    <th class="py-4 px-6 font-semibold">Approved Date</th>
                    <th class="py-4 px-6 font-semibold text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($reports)): ?>
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-500">
                        <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300"></i>
                        <p>No approved analyses found in the archive.</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($reports as $row): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="font-bold text-gray-800"><?= htmlspecialchars($row['judul']) ?></div>
                            <div class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded uppercase w-max mt-1">
                                <?= str_replace('_', ' ', $row['jenis_analisis']) ?>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-gray-700"><?= htmlspecialchars($row['nama_perusahaan']) ?></div>
                            <div class="text-xs text-gray-500">Year: <?= $row['tahun'] ?></div>
                        </td>
                        <td class="py-4 px-6 font-medium text-gray-700">
                            <?= htmlspecialchars($row['analyst_name']) ?>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-600">
                            <?= date('M d, Y', strtotime($row['reviewed_at'])) ?>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> Approved
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
