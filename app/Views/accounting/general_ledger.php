<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="ph"><div class="ph-left"><h1>General Ledger</h1><p>Rincian jurnal posted berdasarkan akun dan periode.</p></div></div>
<div class="card">
    <form method="get" class="flex flex-wrap gap-3 p-4">
        <input name="company_id" value="<?= esc($companyId) ?>" placeholder="ID Perusahaan" required class="form-control">
        <input name="coa_id" value="<?= esc($coaId) ?>" placeholder="ID COA" required class="form-control">
        <input type="date" name="from" value="<?= esc($from) ?>" class="form-control">
        <input type="date" name="to" value="<?= esc($to) ?>" class="form-control">
        <button class="btn btn-primary" type="submit">Tampilkan</button>
    </form>
    <div style="overflow-x:auto;"><table class="tbl"><thead><tr><th>Tanggal</th><th>Referensi</th><th>Deskripsi</th><th>Debit</th><th>Kredit</th><th>Pergerakan</th></tr></thead><tbody>
    <?php foreach ($ledger as $row): ?>
        <tr><td><?= esc($row['tanggal']) ?></td><td><?= esc($row['referensi'] ?? '-') ?></td><td><?= esc($row['deskripsi'] ?? '') ?></td><td class="text-right">Rp <?= number_format((float) $row['debit'], 0, ',', '.') ?></td><td class="text-right">Rp <?= number_format((float) $row['credit'], 0, ',', '.') ?></td><td class="text-right">Rp <?= number_format((float) $row['movement'], 0, ',', '.') ?></td></tr>
    <?php endforeach; ?>
    <?php if ($ledger === []): ?><tr><td colspan="6" class="text-center">Masukkan perusahaan, COA, dan periode untuk melihat data.</td></tr><?php endif; ?>
    </tbody></table></div>
</div>
<?= $this->endSection(); ?>
