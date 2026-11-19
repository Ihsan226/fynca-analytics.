<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Bendahara</h2>
        <p class="text-sm text-gray-500 mt-1">Rekapitulasi arus kas, beban operasional, dan persetujuan pencairan dana.</p>
    </div>
</div>

<!-- Statistik Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-2xl border border-emerald-400 shadow-lg text-white flex items-center justify-between hover:shadow-xl transition-shadow">
        <div>
            <p class="text-xs font-bold text-emerald-100 uppercase tracking-wider mb-2">SALDO KAS INTERNAL</p>
            <h4 class="text-3xl font-bold">Rp 450M+</h4>
        </div>
        <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center text-3xl">
            <i class="fa-solid fa-vault"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">MENUNGGU APPROVAL (PAYROLL)</p>
            <h4 class="text-2xl font-bold text-gray-800">1 <span class="text-sm font-normal text-gray-500">Batch</span></h4>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
            <i class="fa-solid fa-file-signature"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">HUTANG PAJAK (PPH)</p>
            <h4 class="text-2xl font-bold text-gray-800">Rp 12.5M</h4>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-xl">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Pusat Persetujuan (Disbursement Center)</h3>
            <p class="text-[10px] text-gray-500">Hanya menampilkan rekap data yang sudah divalidasi oleh Akuntansi.</p>
        </div>
    </div>
    <div class="p-0">
        <ul class="divide-y divide-gray-100">
            <li class="p-5 hover:bg-gray-50 transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Payroll & PPh 21 Karyawan</h4>
                        <p class="text-xs text-gray-500">Periode: Bulan Ini</p>
                    </div>
                </div>
                <a href="/finance/recap-payroll" class="px-4 py-2 text-sm font-bold text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    Review & Approve
                </a>
            </li>
            <li class="p-5 hover:bg-gray-50 transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Klaim Reimbursement</h4>
                        <p class="text-xs text-gray-500">3 Menunggu Persetujuan</p>
                    </div>
                </div>
                <a href="/finance/claims" class="px-4 py-2 text-sm font-bold text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                    Lihat Klaim
                </a>
            </li>
        </ul>
    </div>
</div>

<?= $this->endSection(); ?>
