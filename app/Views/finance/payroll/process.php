<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Proses Payroll</h1>
        <p>Hitung gaji bulanan, tambahkan bonus/potongan, dan simpan draf slip gaji sebelum diajukan ke Owner.</p>
    </div>
</div>

<!-- FILTER PERIODE -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form action="/finance/payroll/process" method="get" style="display:flex; gap:15px; align-items:flex-end;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-input">
                    <?php for($m=1; $m<=12; ++$m): 
                        $mStr = str_pad($m, 2, '0', STR_PAD_LEFT);
                    ?>
                    <option value="<?= $mStr ?>" <?= ($bulan == $mStr) ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-input">
                    <?php for($y=date('Y')-2; $y<=date('Y')+1; ++$y): ?>
                    <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter"></i> Pilih Periode
            </button>
        </form>
    </div>
</div>

<!-- PAYROLL WORKSPACE -->
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <div class="card-title">Kalkulator Gaji</div>
            <div class="card-sub">Periode: <?= date('F', mktime(0, 0, 0, $bulan, 1)) ?> <?= $tahun ?></div>
        </div>
        <div>
            <?php 
                $badge = 'badge-gray';
                $statusText = 'Belum Ada Draf';
                if ($status_batch === 'draft') { $badge = 'badge-blue'; $statusText = 'Draf Tersimpan'; }
                elseif ($status_batch === 'pending_approval') { $badge = 'badge-amber'; $statusText = 'Menunggu Persetujuan'; }
                elseif ($status_batch === 'approved') { $badge = 'badge-emerald'; $statusText = 'Disetujui'; }
                elseif ($status_batch === 'locked') { $badge = 'badge-solid'; $statusText = 'Terkunci (Selesai)'; }
            ?>
            <span class="badge <?= $badge ?>"><i class="fa-solid fa-info-circle mr-1"></i> <?= $statusText ?></span>
        </div>
    </div>

    <?php 
        $isReadonly = in_array($status_batch, ['pending_approval', 'approved', 'locked']); 
    ?>

    <form action="/finance/payroll/generate" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="periode_bulan" value="<?= $bulan ?>">
        <input type="hidden" name="periode_tahun" value="<?= $tahun ?>">

        <div style="overflow-x:auto;">
            <table class="tbl">
                <thead>
                    <tr>
                        <th style="text-align:left;width:250px;">Karyawan</th>
                        <th style="text-align:right;">Gaji Pokok</th>
                        <th style="text-align:right;">Tunjangan Tetap</th>
                        <th style="text-align:right;">Bonus Performa</th>
                        <th style="text-align:right;">Pot. Wajib (BPJS)</th>
                        <th style="text-align:right;">Potongan Lain</th>
                        <th style="text-align:right;background:#f8fafc;">Total Bersih (THP)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">Belum ada karyawan yang dikonfigurasi gajinya.</div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($users as $i => $u): 
                        // Jika sudah ada draf, pakai data draf. Jika tidak, pakai master data.
                        $pData = $payrollMap[$u['id_user']] ?? null;

                        $gp = $pData ? $pData['gaji_pokok'] : ($u['gaji_pokok'] ?? 0);
                        $tj = $pData ? $pData['tunjangan'] : ($u['tunjangan'] ?? 0);
                        $bn = $pData ? $pData['bonus'] : 0;
                        $pb = $pData ? $pData['potongan_bpjs'] : ($u['potongan_bpjs'] ?? 0);
                        $pl = $pData ? $pData['potongan_lain'] : 0;
                        $thp = $pData ? $pData['total_bersih'] : ($gp + $tj + $bn - $pb - $pl);
                    ?>
                    <tr>
                        <td>
                            <input type="hidden" name="id_users[]" value="<?= $u['id_user'] ?>">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">
                                    <?= strtoupper(substr($u['nama_lengkap'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-weight:600; font-size:13px;"><?= esc($u['nama_lengkap']) ?></div>
                                    <div style="font-size:11px; color:#94a3b8;"><?= strtoupper(esc($u['role'])) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="number" class="form-input thp-calc" style="text-align:right;font-size:13px;padding:6px;min-width:110px;" name="gaji_pokoks[]" value="<?= $gp ?>" <?= $isReadonly ? 'readonly' : '' ?>>
                        </td>
                        <td>
                            <input type="number" class="form-input thp-calc" style="text-align:right;font-size:13px;padding:6px;min-width:110px;" name="tunjangans[]" value="<?= $tj ?>" <?= $isReadonly ? 'readonly' : '' ?>>
                        </td>
                        <td>
                            <input type="number" class="form-input thp-calc" style="text-align:right;font-size:13px;padding:6px;min-width:110px;border-color:#10b981;" name="bonuses[]" value="<?= $bn ?>" <?= $isReadonly ? 'readonly' : '' ?>>
                        </td>
                        <td>
                            <input type="number" class="form-input thp-calc" style="text-align:right;font-size:13px;padding:6px;min-width:110px;" name="potongan_bpjs[]" value="<?= $pb ?>" <?= $isReadonly ? 'readonly' : '' ?>>
                        </td>
                        <td>
                            <input type="number" class="form-input thp-calc" style="text-align:right;font-size:13px;padding:6px;min-width:110px;border-color:#ef4444;" name="potongan_lain[]" value="<?= $pl ?>" <?= $isReadonly ? 'readonly' : '' ?>>
                        </td>
                        <td style="background:#f8fafc;">
                            <input type="text" class="form-input thp-result" style="text-align:right;font-size:14px;padding:6px;font-weight:bold;color:#4f46e5;background:transparent;border:none;" value="<?= number_format($thp, 0, ',', '.') ?>" readonly>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!$isReadonly && !empty($users)): ?>
        <div style="padding:20px; display:flex; justify-content:flex-end; gap:10px; border-top:1px solid #e2e8f0; background:#f8fafc; border-radius:0 0 16px 16px;">
            <button type="submit" class="btn btn-ghost" style="border:1px solid #cbd5e1;">
                <i class="fa-solid fa-save"></i> Simpan Draf
            </button>
        </div>
        <?php endif; ?>
    </form>
</div>

<?php if ($status_batch === 'draft'): ?>
<div class="card mt-5">
    <div class="card-body" style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h4 style="margin:0; color:#334155; font-weight:600;"><i class="fa-solid fa-paper-plane mr-2 text-indigo-500"></i> Ajukan ke Executive</h4>
            <p style="margin:5px 0 0 0; color:#64748b; font-size:13px;">Kirim draf ini ke Owner untuk divalidasi. Draf akan dikunci selama masa review.</p>
        </div>
        <form action="/finance/payroll/submit" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="periode_bulan" value="<?= $bulan ?>">
            <input type="hidden" name="periode_tahun" value="<?= $tahun ?>">
            <button type="submit" class="btn btn-primary" style="background:linear-gradient(135deg,#4f46e5,#7c3aed); border:none; padding:10px 20px;">
                Submit for Approval <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const inputs = row.querySelectorAll('.thp-calc');
        const result = row.querySelector('.thp-result');
        
        if (inputs.length > 0 && result) {
            const calculateTHP = () => {
                const gp = parseFloat(inputs[0].value) || 0;
                const tj = parseFloat(inputs[1].value) || 0;
                const bn = parseFloat(inputs[2].value) || 0;
                const pb = parseFloat(inputs[3].value) || 0;
                const pl = parseFloat(inputs[4].value) || 0;
                
                const thp = gp + tj + bn - pb - pl;
                result.value = new Intl.NumberFormat('id-ID').format(thp);
            };

            inputs.forEach(input => {
                input.addEventListener('input', calculateTHP);
            });
        }
    });
});
</script>

<?= $this->endSection(); ?>
