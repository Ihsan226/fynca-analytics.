<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- 3 Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-7">
    
    <!-- Total Klien -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #3B82F6; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Total Klien</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= number_format($total_clients) ?></h3>
            <p class="text-[11px] text-gray-400">Perusahaan terdaftar</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
            <i class="fa-solid fa-building text-blue-500 text-sm"></i>
        </div>
    </div>
    
    <!-- Total Laporan -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #10B981; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Total Laporan</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= number_format($total_reports) ?></h3>
            <p class="text-[11px] text-gray-400">Laporan dalam sistem</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
            <i class="fa-solid fa-layer-group text-emerald-500 text-sm"></i>
        </div>
    </div>
    
    <!-- Belum Dikirim -->
    <div class="bg-white rounded-[14px] border border-[#EEF0F3] p-5 flex justify-between items-start" style="border-left: 3px solid #F59E0B; box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
        <div>
            <p class="text-xs font-semibold text-gray-400 mb-2">Belum Dikirim</p>
            <h3 class="text-[30px] font-bold text-gray-800 leading-none mb-1"><?= number_format($ready_to_send) ?></h3>
            <p class="text-[11px] text-gray-400">Tersimpan sebagai draft</p>
        </div>
        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
            <i class="fa-solid fa-pen text-amber-500 text-sm"></i>
        </div>
    </div>
</div>

<!-- Panel Daftar Laporan -->
<div class="bg-white rounded-[14px] border border-[#EEF0F3] overflow-hidden" style="box-shadow: 0 1px 2px rgba(16,24,40,0.04);">
    <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h3 class="text-sm font-semibold text-gray-800">Riwayat Input Terbaru</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">Laporan keuangan yang baru saja diinput ke dalam sistem</p>
        </div>
        <a href="/keuangan" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 transition-colors">
            Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#FAFAFB] border-b border-gray-100">
                    <th class="py-3 px-5 text-[10px] font-bold text-gray-400 uppercase w-12 text-center">No</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-gray-400 uppercase">Klien</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-gray-400 uppercase text-center">Tahun</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-gray-400 uppercase">Status</th>
                    <th class="py-3 px-5 text-[10px] font-bold text-gray-400 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                <?php if (empty($recent_reports)): ?>
                <tr>
                    <td colspan="5" class="py-14 text-center text-gray-400 text-xs">
                        <i class="fa-solid fa-inbox text-2xl mb-3 text-gray-300 block"></i>
                        Belum ada laporan yang diinput ke dalam sistem.<br>
                        <a href="/keuangan/import" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs font-bold">Input Laporan Sekarang</a>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach($recent_reports as $index => $row): ?>
                    <tr class="hover:bg-[#FAFAFB] transition-colors">
                        <td class="py-3 px-5 text-center text-gray-400 text-xs"><?= $index + 1 ?></td>
                        <td class="py-3 px-5">
                            <span class="font-semibold text-gray-800"><?= esc($row['nama_perusahaan']) ?></span>
                            <span class="text-[10px] text-gray-400 ml-2">(<?= esc($row['kode_saham']) ?>)</span>
                        </td>
                        <td class="py-3 px-5 text-center font-medium"><?= $row['tahun'] ?></td>
                        <td class="py-3 px-5">
                            <?php 
                                $status = $row['status'];
                                if ($status == 'Ready for Analysis') {
                                    echo '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#FEF3E2] text-[#B45309]"><i class="fa-solid fa-play"></i> BELUM DIKIRIM</span>';
                                } elseif ($status == 'Sent to Analyst') {
                                    echo '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700"><i class="fa-solid fa-paper-plane"></i> TERKIRIM</span>';
                                } else {
                                    echo '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700"><i class="fa-solid fa-check"></i> PROSES ANALISIS</span>';
                                }
                            ?>
                        </td>
                        <td class="py-3 px-5 text-center">
                            <?php if($status == 'Ready for Analysis'): ?>
                            <a href="/keuangan/edit/<?= $row['id_laporan'] ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#FAFAFB] text-gray-500 border border-[#EEF0F3] hover:bg-amber-50 hover:text-amber-600 transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <?php else: ?>
                            <span class="text-[10px] text-gray-400 italic">Locked</span>
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
