<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="flex items-start justify-between mb-5">
    <div>
        <h2 style="font-size:24px;font-weight:700;color:#0f172a;margin:0 0 5px 0;">Tambah Klien Baru</h2>
        <p style="font-size:14px;color:#64748b;margin:0;">Masukkan data perusahaan untuk analisis pajak.</p>
    </div>
    <a href="/perusahaan"
       class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors">
        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form action="/perusahaan/store" method="POST">
            <?= csrf_field() ?>
            
            <!-- Basic Info -->
            <div style="margin-bottom:24px;">
                <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0 0 16px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-building" style="color:#3b5de7;"></i> Informasi Perusahaan
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Saham <span class="text-gray-400 font-normal">(Misal: CTRA)</span></label>
                        <input type="text" name="kode_saham" required placeholder="Contoh: CTRA" class="form-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" required placeholder="Nama lengkap perusahaan" class="form-input">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sektor</label>
                        <select name="sektor" required class="form-input">
                            <option value="">-- Pilih Sektor --</option>
                            <?php foreach($sektors as $s): ?>
                            <option value="<?= esc($s['nama']) ?>"><?= esc($s['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tahun IPO <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="tahun_ipo" min="1970" max="2030" placeholder="Contoh: 1994" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Tax Profile (Applicability Engine) -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin-bottom:24px;">
                <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin:0 0 4px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-scale-balanced" style="color:#7c3aed;"></i> Profil Pajak Perusahaan
                </h3>
                <p style="font-size:12px;color:#94a3b8;margin:0 0 16px;">Digunakan untuk menentukan indikator mana yang berlaku (Applicable) bagi perusahaan ini.</p>
                
                <div style="display:grid;gap:14px;">
                    <!-- Has Related Party -->
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;">Memiliki pihak berelasi?</p>
                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Perusahaan afiliasi, induk, atau anak perusahaan</p>
                        </div>
                        <div style="display:flex;gap:12px;">
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="has_related_party" value="1"> Ya
                            </label>
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="has_related_party" value="0" checked> Tidak
                            </label>
                        </div>
                    </div>

                    <!-- Has RPT Transaction -->
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;">Memiliki transaksi dengan pihak berelasi?</p>
                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Piutang, utang, atau penjualan kepada afiliasi</p>
                        </div>
                        <div style="display:flex;gap:12px;">
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="has_rpt_transaction" value="1"> Ya
                            </label>
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="has_rpt_transaction" value="0" checked> Tidak
                            </label>
                        </div>
                    </div>

                    <!-- Has Cross Border -->
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;">Memiliki transaksi lintas negara?</p>
                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Ekspor, impor, atau transaksi dengan entitas luar negeri</p>
                        </div>
                        <div style="display:flex;gap:12px;">
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="has_cross_border" value="1"> Ya
                            </label>
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="has_cross_border" value="0" checked> Tidak
                            </label>
                        </div>
                    </div>

                    <!-- Has Interest Debt -->
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;">Memiliki utang berbunga?</p>
                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Pinjaman bank, obligasi, atau utang berbunga lainnya</p>
                        </div>
                        <div style="display:flex;gap:12px;">
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="has_interest_debt" value="1"> Ya
                            </label>
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="has_interest_debt" value="0" checked> Tidak
                            </label>
                        </div>
                    </div>

                    <!-- Is Public Company -->
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#0f172a;margin:0;">Perusahaan terbuka?</p>
                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">Terdaftar di bursa efek</p>
                        </div>
                        <div style="display:flex;gap:12px;">
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="is_public_company" value="1"> Ya
                            </label>
                            <label style="display:flex;align-items:center;gap:4px;font-size:13px;cursor:pointer;">
                                <input type="radio" name="is_public_company" value="0" checked> Tidak
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 bg-[#3b5de7] hover:bg-[#2a4ac7] text-white font-semibold px-5 py-2.5 rounded-lg shadow-sm transition-colors text-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data
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
