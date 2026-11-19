<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-6 sm:mb-8">
    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-bell text-blue-600 shrink-0"></i>
        <span class="truncate">Pending Reviews</span>
    </h1>
    <p class="text-gray-500 mt-1 text-xs sm:text-sm">Evaluate analyst reports waiting for executive approval</p>
</div>

<div class="space-y-8">
    <?php if(empty($reports)): ?>
        <div class="text-center py-12 text-gray-400 bg-white rounded-2xl shadow-sm border border-gray-100">
            <i class="fa-solid fa-check-double text-5xl mb-4 text-emerald-300"></i>
            <p>All caught up! No pending reviews at the moment.</p>
        </div>
    <?php else: ?>
        <?php foreach($reports as $r): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-50 to-white p-4 sm:p-6 border-b border-blue-100 flex flex-col sm:flex-row justify-between items-start gap-3">
                    <div>
                        <span class="text-xs font-bold bg-blue-100 text-blue-700 px-3 py-1 rounded-full uppercase tracking-wider mb-3 inline-block">Review Required</span>
                        <h2 class="text-xl font-bold text-gray-800 mb-1"><?= htmlspecialchars($r['judul']) ?></h2>
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fa-solid fa-building text-gray-400"></i> <?= htmlspecialchars($r['nama_perusahaan']) ?> (<?= $r['tahun'] ?>)
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-gray-700"><i class="fa-solid fa-user-pen text-gray-400 mr-1"></i> <?= htmlspecialchars($r['analyst_name']) ?></div>
                        <div class="text-xs text-gray-500 mt-1">Submitted: <?= date('M d, Y H:i', strtotime($r['updated_at'])) ?></div>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-8">
                    <!-- Left: Report Content -->
                    <div class="xl:col-span-2 space-y-6">
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Analyst Insights & Findings</h4>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-gray-700 whitespace-pre-wrap leading-relaxed text-sm"><?= htmlspecialchars($r['analyst_insight']) ?></div>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Recommendations / Action Plan</h4>
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 text-gray-700 whitespace-pre-wrap leading-relaxed text-sm font-medium"><?= htmlspecialchars($r['recommendation']) ?></div>
                        </div>
                    </div>
                    
                    <!-- Right: Indicators & Review Form -->
                    <div class="xl:col-span-1 border-t xl:border-t-0 xl:border-l border-gray-100 pt-6 xl:pt-0 xl:pl-6 space-y-6 flex flex-col">
                        
                        <!-- Indicators Snippet -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Calculated Indicators</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="text-[10px] text-gray-500 uppercase">ETR</div>
                                    <div class="font-bold text-gray-800"><?= number_format($r['val_etr'] * 100, 2) ?>%</div>
                                    <?php if(!empty($r['note_etr'])): ?>
                                        <div class="mt-2 text-[10px] text-gray-600 bg-white p-2 rounded border border-blue-100 border-l-2 border-l-blue-400">"<?= htmlspecialchars($r['note_etr']) ?>"</div>
                                    <?php endif; ?>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="text-[10px] text-gray-500 uppercase">RPT Ratio</div>
                                    <div class="font-bold text-gray-800"><?= number_format($r['val_rpt_ratio'] * 100, 2) ?>%</div>
                                    <?php if(!empty($r['note_rpt'])): ?>
                                        <div class="mt-2 text-[10px] text-gray-600 bg-white p-2 rounded border border-blue-100 border-l-2 border-l-blue-400">"<?= htmlspecialchars($r['note_rpt']) ?>"</div>
                                    <?php endif; ?>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="text-[10px] text-gray-500 uppercase">DER</div>
                                    <div class="font-bold text-gray-800"><?= number_format($r['val_der'], 2) ?></div>
                                    <?php if(!empty($r['note_der'])): ?>
                                        <div class="mt-2 text-[10px] text-gray-600 bg-white p-2 rounded border border-blue-100 border-l-2 border-l-blue-400">"<?= htmlspecialchars($r['note_der']) ?>"</div>
                                    <?php endif; ?>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="text-[10px] text-gray-500 uppercase">Growth</div>
                                    <div class="font-bold text-gray-800"><?= number_format($r['val_sales_growth'] * 100, 2) ?>%</div>
                                    <?php if(!empty($r['note_growth'])): ?>
                                        <div class="mt-2 text-[10px] text-gray-600 bg-white p-2 rounded border border-blue-100 border-l-2 border-l-blue-400">"<?= htmlspecialchars($r['note_growth']) ?>"</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Form -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex-1 flex flex-col">
                            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-gavel text-gray-500"></i> Executive Decision
                            </h4>
                            
                            <form action="<?= base_url('review-report/'.$r['id']) ?>" method="POST" class="flex-1 flex flex-col">
                                <div class="mb-4 flex-1">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Feedback / Notes (Optional for Approval, Required for Revision)</label>
                                    <textarea name="reviewer_comment" rows="4" class="w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm p-3 bg-gray-50"></textarea>
                                </div>
                                
                                <div class="flex gap-2 mt-auto">
                                    <button type="submit" name="action" value="revision" class="flex-1 bg-white hover:bg-orange-50 text-orange-600 border border-orange-200 py-2.5 rounded-lg font-bold text-sm transition-all shadow-sm">
                                        <i class="fa-solid fa-rotate-left"></i> Revise
                                    </button>
                                    <button type="submit" name="action" value="approve" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-lg font-bold text-sm transition-all shadow-sm shadow-emerald-200">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
