<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-6 sm:mb-8">
    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-chart-column text-blue-600 shrink-0"></i>
        <span class="truncate">Analysis Results</span>
    </h1>
    <p class="text-gray-500 mt-1 text-xs sm:text-sm">Comprehensive table of raw tax indicators for all clients</p>
</div>

<div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="text-base sm:text-lg font-bold text-gray-700">Calculated Indicators</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" style="min-width: 600px;">
            <thead>
                <tr class="bg-white border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="py-4 px-6 font-semibold">Client</th>
                    <th class="py-4 px-6 font-semibold">Year</th>
                    <th class="py-4 px-6 font-semibold text-center">ETR</th>
                    <th class="py-4 px-6 font-semibold text-center">RPT Ratio</th>
                    <th class="py-4 px-6 font-semibold text-center">DER</th>
                    <th class="py-4 px-6 font-semibold text-center">Sales Growth</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($analisis)): ?>
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">No analysis data available.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($analisis as $row): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama_perusahaan']) ?></div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($row['kode_saham']) ?></div>
                        </td>
                        <td class="py-4 px-6 font-semibold text-gray-700">
                            <?= $row['tahun'] ?>
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-gray-800">
                            <?= number_format($row['val_etr'] * 100, 2) ?>%
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-gray-800">
                            <?= number_format($row['val_rpt_ratio'] * 100, 2) ?>%
                        </td>
                        <td class="py-4 px-6 text-center font-bold text-gray-800">
                            <?= number_format($row['val_der'], 2) ?>
                        </td>
                        <td class="py-4 px-6 text-center font-bold <?= $row['val_sales_growth'] < 0 ? 'text-red-600' : 'text-emerald-600' ?>">
                            <?= number_format($row['val_sales_growth'] * 100, 2) ?>%
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
