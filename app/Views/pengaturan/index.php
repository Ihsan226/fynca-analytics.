<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Pengaturan Sistem</h1>
        <p>Konfigurasi umum dan preferensi aplikasi Fynca Analytics.</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

    <!-- Informasi Aplikasi -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title"><i class="fa-solid fa-circle-info" style="color:#3b82f6;margin-right:8px;"></i>Informasi Aplikasi</div>
                <div class="card-sub">Detail versi dan konfigurasi sistem.</div>
            </div>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Nama Sistem</span>
                    <span style="font-size:13px;font-weight:700;color:#0f172a;">Fynca Analytics</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Versi</span>
                    <span class="badge badge-green">v1.0.0</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Framework</span>
                    <span style="font-size:13px;font-weight:600;color:#0f172a;">CodeIgniter 4.7.4</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">PHP Version</span>
                    <span style="font-size:13px;font-weight:600;color:#0f172a;"><?= phpversion() ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Database</span>
                    <span style="font-size:13px;font-weight:600;color:#0f172a;">MySQL (db_analisis_pajak)</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Environment</span>
                    <span class="badge badge-amber">Development</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sektor Fokus -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title"><i class="fa-solid fa-bullseye" style="color:#e11d48;margin-right:8px;"></i>Profil Layanan (SaaS)</div>
                <div class="card-sub">Konfigurasi parameter default untuk modul perhitungan.</div>
            </div>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:14px;">
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Sektor Utama</span>
                    <span style="font-size:13px;font-weight:700;color:#0f172a;">Properti & Real Estate</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Kode Sektor</span>
                    <span class="badge badge-solid">PROP</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Tarif PPh Badan</span>
                    <span style="font-size:13px;font-weight:700;color:#e11d48;">22%</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Batas DER (PMK 169)</span>
                    <span style="font-size:13px;font-weight:700;color:#f59e0b;">4 : 1</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="font-size:13px;color:#64748b;font-weight:500;">Status</span>
                    <span class="badge badge-green">Aktif</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Info -->
<div class="insight-box" style="margin-top:20px;">
    <div class="insight-icon blue"><i class="fa-solid fa-shield-halved"></i></div>
    <div class="insight-content">
        <h4>Keamanan Sistem</h4>
        <p>Sistem ini dilindungi dengan autentikasi berbasis session. Pastikan untuk logout setelah selesai menggunakan sistem dan jangan membagikan kredensial akun Anda kepada pihak lain.</p>
    </div>
</div>

<?= $this->endSection(); ?>
