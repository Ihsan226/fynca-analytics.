<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Input Data Laporan Keuangan</h1>
        <p>Masukkan data keuangan — indikator akan dikalkulasi otomatis oleh sistem.</p>
    </div>
    <a href="/keuangan" class="btn btn-ghost">
        <i class="fa-solid fa-arrow-left" style="font-size:11px;"></i> Kembali
    </a>
</div>

<?php if(session()->getFlashdata('error')): ?>
<div class="flash-msg" style="display:flex;align-items:center;gap:10px;margin-bottom:16px;padding:11px 16px;background:#fff1f2;border:1px solid #fecdd3;color:#be123c;border-radius:10px;font-size:13px;font-weight:500;">
    <i class="fa-solid fa-triangle-exclamation" style="color:#f43f5e;"></i>
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<form action="/keuangan/store" method="POST">
    <?= csrf_field() ?>

    <!-- Import File Section -->
    <div class="form-section" style="margin-bottom:20px; background: #eff6ff; border: 1px dashed #3b82f6;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0; font-size:15px; color:#1e40af; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Fill (Import Laporan Keuangan)
                </h3>
                <p style="margin:4px 0 0; font-size:12px; color:#3b82f6;">Upload laporan keuangan PDF/Excel, sistem akan otomatis membaca dan mengisi data di bawah.</p>
            </div>
            <div>
                <label for="laporan_file" class="btn btn-primary" style="cursor:pointer; display:inline-flex; align-items:center; gap:6px; margin:0; font-size:12px; padding:8px 12px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload File
                </label>
                <input type="file" id="laporan_file" accept=".pdf, .xls, .xlsx" style="display:none;">
            </div>
        </div>
        <div id="import-status" style="margin-top:12px; display:none; padding:10px; border-radius:8px; font-size:12px; font-weight:600;"></div>
    </div>

    <!-- Info Umum -->
    <div class="form-section" style="margin-bottom:16px;">
        <div class="form-section-title">
            <i class="fa-solid fa-circle-info" style="color:#3b5de7;"></i> Informasi Umum
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Pilih Perusahaan</label>
                <select name="id_perusahaan" required class="form-input">
                    <option value="">-- Pilih Perusahaan --</option>
                    <?php foreach ($perusahaan as $p): ?>
                    <option value="<?= $p['id_perusahaan'] ?>">
                        <?= htmlspecialchars($p['kode_saham']) ?> — <?= htmlspecialchars($p['nama_perusahaan']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Tahun Laporan</label>
                <input type="number" name="tahun" min="2010" max="2030" required
                       placeholder="Contoh: 2022" class="form-input">
            </div>
        </div>
    </div>

    <!-- 4 Indicator Sections -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-percent" style="color:#e11d48;"></i> ETR — Tax Aggressiveness Indicator
            </div>
            <div class="form-group">
                <label class="form-label">Laba Sebelum Pajak</label>
                <input type="number" name="laba_sebelum_pajak" required placeholder="0" class="form-input">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Beban Pajak</label>
                <input type="number" name="beban_pajak" required placeholder="0" class="form-input">
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-money-bill-transfer" style="color:#3b82f6;"></i> RPT Ratio — Related Party Receivable
            </div>
            <div class="form-group">
                <label class="form-label">Piutang Pihak Berelasi</label>
                <input type="number" name="piutang_berelasi" required placeholder="0" class="form-input">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Total Piutang</label>
                <input type="number" name="total_piutang" required placeholder="0" class="form-input">
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-building-columns" style="color:#d97706;"></i> DER — Leverage Indicator
            </div>
            <p style="font-size:11px;color:#94a3b8;margin:-8px 0 10px;padding:0 2px;">Debt-to-Equity Ratio standar. Bukan thin capitalization calculation sesuai PMK 169/2015.</p>
            <div class="form-group">
                <label class="form-label">Total Hutang (Liabilitas)</label>
                <input type="number" name="total_hutang" required placeholder="0" class="form-input">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Total Ekuitas</label>
                <input type="number" name="total_ekuitas" required placeholder="0" class="form-input">
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-chart-line" style="color:#16a34a;"></i> Sales Growth
            </div>
            <div class="form-group">
                <label class="form-label">Penjualan Tahun Ini</label>
                <input type="number" name="penjualan_tahun_ini" required placeholder="0" class="form-input">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Penjualan Tahun Lalu</label>
                <input type="number" name="penjualan_tahun_lalu" required placeholder="0" class="form-input">
            </div>
        </div>

    </div>

    <!-- Data Quality Preview -->
    <div id="dataQualityPanel" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px 20px;margin-bottom:18px;">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <i class="fa-solid fa-clipboard-check" style="color:#3b5de7;"></i>
            <span style="font-size:13px;font-weight:700;color:#0f172a;">Data Quality Check</span>
        </div>
        <div id="qualityChecks" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
            <div class="qc-item" data-field="laba_sebelum_pajak"><span class="qc-icon">○</span> Laba Sebelum Pajak</div>
            <div class="qc-item" data-field="beban_pajak"><span class="qc-icon">○</span> Beban Pajak</div>
            <div class="qc-item" data-field="total_piutang"><span class="qc-icon">○</span> Total Piutang</div>
            <div class="qc-item" data-field="piutang_berelasi"><span class="qc-icon">○</span> Piutang Pihak Berelasi</div>
            <div class="qc-item" data-field="total_hutang"><span class="qc-icon">○</span> Total Hutang</div>
            <div class="qc-item" data-field="total_ekuitas"><span class="qc-icon">○</span> Total Ekuitas</div>
            <div class="qc-item" data-field="penjualan_tahun_ini"><span class="qc-icon">○</span> Penjualan Tahun Ini</div>
            <div class="qc-item" data-field="penjualan_tahun_lalu"><span class="qc-icon">○</span> Penjualan Tahun Lalu</div>
        </div>
        <div id="qualityStatus" style="margin-top:12px;padding-top:12px;border-top:1px solid #e2e8f0;font-size:12px;font-weight:600;color:#94a3b8;">
            ○ Lengkapi data untuk memulai kalkulasi
        </div>
    </div>

    <style>
    .qc-item { font-size:12px; color:#64748b; display:flex; align-items:center; gap:6px; padding:4px 0; }
    .qc-icon { font-size:14px; width:18px; text-align:center; }
    .qc-item.ok .qc-icon { color:#16a34a; }
    .qc-item.ok { color:#16a34a; font-weight:600; }
    .qc-item.missing .qc-icon { color:#e11d48; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var fields = ['laba_sebelum_pajak','beban_pajak','total_piutang','piutang_berelasi','total_hutang','total_ekuitas','penjualan_tahun_ini','penjualan_tahun_lalu'];
        function updateQuality() {
            var filled = 0;
            fields.forEach(function(f) {
                var input = document.querySelector('input[name="'+f+'"]');
                var item = document.querySelector('.qc-item[data-field="'+f+'"]');
                if (input && item) {
                    var val = input.value.trim();
                    if (val !== '' && val !== '0') {
                        item.className = 'qc-item ok';
                        item.querySelector('.qc-icon').textContent = '✓';
                        filled++;
                    } else if (val === '0') {
                        item.className = 'qc-item ok';
                        item.querySelector('.qc-icon').textContent = '✓';
                        filled++;
                    } else {
                        item.className = 'qc-item missing';
                        item.querySelector('.qc-icon').textContent = '○';
                    }
                }
            });
            var status = document.getElementById('qualityStatus');
            if (filled === fields.length) {
                status.innerHTML = '<span style="color:#16a34a;">✓ Data siap dihitung (' + filled + '/' + fields.length + ')</span>';
            } else {
                status.innerHTML = '<span style="color:#d97706;">⚠ ' + filled + '/' + fields.length + ' field terisi</span>';
            }
        }
        fields.forEach(function(f) {
            var input = document.querySelector('input[name="'+f+'"]');
            if (input) { input.addEventListener('input', updateQuality); }
        });
    });
    // Automated Financial Data Extraction
    document.getElementById('laporan_file').addEventListener('change', function() {
        var file = this.files[0];
        if (!file) return;

        var formData = new FormData();
        formData.append('laporan_file', file);
        
        // Append CSRF token
        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>';
        if (csrfName && csrfHash) {
            formData.append(csrfName, csrfHash);
        }

        var statusDiv = document.getElementById('import-status');
        statusDiv.style.display = 'block';
        statusDiv.style.backgroundColor = '#dbeafe';
        statusDiv.style.color = '#1e40af';
        statusDiv.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Sedang membaca data dari ' + file.name + '...';

        fetch('/keuangan/extract-data', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                statusDiv.style.backgroundColor = '#dcfce7';
                statusDiv.style.color = '#166534';
                statusDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i> ' + res.message + ' (8/8 indikator ditemukan). Silakan review data di bawah.';
                
                // Auto fill fields
                document.querySelector('input[name="penjualan_tahun_ini"]').value = res.data.penjualan_tahun_ini;
                document.querySelector('input[name="penjualan_tahun_lalu"]').value = res.data.penjualan_tahun_lalu;
                document.querySelector('input[name="laba_sebelum_pajak"]').value = res.data.laba_sebelum_pajak;
                document.querySelector('input[name="beban_pajak"]').value = res.data.beban_pajak;
                document.querySelector('input[name="total_ekuitas"]').value = res.data.total_ekuitas;
                document.querySelector('input[name="total_hutang"]').value = res.data.total_hutang;
                document.querySelector('input[name="total_piutang"]').value = res.data.total_piutang;
                document.querySelector('input[name="piutang_berelasi"]').value = res.data.piutang_berelasi;

                updateQuality(); // Trigger UI update for quality check
            } else {
                statusDiv.style.backgroundColor = '#fee2e2';
                statusDiv.style.color = '#991b1b';
                statusDiv.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> ' + res.message;
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
            statusDiv.style.backgroundColor = '#fee2e2';
            statusDiv.style.color = '#991b1b';
            statusDiv.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Terjadi kesalahan jaringan atau server saat mengekstrak data.';
        });
        
        // Reset file input so user can upload same file again if needed
        this.value = '';
    });
    </script>

    <!-- Info -->
    <div class="info-box" style="margin-bottom:18px;">
        <i class="fa-solid fa-circle-info"></i>
        <p>Setelah disimpan, sistem akan <strong>otomatis menghitung</strong> ETR, RPT Ratio, DER, dan Sales Growth dari data di atas.</p>
    </div>

    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-calculator"></i> Simpan &amp; Kalkulasi
        </button>
        <a href="/keuangan" class="btn btn-ghost">Batal</a>
    </div>
</form>

<?= $this->endSection(); ?>
