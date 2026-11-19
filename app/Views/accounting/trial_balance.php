<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="ph"><div class="ph-left"><h1>Trial Balance</h1><p>Validasi keseimbangan debit dan kredit jurnal posted.</p></div></div>
<div class="card">
    <form method="get" class="flex flex-wrap gap-3 p-4">
        <input name="company_id" value="<?= esc($companyId) ?>" placeholder="ID Perusahaan" required class="form-control">
        <input type="date" name="from" value="<?= esc($from) ?>" class="form-control">
        <input type="date" name="to" value="<?= esc($to) ?>" class="form-control">
        <button class="btn btn-primary" type="submit">Tampilkan</button>
    </form>
    <div class="p-4 font-semibold <?= $trialBalance['balanced'] ? 'text-emerald-600' : 'text-red-600' ?>">
        <?= $trialBalance['balanced'] ? 'Trial Balance seimbang' : 'Trial Balance tidak seimbang' ?>
    </div>
    <div style="overflow-x:auto;"><table class="tbl"><thead><tr><th>Kode</th><th>Akun</th><th>Debit</th><th>Kredit</th></tr></thead><tbody>
    <?php foreach ($trialBalance['accounts'] as $account): ?>
        <tr><td><?= esc($account['kode_akun']) ?></td><td><?= esc($account['nama_akun']) ?></td><td class="text-right">Rp <?= number_format($account['total_debit'], 0, ',', '.') ?></td><td class="text-right">Rp <?= number_format($account['total_credit'], 0, ',', '.') ?></td></tr>
    <?php endforeach; ?>
    </tbody><tfoot><tr><th colspan="2">Total</th><th>Rp <?= number_format($trialBalance['total_debit'], 0, ',', '.') ?></th><th>Rp <?= number_format($trialBalance['total_credit'], 0, ',', '.') ?></th></tr></tfoot></table></div>
</div>
<?= $this->endSection(); ?>
