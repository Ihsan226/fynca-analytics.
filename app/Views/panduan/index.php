<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Dokumentasi & Metrik</h1>
        <p>Dokumentasi lengkap mengenai algoritma, metrik, dan proses yang digunakan dalam sistem Fynca Analytics.</p>
    </div>
</div>

<!-- Metrik Analitik -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div>
            <div class="card-title"><i class="fa-solid fa-flask" style="color:#4f46e5;margin-right:8px;"></i>Indikator Utama (KPI)</div>
            <div class="card-sub">Metrik dasar yang diukur dan digunakan sebagai parameter analisis dalam sistem.</div>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="text-align:left;width:40px;">No</th>
                    <th style="text-align:left;">Metrik</th>
                    <th style="text-align:left;">Simbol</th>
                    <th style="text-align:left;">Jenis</th>
                    <th style="text-align:left;">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">1</td>
                    <td style="font-weight:600;">Effective Tax Rate (ETR)</td>
                    <td><span class="badge badge-blue">Y</span></td>
                    <td><span class="badge badge-amber">Dependen</span></td>
                    <td style="font-size:12px;color:#64748b;">Mengukur tingkat agresivitas pajak perusahaan berdasarkan rasio beban pajak terhadap laba sebelum pajak.</td>
                </tr>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">2</td>
                    <td style="font-weight:600;">Related Party Transaction (RPT)</td>
                    <td><span class="badge badge-blue">X1</span></td>
                    <td><span class="badge badge-green">Independen</span></td>
                    <td style="font-size:12px;color:#64748b;">Proporsi transaksi dengan pihak berelasi terhadap total aset perusahaan.</td>
                </tr>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">3</td>
                    <td style="font-weight:600;">Thin Capitalization / DER</td>
                    <td><span class="badge badge-blue">X2</span></td>
                    <td><span class="badge badge-green">Independen</span></td>
                    <td style="font-size:12px;color:#64748b;">Debt to Equity Ratio (DER) mengukur proporsi hutang terhadap modal sendiri sebagai indikator thin capitalization.</td>
                </tr>
                <tr>
                    <td style="color:#94a3b8;font-size:12px;">4</td>
                    <td style="font-weight:600;">Sales Growth</td>
                    <td><span class="badge badge-blue">X3</span></td>
                    <td><span class="badge badge-green">Independen</span></td>
                    <td style="font-size:12px;color:#64748b;">Pertumbuhan penjualan tahun berjalan dibandingkan tahun sebelumnya.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Rumus Perhitungan -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div>
            <div class="card-title"><i class="fa-solid fa-square-root-variable" style="color:#e11d48;margin-right:8px;"></i>Algoritma Kalkulasi</div>
            <div class="card-sub">Formula sistematis yang digunakan untuk menghitung nilai metrik.</div>
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">

            <!-- ETR -->
            <div style="background:linear-gradient(145deg,#f8fafc,#f1f5f9);border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(225,29,72,0.1);display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-percent" style="color:#e11d48;font-size:14px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size:14px;font-weight:700;color:#0f172a;">Effective Tax Rate (ETR)</h4>
                        <span class="badge badge-amber" style="font-size:9px;">Metrik Utama</span>
                    </div>
                </div>
                <div style="background:white;border:1px solid #e2e8f0;border-radius:8px;padding:14px;text-align:center;font-family:monospace;font-size:15px;font-weight:700;color:#1e293b;">
                    ETR = Beban Pajak / Laba Sebelum Pajak
                </div>
                <p style="font-size:11px;color:#64748b;margin-top:10px;line-height:1.5;">ETR &lt; 22% menunjukkan perusahaan cenderung agresif dalam perencanaan pajak. Tarif efektif PPh Badan saat ini adalah 22%.</p>
            </div>

            <!-- RPT -->
            <div style="background:linear-gradient(145deg,#f8fafc,#f1f5f9);border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(79,70,229,0.1);display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-handshake" style="color:#4f46e5;font-size:14px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size:14px;font-weight:700;color:#0f172a;">Related Party Transaction</h4>
                        <span class="badge badge-green" style="font-size:9px;">Indikator Input</span>
                    </div>
                </div>
                <div style="background:white;border:1px solid #e2e8f0;border-radius:8px;padding:14px;text-align:center;font-family:monospace;font-size:15px;font-weight:700;color:#1e293b;">
                    RPT = Piutang Berelasi / Total Aset
                </div>
                <p style="font-size:11px;color:#64748b;margin-top:10px;line-height:1.5;">Semakin besar proporsi RPT, semakin tinggi potensi transfer pricing yang dapat mempengaruhi kewajiban pajak.</p>
            </div>

            <!-- DER -->
            <div style="background:linear-gradient(145deg,#f8fafc,#f1f5f9);border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(245,158,11,0.1);display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-scale-balanced" style="color:#f59e0b;font-size:14px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size:14px;font-weight:700;color:#0f172a;">Debt to Equity Ratio (DER)</h4>
                        <span class="badge badge-green" style="font-size:9px;">Indikator Input</span>
                    </div>
                </div>
                <div style="background:white;border:1px solid #e2e8f0;border-radius:8px;padding:14px;text-align:center;font-family:monospace;font-size:15px;font-weight:700;color:#1e293b;">
                    DER = Total Hutang / Total Ekuitas
                </div>
                <p style="font-size:11px;color:#64748b;margin-top:10px;line-height:1.5;">Batas wajar DER 4:1 berdasarkan PMK No. 169/PMK.010/2015. DER &gt; 4 mengindikasikan thin capitalization.</p>
            </div>

            <!-- Sales Growth -->
            <div style="background:linear-gradient(145deg,#f8fafc,#f1f5f9);border:1px solid #e2e8f0;border-radius:12px;padding:20px;">
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-arrow-trend-up" style="color:#10b981;font-size:14px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size:14px;font-weight:700;color:#0f172a;">Sales Growth</h4>
                        <span class="badge badge-green" style="font-size:9px;">Indikator Input</span>
                    </div>
                </div>
                <div style="background:white;border:1px solid #e2e8f0;border-radius:8px;padding:14px;text-align:center;font-family:monospace;font-size:15px;font-weight:700;color:#1e293b;">
                    SG = (Penjualan<sub>t</sub> - Penjualan<sub>t-1</sub>) / Penjualan<sub>t-1</sub>
                </div>
                <p style="font-size:11px;color:#64748b;margin-top:10px;line-height:1.5;">Pertumbuhan penjualan yang tinggi dapat mempengaruhi strategi perencanaan pajak perusahaan.</p>
            </div>

        </div>
    </div>
