<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Review Status</h1>
    <p class="text-gray-500 mt-2">Track the progress of your submitted analyses</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Pending Review (Submitted) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-700 mb-6 flex items-center gap-2 pb-3 border-b">
            <i class="fa-solid fa-hourglass-half text-blue-500"></i> Pending Review
            <span class="ml-auto bg-blue-100 text-blue-700 text-xs py-1 px-3 rounded-full">
                <?= count(array_filter($reports, fn($r) => $r['status'] === 'submitted')) ?>
            </span>
        </h2>
        
        <div class="space-y-4">
            <?php $hasPending = false; ?>
            <?php foreach ($reports as $r): ?>
                <?php if ($r['status'] === 'submitted'): $hasPending = true; ?>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-800"><?= htmlspecialchars($r['judul']) ?></h3>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-md font-semibold">Submitted</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><i class="fa-solid fa-building mr-1"></i> <?= htmlspecialchars($r['nama_perusahaan']) ?> (<?= $r['tahun'] ?>)</p>
                        <div class="text-xs text-gray-400">
                            Sent: <?= date('d M Y, H:i', strtotime($r['updated_at'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <?php if (!$hasPending): ?>
                <div class="text-center py-8 text-gray-400">
                    <i class="fa-regular fa-face-smile text-3xl mb-3"></i>
                    <p>All caught up! No pending reviews.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Needs Revision -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-700 mb-6 flex items-center gap-2 pb-3 border-b">
            <i class="fa-solid fa-rotate-left text-orange-500"></i> Needs Revision
            <span class="ml-auto bg-orange-100 text-orange-700 text-xs py-1 px-3 rounded-full">
                <?= count(array_filter($reports, fn($r) => $r['status'] === 'revision')) ?>
            </span>
        </h2>
        
        <div class="space-y-4">
            <?php $hasRevision = false; ?>
            <?php foreach ($reports as $r): ?>
                <?php if ($r['status'] === 'revision'): $hasRevision = true; ?>
                    <div class="bg-orange-50 rounded-xl p-4 border border-orange-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-orange-900"><?= htmlspecialchars($r['judul']) ?></h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><i class="fa-solid fa-building mr-1"></i> <?= htmlspecialchars($r['nama_perusahaan']) ?> (<?= $r['tahun'] ?>)</p>
                        
                        <div class="bg-white/60 p-3 rounded-lg border border-orange-100 text-sm text-orange-800 italic mb-4">
                            "<?= htmlspecialchars($r['reviewer_comment'] ?? 'Please revise the report based on offline feedback.') ?>"
                        </div>
                        
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Reviewed: <?= date('d M Y', strtotime($r['reviewed_at'] ?? $r['updated_at'])) ?></span>
                            <a href="<?= base_url('workspace/edit/'.$r['id']) ?>" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded-lg transition-all font-bold">
                                Fix Now
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <?php if (!$hasRevision): ?>
                <div class="text-center py-8 text-gray-400">
                    <i class="fa-solid fa-check text-3xl mb-3 text-green-300"></i>
                    <p>Great job! No revisions requested.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
