<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Import Data Keuangan</h2>
    <p class="text-sm text-gray-500 mt-1">Unggah file Excel untuk memasukkan data laporan keuangan klien sekaligus.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 md:p-8">
        
        <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 border border-emerald-100 flex items-center gap-3">
            <i class="fa-solid fa-circle-check"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-1">
                <form action="/import/process" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Klien <span class="text-red-500">*</span></label>
                        <select name="id_perusahaan" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 outline-none transition-all" required>
                            <option value="">-- Pilih Klien / Perusahaan --</option>
                            <?php foreach($perusahaan as $p): ?>
                                <option value="<?= $p['id_perusahaan'] ?>"><?= $p['nama_perusahaan'] ?> (<?= $p['kode_saham'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload File Excel (.xlsx) <span class="text-red-500">*</span></label>
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fa-solid fa-file-excel text-4xl text-emerald-500 mb-3"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file_excel" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 px-2 py-1 border border-gray-200">
                                        <span>Pilih file</span>
                                        <input id="file_excel" name="file_excel" type="file" class="sr-only" accept=".xlsx" required>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Hanya file .xlsx yang didukung
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <a href="/keuangan" class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">Batal</a>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-[#3b5de7] rounded-lg shadow-sm hover:bg-blue-700 transition-colors">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Proses Import
                        </button>
                    </div>

                </form>
            </div>

            <!-- Petunjuk Template -->
            <div class="md:w-1/3 bg-blue-50/50 border border-blue-100 rounded-xl p-6">
                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-500"></i> Panduan Import
                </h3>
                <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                    Untuk menghindari error, pastikan format kolom pada file Excel Anda sesuai dengan template standar sistem. Anda dapat mengunduh template kosong di bawah ini.
                </p>
                
                <a href="/import/download-template" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 rounded-lg hover:bg-emerald-200 transition-colors">
                    <i class="fa-solid fa-download"></i> Download Template Excel
                </a>

                <div class="mt-6 pt-6 border-t border-blue-100">
                    <p class="text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">Struktur Kolom Wajib:</p>
                    <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                        <li>Tahun</li>
                        <li>Laba Sebelum Pajak</li>
                        <li>Beban Pajak</li>
                        <li>Piutang Berelasi</li>
                        <li>Total Piutang</li>
                        <li>Total Hutang</li>
                        <li>Total Ekuitas</li>
                        <li>Penjualan Tahun Ini</li>
                        <li>Penjualan Tahun Lalu</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>
