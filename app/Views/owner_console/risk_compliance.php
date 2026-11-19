<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-6 sm:mb-8">
    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-shield-halved text-red-600 shrink-0"></i>
        <span class="truncate">Risk & Compliance</span>
    </h1>
    <p class="text-gray-500 mt-1 text-xs sm:text-sm">Identify high-risk clients based on tax indicators and explanation engine</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
    <?php if(empty($all_analyses)): ?>
        <div class="col-span-full py-12 text-center text-gray-400">
            <i class="fa-solid fa-shield text-5xl mb-4 text-gray-300"></i>
            <p>No risk data available.</p>
        </div>
    <?php else: ?>
        <?php foreach($all_analyses as $row): ?>
            <div class="bg-white rounded-2xl shadow-sm border <?= $row['risk_score'] < 60 ? 'border-red-200 shadow-red-100' : 'border-gray-100' ?> p-6 flex flex-col relative">
                <?php if($row['risk_score'] < 60): ?>
                    <div class="absolute top-0 right-0 bg-red-600 text-white text-[10px] font-black px-3 py-1 rounded-bl-xl rounded-tr-2xl uppercase tracking-wider">High Risk</div>
                <?php endif; ?>
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg leading-tight"><?= htmlspecialchars($row['nama_perusahaan']) ?></h3>
                        <p class="text-xs text-gray-500"><?= htmlspecialchars($row['kode_saham']) ?> &bull; <?= $row['tahun'] ?></p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg <?= $row['risk_score'] >= 80 ? 'bg-emerald-100 text-emerald-700' : ($row['risk_score'] >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') ?>">
                            <?= $row['risk_score'] ?>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 mt-4 flex-1">
                    <?php foreach($row['explanations'] as $exp): ?>
                        <?php if(in_array($exp['level'], ['high', 'negative'])): ?>
                            <div class="bg-red-50 text-red-800 p-3 rounded-xl border border-red-100 text-xs flex gap-2 items-start">
                                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                                <span><?= htmlspecialchars($exp['explanation']) ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
