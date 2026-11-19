<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Financial Reports</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola data laporan keuangan dan status analisis klien.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="/dashboard" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <?php if(in_array(session('role'), ['admin', 'staff'])): ?>
        <a href="/import" class="px-4 py-2 text-sm font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm hover:bg-emerald-100 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-file-excel"></i> Import Excel
        </a>
        <a href="/keuangan/import" class="px-4 py-2 text-sm font-medium text-white bg-[#3b5de7] rounded-lg shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Input Laporan Baru
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Statistik Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl"><i class="fa-solid fa-file-invoice-dollar"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">TOTAL LAPORAN</p>
            <h4 class="text-xl font-bold text-gray-800"><?= count($laporan) ?> Data</h4>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl"><i class="fa-regular fa-calendar-check"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">PERIODE</p>
            <h4 class="text-xl font-bold text-gray-800">Tahunan</h4>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-xl"><i class="fa-solid fa-calculator"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">STATUS</p>
            <h4 class="text-xl font-bold text-gray-800">Tracking</h4>
        </div>
    </div>
</div>

<!-- Area Tabel -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Filter Bar yang Rapi -->
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Daftar Pekerjaan Analisis</h3>
            <p class="text-[10px] text-gray-500">Gunakan filter untuk mencari data klien</p>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <input type="text" placeholder="Cari nama klien..." class="text-sm border border-gray-300 rounded-lg px-3 py-2 w-full md:w-64 focus:ring-2 focus:ring-blue-100 outline-none">
            <button class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50"><i class="fa-solid fa-rotate-right"></i></button>
            <button class="px-3 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-400 uppercase tracking-wider">
                <tr>
                    <th class="p-4 font-bold w-16">No</th>
                    <th class="p-4 font-bold">Nama Klien</th>
                    <th class="p-4 font-bold text-center">Tahun Fiskal</th>
                    <?php if(session('role') === 'admin'): ?>
                    <th class="p-4 font-bold">Prepared By</th>
                    <?php endif; ?>
                    <th class="p-4 font-bold text-center">Status</th>
                    <th class="p-4 font-bold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                <?php if(empty($laporan)): ?>
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center mb-2 text-gray-300">
                                <i class="fa-solid fa-inbox text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-xs">Belum ada laporan yang diinput.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no=1; foreach($laporan as $row): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 text-gray-400"><?= $no++ ?></td>
                        <td class="p-4 font-bold text-blue-600"><?= $row['nama_perusahaan'] ?></td>
                        <td class="p-4 text-center font-medium"><?= $row['tahun'] ?></td>
                        <?php if(session('role') === 'admin'): ?>
                        <td class="p-4 text-xs font-semibold text-gray-600">
                            <?= esc($row['prepared_by_name'] ?? '-') ?>
                        </td>
                        <?php endif; ?>
                        <td class="py-2.5 px-3 text-center">
                                <?php 
                                    // Merged Status Logic in Dashboard
                                    $status = $row['status'] ?? null;
                                    
                                    if (!$status) {
                                        $statusClass = 'bg-amber-100 text-amber-700 border-amber-200';
                                        $icon = 'fa-play';
                                        $label = 'READY FOR ANALYSIS';
                                    } elseif ($status == 'Draft') {
                                        $statusClass = 'bg-slate-100 text-slate-700 border-slate-200';
                                        $icon = 'fa-pen-ruler';
                                        $label = 'DRAFT';
                                    } elseif ($status == 'Processing') {
                                        $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                        $icon = 'fa-spinner fa-spin';
                                        $label = 'DIPROSES';
                                    } elseif ($status == 'Analysis Completed') {
                                        $statusClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                        $icon = 'fa-magnifying-glass-chart';
                                        $label = 'UNDER REVIEW';
                                    } elseif ($status == 'Approved') {
                                        $statusClass = 'bg-green-50 text-green-700 border-green-200';
                                        $icon = 'fa-check-double';
                                        $label = 'COMPLETED';
                                    } elseif ($status == 'Revision Required') {
                                        $statusClass = 'bg-red-50 text-red-700 border-red-200';
                                        $icon = 'fa-triangle-exclamation';
                                        $label = 'REVISION REQUIRED';
                                    } else {
                                        $statusClass = 'bg-gray-100 text-gray-700 border-gray-200';
                                        $icon = 'fa-file';
                                        $label = strtoupper($status);
                                    }
                                ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-bold border <?= $statusClass ?>" <?php if($status == 'Processing'): ?>title="Sedang diproses oleh Analyst & Akuntansi"<?php endif; ?>>
                                    <i class="fa-solid <?= $icon ?>"></i> <?= $label ?>
                                </span>
                                <?php if($status == 'Processing'): ?>
                                    <div class="mt-2 text-left bg-gray-50 p-2 rounded-lg border border-gray-100 shadow-sm w-full">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[9px] font-semibold text-gray-500">Analyst:</span>
                                            <span class="text-[9px] font-bold <?= $row['analyst_status'] == 'completed' ? 'text-green-500' : 'text-amber-500' ?>"><?= strtoupper($row['analyst_status'] ?? 'PENDING') ?></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-[9px] font-semibold text-gray-500">Akuntansi:</span>
                                            <span class="text-[9px] font-bold <?= $row['accounting_status'] == 'completed' || $row['accounting_status'] == 'submitted_to_treasurer' ? 'text-green-500' : 'text-amber-500' ?>"><?= strtoupper($row['accounting_status'] ?? 'PENDING') ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                            <?php if(session('role') === 'analyst'): ?>
                                <a href="/workspace/analyze/<?= $row['id_perusahaan'] ?>/<?= $row['tahun'] ?>" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-200 flex items-center justify-center hover:bg-indigo-100 hover:text-indigo-700 transition-colors" title="Buka Workspace">
                                    <i class="fa-solid fa-folder-open"></i>
                                </a>
                            <?php elseif(session('role') === 'owner'): ?>
                                <button type="button" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center hover:bg-emerald-100 transition-colors" title="Lihat Laporan" onclick="Swal.fire({icon:'info', title:'Data Historis', text:'Data mentah ini bersifat read-only. Silakan lihat insight lengkap di halaman Tax Reports atau Dashboard.', confirmButtonColor: '#2563eb'})">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            <?php else: ?>
                                <a href="/keuangan/edit/<?= $row['id_laporan'] ?>" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 border border-gray-200 flex items-center justify-center hover:bg-amber-50 hover:text-amber-600 transition-colors" title="Edit Data Laporan">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a href="/keuangan/delete/<?= $row['id_laporan'] ?>" class="btn-delete w-8 h-8 rounded-lg bg-gray-50 text-gray-500 border border-gray-200 flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-colors" title="Hapus Laporan">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                                <?php if(!in_array($status, ['Sent to Analyst', 'Processing', 'Analysis Completed', 'Revision Required', 'Approved'])): ?>
                                <button type="button" 
                                    class="btn-send-analyst w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 border border-indigo-200 flex items-center justify-center hover:bg-indigo-100 hover:text-indigo-700 transition-colors" 
                                    title="Kirim ke Analyst"
                                    data-id="<?= $row['id_laporan'] ?>"
                                    data-client="<?= esc($row['nama_perusahaan']) ?>"
                                    data-year="<?= $row['tahun'] ?>">
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                </button>
                                <?php endif; ?>
                            <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL: KONFIRMASI KIRIM KE ANALYST            -->
