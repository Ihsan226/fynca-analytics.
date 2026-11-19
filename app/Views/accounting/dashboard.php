<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Akuntansi</h2>
        <p class="text-sm text-gray-500 mt-1">Mesin hitung pemrosesan keuangan, jurnal, dan perpajakan.</p>
    </div>
</div>

<!-- Statistik Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl"><i class="fa-solid fa-file-invoice"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">PENDING JURNAL</p>
            <h4 class="text-xl font-bold text-gray-800">5 <span class="text-sm font-normal text-gray-500">Tugas</span></h4>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl"><i class="fa-solid fa-money-check-dollar"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">PAYROLL & PPH 21</p>
            <h4 class="text-xl font-bold text-gray-800">Pending</h4>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">LAPORAN KEUANGAN</p>
            <h4 class="text-xl font-bold text-gray-800">Ready</h4>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl"><i class="fa-solid fa-receipt"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">PAJAK (PPH 22/23)</p>
            <h4 class="text-xl font-bold text-gray-800">2 <span class="text-sm font-normal text-gray-500">Tugas</span></h4>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Tugas & Antrean Pemrosesan (Dari Staff)</h3>
            <p class="text-[10px] text-gray-500">Data mentah yang siap untuk dibuatkan jurnal dan kalkulasi laporan</p>
        </div>
        <a href="/accounting/process-financial" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-gears"></i> Buka Mesin Proses
        </a>
    </div>
    <div class="p-8 text-center">
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
            <i class="fa-solid fa-inbox text-2xl"></i>
        </div>
        <h4 class="text-sm font-bold text-gray-700 mb-1">Belum ada antrean data dari Staff</h4>
        <p class="text-xs text-gray-500">Saat Staff mengirimkan data keuangan baru, data akan muncul di sini untuk Anda proses menjadi Jurnal dan Buku Besar.</p>
    </div>
</div>

<?= $this->endSection(); ?>