</div>

<!-- Metodologi -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div>
            <div class="card-title"><i class="fa-solid fa-diagram-project" style="color:#10b981;margin-right:8px;"></i>Metodologi Analisis</div>
            <div class="card-sub">Tahapan analisis yang diterapkan dalam sistem.</div>
        </div>
    </div>
    <div class="card-body">
        <div style="display:flex;gap:16px;flex-wrap:wrap;">
            <div style="flex:1;min-width:200px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:18px;text-align:center;">
                <div style="width:40px;height:40px;border-radius:50%;background:#3b82f6;color:white;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-weight:800;">1</div>
                <h4 style="font-size:13px;font-weight:700;color:#1e3a8a;margin-bottom:4px;">Input Data</h4>
                <p style="font-size:11px;color:#3b82f6;">Masukkan data laporan keuangan perusahaan (neraca & laba rugi).</p>
            </div>
            <div style="flex:1;min-width:200px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:18px;text-align:center;">
                <div style="width:40px;height:40px;border-radius:50%;background:#16a34a;color:white;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-weight:800;">2</div>
                <h4 style="font-size:13px;font-weight:700;color:#166534;margin-bottom:4px;">Kalkulasi Otomatis</h4>
                <p style="font-size:11px;color:#16a34a;">Sistem menghitung ETR, RPT, DER, dan Sales Growth secara otomatis.</p>
            </div>
            <div style="flex:1;min-width:200px;background:#fdf4ff;border:1px solid #e9d5ff;border-radius:12px;padding:18px;text-align:center;">
                <div style="width:40px;height:40px;border-radius:50%;background:#9333ea;color:white;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-weight:800;">3</div>
                <h4 style="font-size:13px;font-weight:700;color:#581c87;margin-bottom:4px;">Klasifikasi</h4>
                <p style="font-size:11px;color:#9333ea;">Hasil diklasifikasi berdasarkan parameter threshold yang telah dikonfigurasi.</p>
            </div>
            <div style="flex:1;min-width:200px;background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:18px;text-align:center;">
                <div style="width:40px;height:40px;border-radius:50%;background:#d97706;color:white;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-weight:800;">4</div>
                <h4 style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:4px;">Laporan</h4>
                <p style="font-size:11px;color:#d97706;">Laporan komprehensif dengan visualisasi grafik dan rekomendasi.</p>
            </div>
        </div>
    </div>
</div>

<!-- Referensi Regulasi -->
<div class="insight-box">
    <div class="insight-icon blue"><i class="fa-solid fa-scale-balanced"></i></div>
    <div class="insight-content">
        <h4>Dasar Regulasi</h4>
        <p>Sistem ini mengacu pada <b>PMK No. 169/PMK.010/2015</b> tentang Perbandingan Hutang dan Modal (DER 4:1), <b>UU PPh Pasal 18</b> tentang hubungan istimewa, dan tarif PPh Badan efektif sebesar <b>22%</b> sesuai UU HPP.</p>
    </div>
</div>

<?= $this->endSection(); ?>
