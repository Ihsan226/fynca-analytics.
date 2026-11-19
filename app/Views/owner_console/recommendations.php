<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="min-w-0">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-lightbulb text-amber-500 shrink-0"></i>
            <span class="truncate">Strategic Recommendations</span>
        </h1>
        <p class="text-gray-500 mt-1 text-xs sm:text-sm">Actionable insights curated from approved analyst reports</p>
    </div>
</div>

<div class="space-y-6">
    <?php if(empty($reports)): ?>
        <div class="text-center py-12 text-gray-400 bg-white rounded-2xl shadow-sm border border-gray-100">
            <i class="fa-regular fa-lightbulb text-4xl mb-4 text-gray-300"></i>
            <p>No recommendations available yet.</p>
        </div>
    <?php else: ?>
        <?php foreach($reports as $r): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row gap-6">
                <!-- Context -->
                <div class="w-full md:w-1/3 md:border-r border-gray-100 pr-6">
                    <div class="text-xs font-bold bg-amber-100 text-amber-700 px-3 py-1 rounded-full w-max mb-3 uppercase tracking-wider">
                        <?= str_replace('_', ' ', $r['jenis_analisis']) ?>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-1"><?= htmlspecialchars($r['nama_perusahaan']) ?></h3>
                    <p class="text-xs text-gray-500 mb-4"><?= htmlspecialchars($r['kode_saham']) ?> &bull; <?= $r['tahun'] ?></p>
                    
                    <div class="flex items-center gap-2 mt-4 text-sm text-gray-600">
                        <i class="fa-solid fa-user text-gray-400"></i> By <?= htmlspecialchars($r['analyst_name']) ?>
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Approved: <?= date('M d, Y', strtotime($r['reviewed_at'])) ?>
                    </div>
                </div>
                
                <!-- Recommendation -->
                <div class="w-full md:w-2/3">
                    <h4 class="font-bold text-gray-700 mb-2">Recommended Action:</h4>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-gray-700 whitespace-pre-wrap leading-relaxed"><?= htmlspecialchars($r['recommendation']) ?></div>
                    
                    <h4 class="font-bold text-gray-700 mt-4 mb-2">Analyst Insight:</h4>
                    <div class="text-sm text-gray-500 whitespace-pre-wrap leading-relaxed"><?= htmlspecialchars($r['analyst_insight']) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
