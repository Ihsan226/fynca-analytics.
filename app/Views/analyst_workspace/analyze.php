<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fa-solid fa-microscope text-blue-600 mr-2"></i> Workspace: <?= htmlspecialchars($perusahaan['nama_perusahaan']) ?> (<?= $tahun ?>)
        </h1>
        <p class="text-gray-500 mt-2">Analyze tax indicators and compile your report</p>
    </div>
    <a href="<?= base_url('workspace') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl transition-all font-medium text-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to Selection
    </a>
</div>

<?php if ($existingDraft): ?>
    <div class="alert-message p-4 mb-6 text-sm text-yellow-800 bg-yellow-50 rounded-xl border border-yellow-200 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i> 
            You already have a saved draft for this client and year.
        </div>
        <a href="<?= base_url('workspace/edit/'.$existingDraft['id']) ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm">
            Edit Draft
        </a>
    </div>
<?php endif; ?>

<form id="analysisForm" action="<?= base_url('workspace/store') ?>" method="POST" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Left Column: Indicators & Regulations -->
    <div class="xl:col-span-1 space-y-6">
        
        <!-- Indicator Scores -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-chart-pie text-blue-500 mr-2"></i> Auto-Calculated Indicators</h3>
            
            <div class="space-y-4">
                <!-- ETR -->
                <div class="p-4 rounded-xl border <?= $explanations['etr']['level'] === 'high' ? 'bg-red-50 border-red-100' : ($explanations['etr']['level'] === 'low' ? 'bg-green-50 border-green-100' : 'bg-yellow-50 border-yellow-100') ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">Effective Tax Rate (ETR)</span>
                        <span class="text-lg font-black text-gray-800"><?= number_format($row['val_etr'] * 100, 2) ?>%</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 mb-3"><?= htmlspecialchars($explanations['etr']['explanation']) ?></p>
                    <textarea name="note_etr" rows="2" placeholder="Catatan Analis untuk ETR (opsional)..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 shadow-sm"></textarea>
                </div>
                
                <!-- RPT -->
                <div class="p-4 rounded-xl border <?= $explanations['rpt']['level'] === 'high' ? 'bg-red-50 border-red-100' : ($explanations['rpt']['level'] === 'low' ? 'bg-green-50 border-green-100' : 'bg-yellow-50 border-yellow-100') ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">RPT Ratio</span>
                        <span class="text-lg font-black text-gray-800"><?= number_format($row['val_rpt_ratio'] * 100, 2) ?>%</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 mb-3"><?= htmlspecialchars($explanations['rpt']['explanation']) ?></p>
                    <textarea name="note_rpt" rows="2" placeholder="Catatan Analis untuk RPT Ratio (opsional)..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 shadow-sm"></textarea>
                </div>

                <!-- DER -->
                <div class="p-4 rounded-xl border <?= $explanations['der']['level'] === 'high' ? 'bg-red-50 border-red-100' : ($explanations['der']['level'] === 'low' ? 'bg-green-50 border-green-100' : 'bg-yellow-50 border-yellow-100') ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">Debt-to-Equity Ratio</span>
                        <span class="text-lg font-black text-gray-800"><?= number_format($row['val_der'] * 100, 2) ?>%</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 mb-3"><?= htmlspecialchars($explanations['der']['explanation']) ?></p>
                    <textarea name="note_der" rows="2" placeholder="Catatan Analis untuk DER (opsional)..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 shadow-sm"></textarea>
                </div>

                <!-- Sales Growth -->
                <div class="p-4 rounded-xl border <?= $explanations['growth']['level'] === 'negative' ? 'bg-red-50 border-red-100' : ($explanations['growth']['level'] === 'positive' ? 'bg-green-50 border-green-100' : 'bg-yellow-50 border-yellow-100') ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">Sales Growth</span>
                        <span class="text-lg font-black text-gray-800"><?= number_format($row['val_sales_growth'] * 100, 2) ?>%</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 mb-3"><?= htmlspecialchars($explanations['growth']['explanation']) ?></p>
                    <textarea name="note_growth" rows="2" placeholder="Catatan Analis untuk Sales Growth (opsional)..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 shadow-sm"></textarea>
                </div>
            </div>
        </div>

        <!-- Quick Regulations Reference -->
        <div class="bg-slate-800 rounded-2xl shadow-lg p-6 text-white h-96 flex flex-col">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2 border-b border-slate-700 pb-2">
                <i class="fa-solid fa-book text-blue-400"></i> Regulatory Ref.
            </h3>
            <div class="overflow-y-auto pr-2 space-y-3 flex-1 custom-scrollbar">
                <?php if (empty($regulasi)): ?>
                    <p class="text-sm text-slate-400 text-center italic mt-4">No active regulations found.</p>
                <?php else: ?>
                    <?php foreach($regulasi as $reg): ?>
                        <div class="bg-slate-700/50 p-3 rounded-xl border border-slate-600">
                            <span class="text-[10px] font-bold bg-blue-900/50 text-blue-300 px-2 py-0.5 rounded uppercase tracking-wider mb-2 inline-block">
                                <?= htmlspecialchars($reg['indikator_terkait']) ?>
                            </span>
                            <h4 class="text-sm font-semibold text-slate-200"><?= htmlspecialchars($reg['kode_regulasi']) ?></h4>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2"><?= htmlspecialchars($reg['judul']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Drafting Form -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <input type="hidden" name="id_perusahaan" value="<?= $perusahaan['id_perusahaan'] ?>">
                <input type="hidden" name="id_laporan" value="<?= $row['id_laporan'] ?>">
                <input type="hidden" name="id_analisis" value="<?= $row['id_analisis'] ?>">
                <input type="hidden" name="tahun" value="<?= $tahun ?>">
                <!-- Hidden input for status -->
                <input type="hidden" name="status" id="formStatus" value="draft">

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Report Title <span class="text-red-500">*</span></label>
                        <input type="text" id="reportTitle" name="judul" placeholder="e.g. Tax Risk Assessment <?= $tahun ?>" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all px-4 py-3 bg-gray-50 text-gray-800">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Analysis Type <span class="text-red-500">*</span></label>
                        <select id="analysisType" name="jenis_analisis" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all px-4 py-3 bg-gray-50 text-gray-800">
                            <option value="tax_indicator">Tax Indicator Analysis</option>
                            <option value="transfer_pricing">Transfer Pricing Risk</option>
                            <option value="compliance">General Tax Compliance</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Analyst Insights & Findings <span class="text-red-500">*</span></label>
                        <textarea id="analystInsight" name="analyst_insight" rows="8" placeholder="Write your detailed observations and insights based on the indicators..." class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all px-4 py-3 bg-gray-50 text-gray-800"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Recommendations / Action Plan <span class="text-red-500">*</span></label>
                        <textarea id="recommendation" name="recommendation" rows="5" placeholder="What actions should the client take?" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all px-4 py-3 bg-gray-50 text-gray-800"></textarea>
                    </div>
                </div>

                <!-- Validation Error Message (Hidden by default) -->
                <div id="validationError" class="hidden mt-4 p-3 rounded-lg bg-red-50 text-red-600 border border-red-200 text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Analyst Insights & Recommendations wajib diisi sebelum analisis dapat dikirim.</span>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
                    <button type="button" onclick="saveDraft()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Save Draft
                    </button>
                    
                    <button type="button" onclick="submitAnalysis()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-md shadow-blue-200 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Submit Analysis
                    </button>
                </div>
        </div>
    </div>
