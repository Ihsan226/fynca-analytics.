<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fa-solid fa-pen-to-square text-blue-600 mr-2"></i> Edit Report: <?= htmlspecialchars($perusahaan['nama_perusahaan']) ?> (<?= $report['tahun'] ?>)
        </h1>
        <p class="text-gray-500 mt-2">Update your analysis draft or submit it for review</p>
    </div>
    <a href="<?= base_url('my-analyses') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl transition-all font-medium text-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to My Analyses
    </a>
</div>

<?php if ($report['status'] === 'revision'): ?>
    <div class="alert-message p-5 mb-6 bg-red-50 rounded-xl border border-red-200 shadow-sm">
        <div class="flex items-center gap-2 text-red-800 font-bold mb-2">
            <i class="fa-solid fa-circle-exclamation"></i> Revision Requested
        </div>
        <p class="text-sm text-red-700 bg-white/50 p-3 rounded-lg border border-red-100 italic">
            "<?= htmlspecialchars($report['reviewer_comment'] ?? 'Please review and revise the report.') ?>"
        </p>
    </div>
<?php endif; ?>

<form id="analysisForm" action="<?= base_url('workspace/update/'.$report['id']) ?>" method="POST" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Left Column: Indicators -->
    <div class="xl:col-span-1 space-y-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-chart-pie text-blue-500 mr-2"></i> Financial Indicators</h3>
            
            <div class="space-y-4">
                <!-- ETR -->
                <div class="p-4 rounded-xl border <?= $explanations['etr']['level'] === 'high' ? 'bg-red-50 border-red-100' : ($explanations['etr']['level'] === 'low' ? 'bg-green-50 border-green-100' : 'bg-yellow-50 border-yellow-100') ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">Effective Tax Rate (ETR)</span>
                        <span class="text-lg font-black text-gray-800"><?= number_format($row['val_etr'] * 100, 2) ?>%</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 mb-3"><?= htmlspecialchars($explanations['etr']['explanation']) ?></p>
                    <textarea name="note_etr" rows="2" placeholder="Catatan Analis untuk ETR (opsional)..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 shadow-sm"><?= htmlspecialchars($report['note_etr'] ?? '') ?></textarea>
                </div>
                
                <!-- RPT -->
                <div class="p-4 rounded-xl border <?= $explanations['rpt']['level'] === 'high' ? 'bg-red-50 border-red-100' : ($explanations['rpt']['level'] === 'low' ? 'bg-green-50 border-green-100' : 'bg-yellow-50 border-yellow-100') ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">RPT Ratio</span>
                        <span class="text-lg font-black text-gray-800"><?= number_format($row['val_rpt_ratio'] * 100, 2) ?>%</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 mb-3"><?= htmlspecialchars($explanations['rpt']['explanation']) ?></p>
                    <textarea name="note_rpt" rows="2" placeholder="Catatan Analis untuk RPT Ratio (opsional)..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 shadow-sm"><?= htmlspecialchars($report['note_rpt'] ?? '') ?></textarea>
                </div>

                <!-- DER -->
                <div class="p-4 rounded-xl border <?= $explanations['der']['level'] === 'high' ? 'bg-red-50 border-red-100' : ($explanations['der']['level'] === 'low' ? 'bg-green-50 border-green-100' : 'bg-yellow-50 border-yellow-100') ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">Debt-to-Equity Ratio</span>
                        <span class="text-lg font-black text-gray-800"><?= number_format($row['val_der'] * 100, 2) ?>%</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 mb-3"><?= htmlspecialchars($explanations['der']['explanation']) ?></p>
                    <textarea name="note_der" rows="2" placeholder="Catatan Analis untuk DER (opsional)..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 shadow-sm"><?= htmlspecialchars($report['note_der'] ?? '') ?></textarea>
                </div>

                <!-- Sales Growth -->
                <div class="p-4 rounded-xl border <?= $explanations['growth']['level'] === 'negative' ? 'bg-red-50 border-red-100' : ($explanations['growth']['level'] === 'positive' ? 'bg-green-50 border-green-100' : 'bg-yellow-50 border-yellow-100') ?>">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase">Sales Growth</span>
                        <span class="text-lg font-black text-gray-800"><?= number_format($row['val_sales_growth'] * 100, 2) ?>%</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2 mb-3"><?= htmlspecialchars($explanations['growth']['explanation']) ?></p>
                    <textarea name="note_growth" rows="2" placeholder="Catatan Analis untuk Sales Growth (opsional)..." class="w-full text-xs rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 shadow-sm"><?= htmlspecialchars($report['note_growth'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Drafting Form -->
    <div class="xl:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <!-- Hidden input for status -->
                <input type="hidden" name="status" id="formStatus" value="<?= $report['status'] ?>">

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Report Title <span class="text-red-500">*</span></label>
                        <input type="text" id="reportTitle" name="judul" value="<?= htmlspecialchars($report['judul']) ?>" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all px-4 py-3 bg-gray-50 text-gray-800">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Analysis Type <span class="text-red-500">*</span></label>
                        <select id="analysisType" name="jenis_analisis" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all px-4 py-3 bg-gray-50 text-gray-800">
                            <option value="tax_indicator" <?= $report['jenis_analisis'] === 'tax_indicator' ? 'selected' : '' ?>>Tax Indicator Analysis</option>
                            <option value="transfer_pricing" <?= $report['jenis_analisis'] === 'transfer_pricing' ? 'selected' : '' ?>>Transfer Pricing Risk</option>
                            <option value="compliance" <?= $report['jenis_analisis'] === 'compliance' ? 'selected' : '' ?>>General Tax Compliance</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Analyst Insights & Findings <span class="text-red-500">*</span></label>
                        <textarea id="analystInsight" name="analyst_insight" rows="8" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all px-4 py-3 bg-gray-50 text-gray-800"><?= htmlspecialchars($report['analyst_insight']) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Recommendations / Action Plan <span class="text-red-500">*</span></label>
                        <textarea id="recommendation" name="recommendation" rows="5" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all px-4 py-3 bg-gray-50 text-gray-800"><?= htmlspecialchars($report['recommendation']) ?></textarea>
                    </div>
                </div>

                <!-- Validation Error Message (Hidden by default) -->
                <div id="validationError" class="hidden mt-4 p-3 rounded-lg bg-red-50 text-red-600 border border-red-200 text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Analyst Insights & Recommendations wajib diisi sebelum analisis dapat dikirim.</span>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" onclick="saveDraft()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-save"></i> <?= $report['status'] === 'revision' ? 'Save Draft (Keep as Revision)' : 'Save Draft' ?>
                    </button>
                    
                    <button type="button" onclick="submitAnalysis()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-md shadow-blue-200 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Submit Analysis
                    </button>
                </div>
        </div>
    </div>
</form>

<!-- SweetAlert2 -->
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
        statusInput.value = '<?= $report['status'] === 'revision' ? 'revision' : 'draft' ?>';
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
            html: 'Anda akan mengirim hasil analisis <br><b><?= htmlspecialchars($perusahaan['nama_perusahaan']) ?> — <?= $report['tahun'] ?></b><br>kepada Admin untuk direview.<br><br><span class="text-xs text-red-500">Setelah dikirim, Anda tidak dapat mengubah analisis sampai Admin meminta revisi.</span>',
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

<?= $this->endSection() ?>
