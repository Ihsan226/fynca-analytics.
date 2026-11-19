<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Persetujuan Pencairan Payroll</h2>
        <p class="text-sm text-gray-500 mt-1">Rekapitulasi beban gaji dan PPh 21 hasil kalkulasi Akuntansi.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="/finance/dashboard" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
    <i class="fa-solid fa-check-circle"></i>
    <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Card Info -->
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 p-6 opacity-20 text-6xl">
            <i class="fa-solid fa-money-check-dollar"></i>
        </div>
        <h3 class="text-blue-100 font-medium text-sm mb-1 uppercase tracking-widest">TOTAL BEBAN GAJI (BRUTO)</h3>
        <h2 class="text-4xl font-bold mb-4">Rp <?= number_format($total_beban, 0, ',', '.') ?></h2>
        
        <div class="border-t border-blue-500/30 pt-4 mt-2">
            <h3 class="text-blue-100 font-medium text-xs mb-1 uppercase tracking-widest">ESTIMASI POTONGAN PPH 21</h3>
            <h4 class="text-xl font-bold text-red-200">- Rp <?= number_format($total_pph21, 0, ',', '.') ?></h4>
        </div>
    </div>
    
    <!-- Action Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <h3 class="text-gray-800 font-bold mb-2">Status: 
            <?php if($status_batch == 'approved'): ?>
                <span class="text-amber-500">Menunggu Persetujuan Anda</span>
            <?php elseif($status_batch == 'paid'): ?>
                <span class="text-emerald-500">Telah Disetujui (Paid)</span>
            <?php else: ?>
                <span class="text-gray-500">Belum Dikirim oleh Akuntansi</span>
            <?php endif; ?>
        </h3>
        <p class="text-sm text-gray-500 mb-6">Pastikan saldo kas internal mencukupi sebelum menyetujui pencairan dana ini.</p>
        
        <?php if($status_batch == 'approved'): ?>
            <form action="/finance/approve-disbursement/<?= esc($bulan) ?>/<?= esc($tahun) ?>" method="post" enctype="multipart/form-data" class="mt-auto">
                <?= csrf_field() ?>
                <label for="payment_proof" class="block text-xs font-medium text-gray-600 mb-2">Bukti pembayaran (opsional)</label>
                <input id="payment_proof" name="payment_proof" type="file" accept=".jpg,.jpeg,.png,.pdf" class="w-full mb-3 text-xs text-gray-600">
                <button type="button" onclick="Swal.fire({
                    title: 'Setujui Pencairan?',
                    text: 'Setelah disetujui, dana akan dialokasikan dan slip gaji dapat diakses karyawan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Approve & Pay!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                })" class="w-full px-5 py-3 text-sm font-bold text-white bg-emerald-500 rounded-xl hover:bg-emerald-600 transition-colors shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check-double"></i> Approve & Pay (Disbursement)
                </button>
            </form>
        <?php elseif($status_batch == 'paid'): ?>
            <div class="w-full px-5 py-3 text-sm font-bold text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-center gap-2 mt-auto">
                <i class="fa-solid fa-circle-check"></i> Dana Telah Dicairkan
            </div>
        <?php else: ?>
            <div class="w-full px-5 py-3 text-sm font-bold text-gray-400 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-center gap-2 mt-auto cursor-not-allowed">
                <i class="fa-solid fa-lock"></i> Menunggu Data Akuntansi
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Catatan Validasi</h3>
            <p class="text-[10px] text-gray-500">Anda tidak dapat mengubah data komponen gaji. Hubungi Akuntansi jika ada revisi.</p>
        </div>
    </div>
    <div class="p-8 text-center bg-gray-50/30">
        <div class="w-16 h-16 bg-white border border-gray-200 rounded-full flex items-center justify-center mx-auto mb-3 text-emerald-400 shadow-sm">
            <i class="fa-solid fa-shield-check text-2xl"></i>
        </div>
        <h4 class="text-sm font-bold text-gray-700 mb-1">Data Telah Diverifikasi Akuntansi</h4>
        <p class="text-xs text-gray-500">Form input manual telah dinonaktifkan untuk menjaga Segregation of Duties (Pemisahan Tugas).</p>
    </div>
</div>

<?= $this->endSection(); ?>
