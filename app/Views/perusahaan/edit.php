<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="flex items-start justify-between mb-5">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Data Klien</h1>
        <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi perusahaan klien — <?= htmlspecialchars($perusahaan['kode_saham']) ?>.</p>
    </div>
    <a href="/perusahaan"
       class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors">
        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form action="/perusahaan/update/<?= $perusahaan['id_perusahaan'] ?>" method="POST">
            <?= csrf_field() ?>
            
            <!-- Basic Info -->
            <div style="margin-bottom:24px;">
                <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0 0 16px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-building" style="color:#3b5de7;"></i> Informasi Perusahaan
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Saham</label>
                        <input type="text" name="kode_saham" value="<?= htmlspecialchars($perusahaan['kode_saham']) ?>" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" value="<?= htmlspecialchars($perusahaan['nama_perusahaan']) ?>" required class="form-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sektor</label>
                        <select name="sektor" required class="form-input">
                            <option value="">-- Pilih Sektor --</option>
                            <?php foreach($sektors as $s): ?>
                            <option value="<?= esc($s['nama']) ?>" <?= $s['nama'] === $perusahaan['sektor'] ? 'selected' : '' ?>><?= esc($s['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tahun IPO <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="tahun_ipo" value="<?= htmlspecialchars($perusahaan['tahun_ipo']) ?>" min="1970" max="2030" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Tax Profile -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin-bottom:24px;">
                <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0 0 4px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-scale-balanced" style="color:#7c3aed;"></i> Profil Pajak Perusahaan
                </h3>
                <p style="font-size:12px;color:#94a3b8;margin:0 0 16px;">Digunakan untuk menentukan indikator mana yang berlaku (Applicable).</p>
                
                <div style="display:grid;gap:14px;">
                    <?php
                    $taxFields = [
                        ['name' => 'has_related_party',  'label' => 'Memiliki pihak berelasi?',                'desc' => 'Perusahaan afiliasi, induk, atau anak perusahaan'],
                        ['name' => 'has_rpt_transaction','label' => 'Memiliki transaksi dengan pihak berelasi?','desc' => 'Piutang, utang, atau penjualan kepada afiliasi'],
                        ['name' => 'has_cross_border',   'label' => 'Memiliki transaksi lintas negara?',       'desc' => 'Ekspor, impor, atau transaksi dengan entitas luar negeri'],
                        ['name' => 'has_interest_debt',  'label' => 'Memiliki utang berbunga?',                'desc' => 'Pinjaman bank, obligasi, atau utang berbunga lainnya'],
                        ['name' => 'is_public_company',  'label' => 'Perusahaan terbuka?',                    'desc' => 'Terdaftar di bursa efek'],
                    ];
                    foreach ($taxFields as $tf):
                    ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;"><?= $tf['label'] ?></p>
                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;"><?= $tf['desc'] ?></p>
                        </div>
                        <div style="display:flex;gap:12px;">
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="<?= $tf['name'] ?>" value="1" <?= ($perusahaan[$tf['name']] ?? 0) == 1 ? 'checked' : '' ?>> Ya
                            </label>
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="<?= $tf['name'] ?>" value="0" <?= ($perusahaan[$tf['name']] ?? 0) == 0 ? 'checked' : '' ?>> Tidak
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 bg-[#3b5de7] hover:bg-[#2a4ac7] text-white font-semibold px-5 py-2.5 rounded-lg shadow-sm transition-colors text-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Update Data
                </button>
                <a href="/perusahaan"
                   class="inline-flex items-center bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 font-semibold px-5 py-2.5 rounded-lg shadow-sm transition-colors text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>