<!-- ============================================= -->
<div id="modalSendAnalyst" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 invisible transition-all duration-300" style="pointer-events:none;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 transform scale-95 transition-transform duration-300" id="modalSendContent">
        <!-- Header -->
        <div class="flex items-center gap-3 p-5 border-b border-gray-100 bg-gray-50/60 rounded-t-2xl">
            <div class="w-11 h-11 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
            <div>
                <h3 class="text-[1.05rem] font-bold text-gray-800">Kirim Laporan</h3>
                <p class="text-xs text-gray-500 mt-0.5">Konfirmasi pengiriman data</p>
            </div>
            <button type="button" class="ml-auto w-9 h-9 rounded-lg bg-gray-100 hover:bg-red-50 text-gray-400 hover:text-red-500 flex items-center justify-center transition-colors" onclick="closeSendModal()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <!-- Body -->
        <div class="p-5">
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-4">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-indigo-400 mt-0.5"></i>
                    <div class="text-sm text-indigo-700 leading-relaxed">
                        Laporan keuangan ini akan dikirimkan secara otomatis ke bagian <strong>Analyst</strong> (untuk analisis pajak) dan <strong>Akuntansi</strong> (untuk pembentukan jurnal).
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Klien</span>
                    <span class="font-bold text-gray-800" id="sendClientName">—</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Tahun Fiskal</span>
                    <span class="font-bold text-gray-800" id="sendYear">—</span>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3 flex items-center gap-1.5">
                <i class="fa-solid fa-triangle-exclamation text-amber-400"></i>
                Setelah dikirim, status tidak bisa dikembalikan ke semula.
            </p>
        </div>
        <!-- Footer -->
        <div class="flex justify-end gap-2 p-4 border-t border-gray-100 bg-gray-50/40 rounded-b-2xl">
            <button type="button" class="px-4 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors" onclick="closeSendModal()">
                Batal
            </button>
            <a href="#" id="confirmSendLink" class="px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-paper-plane"></i> Kirim Sekarang
            </a>
        </div>
    </div>
</div>

<script>
function openSendModal(id, client, year) {
    document.getElementById('sendClientName').textContent = client;
    document.getElementById('sendYear').textContent = year;
    document.getElementById('confirmSendLink').href = '/keuangan/send-to-processing/' + id;
    
    var modal = document.getElementById('modalSendAnalyst');
    var content = document.getElementById('modalSendContent');
    modal.style.pointerEvents = 'auto';
    modal.classList.remove('opacity-0', 'invisible');
    modal.classList.add('opacity-100', 'visible');
    content.classList.remove('scale-95');
    content.classList.add('scale-100');
    document.body.style.overflow = 'hidden';
}

function closeSendModal() {
    var modal = document.getElementById('modalSendAnalyst');
    var content = document.getElementById('modalSendContent');
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    modal.classList.remove('opacity-100', 'visible');
    modal.classList.add('opacity-0', 'invisible');
    modal.style.pointerEvents = 'none';
    document.body.style.overflow = '';
}

// Close on overlay click
document.getElementById('modalSendAnalyst').addEventListener('click', function(e) {
    if (e.target === this) closeSendModal();
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSendModal();
});

// Attach click handlers to all send buttons
document.querySelectorAll('.btn-send-analyst').forEach(function(btn) {
    btn.addEventListener('click', function() {
        openSendModal(
            this.getAttribute('data-id'),
            this.getAttribute('data-client'),
            this.getAttribute('data-year')
        );
    });
});
</script>

<?= $this->endSection(); ?>