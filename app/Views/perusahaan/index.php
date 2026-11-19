<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Daftar Klien</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola data perusahaan klien yang terdaftar di sistem.</p>
    </div>
    <div class="flex items-center gap-2">
        <?php if(session('role') !== 'analyst'): ?>
        <a href="/perusahaan/create" class="px-4 py-2 text-sm font-medium text-white bg-[#3b5de7] rounded-lg shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Klien
        </a>
        <?php endif; ?>
        <button class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">PDF</button>
        <button class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">Excel</button>
    </div>
</div>

<!-- 4 Kartu Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl"><i class="fa-solid fa-list-ul"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">TOTAL KLIEN</p>
            <h4 class="text-xl font-bold text-gray-800"><?= count($perusahaan) ?></h4>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl"><i class="fa-solid fa-layer-group"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">SEKTOR UTAMA</p>
            <h4 class="text-xl font-bold text-gray-800">Properti</h4>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl"><i class="fa-solid fa-chart-line"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">STATUS BURSA</p>
            <h4 class="text-xl font-bold text-gray-800">BEI</h4>
        </div>
    </div>
    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl"><i class="fa-solid fa-check-double"></i></div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">KONDISI DATA</p>
            <h4 class="text-xl font-bold text-gray-800">Valid</h4>
        </div>
    </div>
</div>

<!-- Tabel Klien -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 text-sm">Daftar Klien</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="p-4 font-bold w-16 text-center">No</th>
                    <th class="p-4 font-bold w-32">Kode</th>
                    <th class="p-4 font-bold">Nama Perusahaan</th>
                    <th class="p-4 font-bold w-32 text-center">Tahun IPO</th>
                    <th class="p-4 font-bold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                <?php if(empty($perusahaan)): ?>
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                <i class="fa-solid fa-inbox text-3xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Belum ada data klien yang ditambahkan.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no=1; foreach($perusahaan as $p): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 text-center text-gray-400"><?= $no++ ?></td>
                        <td class="p-4 font-bold text-blue-600"><?= $p['kode_saham'] ?></td>
                        <td class="p-4 font-medium"><?= $p['nama_perusahaan'] ?></td>
                        <td class="p-4 text-center"><?= $p['tahun_ipo'] ?: '-' ?></td>
                        <td class="p-4 flex justify-center gap-2">
                            <?php if(session('role') !== 'analyst'): ?>
                            <a href="/perusahaan/edit/<?= $p['id_perusahaan'] ?>" class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 border border-gray-200 flex items-center justify-center hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="/perusahaan/delete/<?= $p['id_perusahaan'] ?>" class="btn-delete w-8 h-8 rounded-lg bg-gray-50 text-gray-500 border border-gray-200 flex items-center justify-center hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                            <?php else: ?>
                            <span class="text-xs text-slate-400 italic">View Only</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>