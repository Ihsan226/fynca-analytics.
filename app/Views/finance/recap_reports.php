<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Rekap Laporan & Pajak</h1>
        <p>Laporan rekapan keuangan dan kewajiban pajak (Read-Only).</p>
    </div>
</div>

<div class="card p-6 border-0 shadow-lg shadow-purple-900/10 rounded-2xl bg-white/5 backdrop-blur-md">
    <div class="card-body">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-500 flex items-center justify-center">
                <i class="fa-solid fa-lock text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Data Terkunci (Locked)</h3>
                <p class="text-sm text-gray-500">Bendahara hanya dapat melihat data final yang telah dikunci oleh tim Akuntansi.</p>
            </div>
        </div>
        
        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
            <h4 class="font-bold text-gray-800 mb-2">Sinkronisasi Jurnal Akuntansi</h4>
            <p class="text-sm text-gray-600 mb-4">Data P&L dan Pajak Transaksional sedang dalam proses sinkronisasi dari modul Akuntansi. Bendahara akan menerima notifikasi otomatis ketika rekap final siap untuk dibayarkan.</p>
            <a href="/finance/recap-payroll" class="btn bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors inline-block">Cek Persetujuan Payroll</a>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
