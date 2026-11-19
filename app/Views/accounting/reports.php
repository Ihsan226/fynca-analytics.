<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Laporan Keuangan (P&L)</h2>
        <p class="text-sm text-gray-500 mt-1">Kompilasi otomatis transaksi mentah (SUM) menjadi struktur Laba/Rugi.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Engine Rekapitulasi (SUM)</h3>
            <p class="text-[10px] text-gray-500">Data ini dipetakan dari tabel transaksi mentah yang dikirim oleh Staff.</p>
        </div>
    </div>
    <div class="p-6">
        <table class="w-full text-left text-sm border-collapse">
            <tbody>
                <tr class="border-b border-gray-100">
                    <td class="py-3 font-semibold text-gray-700">Pendapatan Bruto</td>
                    <td class="py-3 text-right text-gray-800">Rp <?= number_format($pnl['pendapatan'], 0, ',', '.') ?></td>
                </tr>
                <tr class="border-b border-gray-100">
                    <td class="py-3 font-semibold text-gray-700 pl-4">Harga Pokok Penjualan (HPP)</td>
                    <td class="py-3 text-right text-gray-800">(Rp <?= number_format($pnl['hpp'], 0, ',', '.') ?>)</td>
                </tr>
                <tr class="border-b-2 border-gray-200 bg-gray-50 font-bold">
                    <td class="py-3 text-gray-800">LABA KOTOR</td>
                    <td class="py-3 text-right text-blue-600">Rp <?= number_format($pnl['laba_kotor'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td class="py-3 font-semibold text-gray-700" colspan="2">Beban Operasional:</td>
                </tr>
                <?php foreach ($pnl['beban_operasional'] as $nama_beban => $nominal): ?>
                <tr class="border-b border-gray-100">
                    <td class="py-2 pl-4 text-gray-600">- <?= htmlspecialchars($nama_beban) ?></td>
                    <td class="py-2 text-right text-gray-800">(Rp <?= number_format($nominal, 0, ',', '.') ?>)</td>
                </tr>
                <?php endforeach; ?>
                <tr class="border-b-2 border-gray-200 bg-gray-50 font-bold">
                    <td class="py-3 text-gray-800">TOTAL BEBAN OPERASIONAL</td>
                    <td class="py-3 text-right text-amber-600">(Rp <?= number_format($pnl['total_beban_operasional'], 0, ',', '.') ?>)</td>
                </tr>
                <tr class="border-b-2 border-gray-200 font-bold">
                    <td class="py-4 text-gray-800 text-base">LABA SEBELUM PAJAK</td>
                    <td class="py-4 text-right text-indigo-600 text-base">Rp <?= number_format($pnl['laba_sebelum_pajak'], 0, ',', '.') ?></td>
                </tr>
                <tr class="border-b border-gray-100">
                    <td class="py-3 font-semibold text-gray-700 pl-4">Estimasi Beban Pajak (PPh Badan 22%)</td>
                    <td class="py-3 text-right text-red-500">(Rp <?= number_format($pnl['estimasi_beban_pajak'], 0, ',', '.') ?>)</td>
                </tr>
                <tr class="border-b-2 border-gray-200 bg-emerald-50 text-emerald-700 font-bold text-lg">
                    <td class="py-4">LABA BERSIH (NET PROFIT)</td>
                    <td class="py-4 text-right">Rp <?= number_format($pnl['laba_bersih'], 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6 flex justify-end gap-3">
            <form id="formSubmitTreasurer" action="/accounting/submit-to-treasurer" method="post">
                <?= csrf_field() ?>
                <button type="button" id="btnSubmitTreasurer" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-lock"></i> Kirim Laporan &amp; Lock
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('btnSubmitTreasurer').addEventListener('click', function() {
    Swal.fire({
        title: 'Kirim ke Bendahara?',
        text: 'Laporan akan di-Lock dan tidak dapat diubah lagi. Data akan dikirim ke Bendahara.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kirim & Lock'
    }).then(function(result) {
        if (result.isConfirmed) {
            document.getElementById('formSubmitTreasurer').submit();
        }
    });
});
</script>

<?= $this->endSection(); ?>
