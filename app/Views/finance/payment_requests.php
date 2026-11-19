<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="ph"><div class="ph-left"><h1>Payment Monitoring</h1><p>Status payment request Treasury berdasarkan organisasi aktif.</p></div></div>
<div class="card"><div style="overflow-x:auto;"><table class="tbl"><thead><tr><th>Entity</th><th>Periode</th><th>Nominal</th><th>Status</th><th>Failure Reason</th><th>Waktu</th></tr></thead><tbody>
<?php foreach ($requests as $request): ?>
<tr><td><?= esc($request['entity_type']) ?></td><td><?= esc($request['entity_period_month'] ?? '-') ?>/<?= esc($request['entity_period_year'] ?? '-') ?></td><td>Rp <?= number_format((float) $request['amount'], 0, ',', '.') ?></td><td><?= esc(strtoupper($request['status'])) ?></td><td><?= esc($request['failure_reason'] ?? '-') ?></td><td><?= esc($request['paid_at'] ?? $request['updated_at'] ?? '-') ?></td></tr>
<?php endforeach; ?>
<?php if ($requests === []): ?><tr><td colspan="6" class="text-center">Belum ada payment request.</td></tr><?php endif; ?>
</tbody></table></div></div>
<?= $this->endSection(); ?>
