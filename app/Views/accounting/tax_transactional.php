<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Pajak Transaksional (PPh 22, 23, 24)</h2>
        <p class="text-sm text-gray-500 mt-1">Sistem deteksi otomatis transaksi yang terkena objek pemotongan pajak.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Deteksi Objek Pajak dari Transaksi Raw</h3>
            <p class="text-[10px] text-gray-500">Keyword "Sewa", "Jasa", "Kredit Pajak" akan memicu sistem untuk menghitung persentase pajaknya.</p>
        </div>
        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full"><i class="fa-solid fa-robot"></i> Auto-Detected</span>
    </div>
    
    <div class="overflow-x-auto p-5">
        <table class="w-full text-left border-collapse text-sm">
            <thead class="bg-gray-50 border-b border-gray-200 text-[10px] text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="p-3 font-bold">Tanggal</th>
                    <th class="p-3 font-bold">Deskripsi Transaksi</th>
                    <th class="p-3 font-bold text-right">Nominal Transaksi (Bruto)</th>
                    <th class="p-3 font-bold text-center">Jenis Objek Pajak</th>
                    <th class="p-3 font-bold text-center">Tarif</th>
                    <th class="p-3 font-bold text-right">Potongan/Kredit Pajak</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(!empty($taxDetected)): ?>
                    <?php foreach($taxDetected as $t): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 text-gray-600 whitespace-nowrap"><?= date('d M Y', strtotime($t['trx']['tanggal'])) ?></td>
                            <td class="p-3 font-medium text-gray-800"><?= esc($t['trx']['deskripsi']) ?></td>
                            <td class="p-3 text-right">Rp <?= number_format($t['trx']['nominal'], 0, ',', '.') ?></td>
                            <td class="p-3 text-center">
                                <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-amber-200">
                                    <?= $t['jenis'] ?>
                                </span>
                            </td>
                            <td class="p-3 text-center font-bold text-gray-600"><?= $t['tarif'] ?></td>
                            <td class="p-3 text-right font-bold text-red-600">Rp <?= number_format($t['potongan'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-5 text-center text-gray-500">Tidak ada objek pajak terdeteksi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                <tr>
                    <td colspan="5" class="p-4 text-right text-gray-800 uppercase">TOTAL PAJAK TRANSAKSIONAL :</td>
                    <td class="p-4 text-right text-red-600 text-lg">Rp <?= number_format($totalPajak, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="mt-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg text-xs flex gap-2 items-start">
            <i class="fa-solid fa-circle-info mt-0.5"></i>
            <p>Total pajak ini akan otomatis masuk ke perhitungan estimasi beban pajak tahunan atau dipotong langsung pada saat pencairan (jika bersinggungan dengan modul Kas).</p>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
