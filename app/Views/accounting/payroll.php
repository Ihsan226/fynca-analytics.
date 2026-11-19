<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Kalkulasi Payroll & PPh 21</h2>
        <p class="text-sm text-gray-500 mt-1">Pemrosesan Take Home Pay karyawan dan Pajak Penghasilan Pasal 21.</p>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
    <i class="fa-solid fa-check-circle"></i>
    <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
<div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="font-bold text-gray-800 text-sm">Engine Kalkulasi Otomatis (<?= date('F', mktime(0, 0, 0, $bulan, 10)) ?> <?= $tahun ?>)</h3>
            <p class="text-[10px] text-gray-500">Sistem akan mengambil data master dan parameter tarif pajak terbaru.</p>
        </div>
        <div class="flex gap-2">
            <!-- Form Header to submit period -->
            <form action="" method="get" class="flex gap-2">
                <select name="bulan" class="text-sm border border-gray-300 rounded-lg px-3 py-2 w-32 focus:ring-2 focus:ring-blue-100 outline-none">
                    <?php for($m=1; $m<=12; $m++): ?>
                        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 10)) ?></option>
                    <?php endfor; ?>
                </select>
                <select name="tahun" class="text-sm border border-gray-300 rounded-lg px-3 py-2 w-24 focus:ring-2 focus:ring-blue-100 outline-none">
                    <?php for($y=date('Y')-1; $y<=date('Y')+1; $y++): ?>
                        <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">Load Periode</button>
            </form>
        </div>
    </div>
    
    <div class="p-5">
        <?php if($status_batch == 'pending_approval' || $status_batch == 'approved' || $status_batch == 'locked'): ?>
            <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <i class="fa-solid fa-lock"></i>
                <span class="text-sm font-medium">Data payroll bulan ini sudah dikirim ke Bendahara dan bersifat Read-Only.</span>
            </div>
        <?php else: ?>
            <form action="/accounting/payroll-generate" method="post" id="formPayroll">
                <?= csrf_field() ?>
                <input type="hidden" name="periode_bulan" value="<?= esc($bulan) ?>">
                <input type="hidden" name="periode_tahun" value="<?= esc($tahun) ?>">
                
                <div class="overflow-x-auto mb-4 border border-gray-200 rounded-lg">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200 text-[10px] text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="p-3 font-bold">Karyawan</th>
                                <th class="p-3 font-bold text-right">Gaji Pokok</th>
                                <th class="p-3 font-bold text-right">Tunjangan</th>
                                <th class="p-3 font-bold text-right">Bonus</th>
                                <th class="p-3 font-bold text-right">Pot. BPJS</th>
                                <th class="p-3 font-bold text-right">Lainnya</th>
                                <th class="p-3 font-bold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            <?php if(!empty($users)): ?>
                                <?php foreach($users as $u): ?>
                                    <?php 
                                        $id_user = $u['id_user'];
                                        $p = $payrollMap[$id_user] ?? null;
                                        // Mock default values if not in DB
                                        $gp = $p ? $p['gaji_pokok'] : ($u['gaji_pokok'] ?? 5000000);
                                        $tj = $p ? $p['tunjangan'] : ($u['tunjangan'] ?? 1000000);
                                        $bn = $p ? $p['bonus'] : 0;
                                        $pb = $p ? $p['potongan_bpjs'] : 150000;
                                        $pl = $p ? $p['potongan_lain'] : 0;
                                    ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="p-3">
                                            <input type="hidden" name="id_users[]" value="<?= $id_user ?>">
                                            <div class="font-bold text-blue-600"><?= esc($u['nama_lengkap']) ?></div>
                                            <div class="text-[10px] text-gray-400">ID: <?= $id_user ?> | <?= esc($u['role']) ?></div>
                                        </td>
                                        <td class="p-3"><input type="text" name="gaji_pokoks[]" value="<?= number_format($gp, 0, '', '') ?>" class="w-full text-right border border-gray-300 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 outline-none"></td>
                                        <td class="p-3"><input type="text" name="tunjangans[]" value="<?= number_format($tj, 0, '', '') ?>" class="w-full text-right border border-gray-300 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 outline-none"></td>
                                        <td class="p-3"><input type="text" name="bonuses[]" value="<?= number_format($bn, 0, '', '') ?>" class="w-full text-right border border-gray-300 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 outline-none"></td>
                                        <td class="p-3"><input type="text" name="potongan_bpjs[]" value="<?= number_format($pb, 0, '', '') ?>" class="w-full text-right border border-gray-300 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 outline-none"></td>
                                        <td class="p-3"><input type="text" name="potongan_lain[]" value="<?= number_format($pl, 0, '', '') ?>" class="w-full text-right border border-gray-300 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 outline-none"></td>
                                        <td class="p-3 text-center">
                                            <?php if($p): ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-bold bg-green-50 text-green-600 border border-green-200">
                                                    TERHITUNG
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                                    BELUM
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-sm text-gray-500">Tidak ada data karyawan aktif.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="flex justify-end mt-4 gap-3">
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-calculator"></i> Kalkulasi PPh 21 & Kirim ke Bendahara
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php if($status_batch == 'pending_approval' || $status_batch == 'approved' || $status_batch == 'locked'): ?>
<!-- Table of results if already processed -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
        <h3 class="font-bold text-gray-800 text-sm">Hasil Kalkulasi (Menunggu Approval Bendahara)</h3>
    </div>
    <div class="overflow-x-auto p-5">
        <table class="w-full text-left border-collapse text-sm border border-gray-200 rounded-lg">
            <thead class="bg-gray-50 border-b border-gray-200 text-[10px] text-gray-500 uppercase tracking-wider">
                <tr>
                    <th class="p-3 font-bold">Karyawan</th>
                    <th class="p-3 font-bold text-right">Total Pendapatan Bruto</th>
                    <th class="p-3 font-bold text-right">Potongan (BPJS + Lain)</th>
                    <th class="p-3 font-bold text-right">Estimasi PPh 21 (TER)</th>
                    <th class="p-3 font-bold text-right text-emerald-600">Take Home Pay</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($users as $u): ?>
                    <?php 
                        $p = $payrollMap[$u['id_user']] ?? null;
                        if($p):
                            $bruto = $p['gaji_pokok'] + $p['tunjangan'] + $p['bonus'];
                            $pot = $p['potongan_bpjs'] + $p['potongan_lain'];
                            // Deriving PPh21 visually from total_bersih
                            $thp = $p['total_bersih'];
                            $pph21 = $bruto - $pot - $thp;
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-medium text-gray-800"><?= esc($u['nama_lengkap']) ?></td>
                        <td class="p-3 text-right">Rp <?= number_format($bruto, 0, ',', '.') ?></td>
                        <td class="p-3 text-right">Rp <?= number_format($pot, 0, ',', '.') ?></td>
                        <td class="p-3 text-right text-red-500">- Rp <?= number_format($pph21, 0, ',', '.') ?></td>
                        <td class="p-3 text-right font-bold text-emerald-600">Rp <?= number_format($thp, 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection(); ?>
