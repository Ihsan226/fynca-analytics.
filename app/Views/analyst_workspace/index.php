<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">My Assigned Work</h1>
        <p class="text-gray-500 mt-2">Daftar tugas analisis yang di-assign kepada Anda</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="py-4 px-6 font-semibold">Client</th>
                    <th class="py-4 px-6 font-semibold text-center">Tahun</th>
                    <th class="py-4 px-6 font-semibold text-center">Status</th>
                    <th class="py-4 px-6 font-semibold text-center">Indicators</th>
                    <th class="py-4 px-6 font-semibold text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan="5" class="py-12 px-6 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-folder-open text-2xl text-gray-300"></i>
                                </div>
                                <p>Belum ada laporan keuangan yang di-assign ke Anda.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reports as $r): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800"><?= htmlspecialchars($r['kode_saham']) ?></div>
                                <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($r['nama_perusahaan']) ?></div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-gray-100 text-gray-700 px-2.5 py-1 rounded-md text-xs font-bold"><?= $r['tahun'] ?></span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php 
                                    // Determine workflow status for the analyst
                                    $assignmentStatus = 'PENDING ANALYSIS';
                                    $statusClass = 'bg-gray-100 text-gray-700 border-gray-200';
                                    $icon = 'fa-hourglass-start';

                                    if ($r['analysis_status'] === 'draft') {
                                        $assignmentStatus = 'DRAFT';
                                        $statusClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        $icon = 'fa-pen-ruler';
                                    } elseif ($r['analysis_status'] === 'revision') {
                                        $assignmentStatus = 'REVISION REQUIRED';
                                        $statusClass = 'bg-red-50 text-red-700 border-red-200';
                                        $icon = 'fa-triangle-exclamation';
                                    } elseif ($r['analysis_status'] === 'submitted') {
                                        $assignmentStatus = 'SUBMITTED';
                                        $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                        $icon = 'fa-paper-plane';
                                    } elseif ($r['analysis_status'] === 'approved' || $r['analysis_status'] === 'completed') {
                                        $assignmentStatus = 'APPROVED';
                                        $statusClass = 'bg-green-50 text-green-700 border-green-200';
                                        $icon = 'fa-check-double';
                                    }
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] uppercase font-bold border <?= $statusClass ?>">
                                    <i class="fa-solid <?= $icon ?>"></i> <?= $assignmentStatus ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex flex-wrap justify-center gap-1">
                                    <?php if(isset($r['val_etr'])): ?>
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 border border-blue-200 rounded text-[9px] font-bold" title="ETR Available">ETR</span>
                                    <?php endif; ?>
                                    <?php if(isset($r['val_rpt_ratio'])): ?>
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 border border-indigo-200 rounded text-[9px] font-bold" title="RPT Available">RPT</span>
                                    <?php endif; ?>
                                    <?php if(isset($r['val_der'])): ?>
                                        <span class="px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-200 rounded text-[9px] font-bold" title="DER Available">DER</span>
                                    <?php endif; ?>
                                    <?php if(isset($r['val_sales_growth'])): ?>
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded text-[9px] font-bold" title="Growth Available">Growth</span>
                                    <?php endif; ?>
                                    <?php if(!isset($r['val_etr']) && !isset($r['val_rpt_ratio']) && !isset($r['val_der']) && !isset($r['val_sales_growth'])): ?>
                                        <span class="text-xs text-gray-400">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <?php if ($assignmentStatus === 'PENDING ANALYSIS'): ?>
                                    <a href="<?= base_url('workspace/analyze/'.$r['id_perusahaan'].'/'.$r['tahun']) ?>" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-all text-xs font-bold shadow-sm" title="Start Analysis">
                                        <i class="fa-solid fa-play mr-1.5"></i> Start
                                    </a>
                                <?php elseif ($assignmentStatus === 'DRAFT' || $assignmentStatus === 'REVISION REQUIRED'): ?>
                                    <a href="<?= base_url('workspace/edit/'.$r['id_analysis']) ?>" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition-all text-xs font-bold shadow-sm" title="Continue Analysis">
                                        <i class="fa-solid fa-pen mr-1.5"></i> <?= $assignmentStatus === 'REVISION REQUIRED' ? 'Fix Revision' : 'Continue' ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('workspace/analyze/'.$r['id_perusahaan'].'/'.$r['tahun']) ?>" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all text-xs font-bold" title="View Analysis">
                                        <i class="fa-solid fa-eye mr-1.5"></i> View
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
