<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mx-auto px-4 py-8">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Input Laporan Keuangan</h1>
        <p class="text-slate-500 mt-2">Pilih metode input laporan keuangan perusahaan untuk analisis.</p>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-triangle-exclamation text-red-500 mr-3"></i>
                <p class="text-red-700"><?= session()->getFlashdata('error'); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tabs Header -->
    <div class="flex border-b border-slate-200 mb-6">
        <button id="btn-tab-import" class="py-3 px-6 text-indigo-600 font-semibold border-b-2 border-indigo-600 focus:outline-none flex items-center gap-2" onclick="switchTab('import')">
            <i class="fa-solid fa-cloud-arrow-up"></i> Import Dokumen
        </button>
        <button id="btn-tab-manual" class="py-3 px-6 text-slate-500 font-medium hover:text-slate-700 focus:outline-none transition-colors flex items-center gap-2" onclick="switchTab('manual')">
            <i class="fa-solid fa-keyboard"></i> Input Manual
        </button>
    </div>

    <!-- TAB 1: IMPORT -->
    <div id="tab-import" class="block">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8">
                <form action="/keuangan/extract-data" method="POST" enctype="multipart/form-data" id="form-import">
                    <?= csrf_field() ?>

                    <!-- Document Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Perusahaan</label>
                            <select name="id_perusahaan" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                <?php foreach ($perusahaan as $p) : ?>
                                    <option value="<?= $p['id_perusahaan']; ?>"><?= esc($p['nama_perusahaan']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Tahun Laporan</label>
                            <select name="tahun" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <?php 
                                $currentYear = date('Y');
                                for($i = $currentYear; $i >= $currentYear - 10; $i--): ?>
                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Bulan Laporan</label>
                            <select name="bulan" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Bulan --</option>
                                <?php 
                                $bulans = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                                foreach($bulans as $num => $nama): ?>
                                    <option value="<?= $num; ?>"><?= $nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Drag and Drop Area -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Upload File Dokumen</label>
                        <div class="mt-1 flex justify-center px-6 pt-10 pb-12 border-2 border-slate-300 border-dashed rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition-colors relative cursor-pointer" id="drop-zone">
                            <div class="space-y-1 text-center">
                                <i class="fa-solid fa-file-invoice-dollar text-4xl text-slate-400 mb-3" id="upload-icon"></i>
                                <div class="flex text-sm text-slate-600 justify-center">
                                    <span class="relative rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                        <span>Pilih File</span>
                                        <input id="file-upload" name="laporan_file" type="file" class="sr-only" accept=".pdf,.xls,.xlsx,.csv,.xbrl" required>
                                    </span>
                                    <p class="pl-1">atau Drag & Drop di sini</p>
                                </div>
                                <p class="text-xs text-slate-500">PDF, XLSX, CSV, XBRL (Maks. 20MB)</p>
                                <p id="file-name-display" class="text-sm font-semibold text-indigo-700 mt-4 hidden"></p>
                            </div>
                            <!-- Full area click overlay -->
                            <div class="absolute inset-0 w-full h-full" onclick="document.getElementById('file-upload').click()"></div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" id="btn-submit-import" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors flex items-center shadow-sm">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Mulai Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 2: MANUAL -->
    <div id="tab-manual" class="hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8">
                <form action="/keuangan/store" method="POST">
                    <?= csrf_field() ?>

                    <!-- Document Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Perusahaan</label>
                            <select name="id_perusahaan" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Perusahaan --</option>
                                <?php foreach ($perusahaan as $p) : ?>
                                    <option value="<?= $p['id_perusahaan']; ?>"><?= esc($p['nama_perusahaan']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Tahun Laporan</label>
                            <select name="tahun" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <?php 
                                $currentYear = date('Y');
                                for($i = $currentYear; $i >= $currentYear - 10; $i--): ?>
                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Bulan Laporan</label>
                            <select name="bulan" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Bulan --</option>
                                <?php 
                                $bulans = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                                foreach($bulans as $num => $nama): ?>
                                    <option value="<?= $num; ?>"><?= $nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Input Fields Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        
                        <!-- ETR Section -->
                        <div class="border border-slate-200 rounded-xl p-5 relative">
                            <div class="absolute -top-3 left-4 bg-white px-2 text-xs font-bold text-rose-600 flex items-center gap-1"><i class="fa-solid fa-percent"></i> ETR (Tax Aggressiveness)</div>
                            <div class="space-y-4 mt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Laba Sebelum Pajak</label>
                                    <input type="number" name="profit_before_tax" required placeholder="0" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Beban Pajak</label>
                                    <input type="number" name="tax_expense" required placeholder="0" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- RPT Section -->
                        <div class="border border-slate-200 rounded-xl p-5 relative">
                            <div class="absolute -top-3 left-4 bg-white px-2 text-xs font-bold text-blue-600 flex items-center gap-1"><i class="fa-solid fa-money-bill-transfer"></i> RPT Ratio</div>
                            <div class="space-y-4 mt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Piutang Pihak Berelasi</label>
                                    <input type="number" name="related_party_receivables" required placeholder="0" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Piutang</label>
                                    <input type="number" name="total_receivables" required placeholder="0" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- DER Section -->
                        <div class="border border-slate-200 rounded-xl p-5 relative">
                            <div class="absolute -top-3 left-4 bg-white px-2 text-xs font-bold text-amber-600 flex items-center gap-1"><i class="fa-solid fa-building-columns"></i> DER (Leverage)</div>
                            <div class="space-y-4 mt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Hutang (Liabilitas)</label>
                                    <input type="number" name="total_liabilities" required placeholder="0" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Total Ekuitas</label>
                                    <input type="number" name="total_equity" required placeholder="0" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Sales Growth Section -->
                        <div class="border border-slate-200 rounded-xl p-5 relative">
                            <div class="absolute -top-3 left-4 bg-white px-2 text-xs font-bold text-emerald-600 flex items-center gap-1"><i class="fa-solid fa-chart-line"></i> Sales Growth</div>
                            <div class="space-y-4 mt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Penjualan Tahun Ini</label>
                                    <input type="number" name="revenue_current" required placeholder="0" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Penjualan Tahun Lalu</label>
                                    <input type="number" name="revenue_previous" required placeholder="0" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-6 flex items-start gap-3">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                        <p class="text-sm text-blue-800">Setelah disimpan, sistem akan <strong>otomatis menghitung</strong> rasio ETR, RPT Ratio, DER, dan Sales Growth dari data di atas secara instan.</p>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors flex items-center shadow-sm">
                            <i class="fa-solid fa-calculator mr-2"></i> Simpan & Kalkulasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // Tab Switching Logic
    function switchTab(tab) {
        const tabImport = document.getElementById('tab-import');
        const tabManual = document.getElementById('tab-manual');
        const btnImport = document.getElementById('btn-tab-import');
        const btnManual = document.getElementById('btn-tab-manual');

        if(tab === 'import') {
            tabImport.classList.remove('hidden');
            tabImport.classList.add('block');
            tabManual.classList.add('hidden');
            tabManual.classList.remove('block');
            
            btnImport.className = "py-3 px-6 text-indigo-600 font-semibold border-b-2 border-indigo-600 focus:outline-none flex items-center gap-2";
            btnManual.className = "py-3 px-6 text-slate-500 font-medium hover:text-slate-700 focus:outline-none transition-colors flex items-center gap-2";
        } else {
            tabManual.classList.remove('hidden');
            tabManual.classList.add('block');
            tabImport.classList.add('hidden');
            tabImport.classList.remove('block');
            
            btnManual.className = "py-3 px-6 text-indigo-600 font-semibold border-b-2 border-indigo-600 focus:outline-none flex items-center gap-2";
            btnImport.className = "py-3 px-6 text-slate-500 font-medium hover:text-slate-700 focus:outline-none transition-colors flex items-center gap-2";
        }
    }

    // Drag and Drop Logic
    const fileUpload = document.getElementById('file-upload');
    const fileNameDisplay = document.getElementById('file-name-display');
    const dropZone = document.getElementById('drop-zone');
    const btnSubmitImport = document.getElementById('btn-submit-import');
    const uploadIcon = document.getElementById('upload-icon');

    fileUpload.addEventListener('change', function() {
        if(this.files && this.files[0]) {
            showFile(this.files[0].name);
        }
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            fileUpload.files = e.dataTransfer.files;
            showFile(e.dataTransfer.files[0].name);
        }
    });

    function showFile(name) {
        fileNameDisplay.textContent = "Terpilih: " + name;
        fileNameDisplay.classList.remove('hidden');
        uploadIcon.classList.remove('fa-file-invoice-dollar', 'text-slate-400');
        uploadIcon.classList.add('fa-file-circle-check', 'text-indigo-500');
    }

    // Loading State
    document.getElementById('form-import').addEventListener('submit', function() {
        if(fileUpload.files.length > 0) {
            btnSubmitImport.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Sedang Memproses...';
            btnSubmitImport.classList.add('opacity-75', 'cursor-not-allowed');
        }
    });
</script>
<?= $this->endSection(); ?>