</form>

<!-- SweetAlert2 (Assume it's loaded in template, but just in case) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const form = document.getElementById('analysisForm');
    const statusInput = document.getElementById('formStatus');
    const errorAlert = document.getElementById('validationError');

    // Fields to validate
    const title = document.getElementById('reportTitle');
    const insight = document.getElementById('analystInsight');
    const recommendation = document.getElementById('recommendation');

    function saveDraft() {
        // Save draft ignores strict validation
        statusInput.value = 'draft';
        errorAlert.classList.add('hidden');
        form.submit();
    }

    function submitAnalysis() {
        errorAlert.classList.add('hidden');

        // Check required fields for submission
        if (!title.value.trim() || !insight.value.trim() || !recommendation.value.trim()) {
            errorAlert.classList.remove('hidden');
            // Optional: highlight empty fields
            if(!title.value.trim()) title.classList.add('border-red-400', 'ring-red-200');
            if(!insight.value.trim()) insight.classList.add('border-red-400', 'ring-red-200');
            if(!recommendation.value.trim()) recommendation.classList.add('border-red-400', 'ring-red-200');
            
            // Remove highlight on input
            [title, insight, recommendation].forEach(el => {
                el.addEventListener('input', function() {
                    this.classList.remove('border-red-400', 'ring-red-200');
                }, {once: true});
            });
            
            return;
        }

        // Show SweetAlert Confirmation
        Swal.fire({
            title: 'Submit Analysis?',
            html: 'Anda akan mengirim hasil analisis <br><b><?= htmlspecialchars($perusahaan['nama_perusahaan']) ?> — <?= $tahun ?></b><br>kepada Admin untuk direview.<br><br><span class="text-xs text-red-500">Setelah dikirim, Anda tidak dapat mengubah analisis sampai Admin meminta revisi.</span>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: '<i class="fa-solid fa-paper-plane mr-1"></i> Submit Analysis',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                statusInput.value = 'submitted';
                form.submit();
            }
        });
    }
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
<?= $this->endSection() ?>
