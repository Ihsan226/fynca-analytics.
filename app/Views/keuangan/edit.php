<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Workspace Analisis — Laporan Keuangan Tahun <?= $laporan['tahun'] ?></h1>
        <p>Kelola data keuangan dan perbarui status pekerjaan analisis untuk klien ini.</p>
    </div>
    <a href="/keuangan" class="btn btn-ghost">
        <i class="fa-solid fa-arrow-left" style="font-size:11px;"></i> Kembali
    </a>
</div>

<form action="/keuangan/update/<?= $laporan['id_laporan'] ?>" method="POST">
    <?= csrf_field() ?>
    <input type="hidden" name="id_perusahaan" value="<?= $laporan['id_perusahaan'] ?>">
    <input type="hidden" name="tahun" value="<?= $laporan['tahun'] ?>">

    <!-- Notice -->
    <div class="info-box" style="margin-bottom:18px;">
        <i class="fa-solid fa-circle-info"></i>
        <p>Sedang mengerjakan data <strong>Tahun <?= $laporan['tahun'] ?></strong>.
        ID Perusahaan dan Tahun tidak dapat diubah untuk menjaga integritas data.</p>
    </div>

    <!-- Status Section -->
    <div class="form-section" style="margin-bottom:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0; font-size:15px; color:#1f2937; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-bars-progress"></i> Status Pekerjaan
                </h3>
                <p style="margin:4px 0 0; font-size:12px; color:#6b7280;">Update status alur kerja analisis Anda untuk laporan ini.</p>
            </div>
            <div>
                <?php
                    $statusList = ['Draft', 'Processing', 'Ready for Analysis', 'Analysis Completed', 'Report Generated'];
                    $currentStatus = $laporan['status'] ?? 'Draft';
                ?>
                <select name="status" class="form-input" style="width:200px; margin:0;">
                    <?php foreach ($statusList as $s): ?>
                    <option value="<?= $s ?>" <?= $currentStatus === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Import File Section -->
    <div class="form-section" style="margin-bottom:20px; background: #eff6ff; border: 1px dashed #3b82f6;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0; font-size:15px; color:#1e40af; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Auto-Fill (Import Laporan Keuangan)
                </h3>
                <p style="margin:4px 0 0; font-size:12px; color:#3b82f6;">Upload laporan keuangan PDF/Excel untuk me-replace angka di bawah secara otomatis.</p>
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

    <!-- 4 Indicator Sections -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">

        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-percent" style="color:#e11d48;"></i> ETR — Tax Aggressiveness Indicator
            </div>
            <div class="form-group">
                <label class="form-label">Laba Sebelum Pajak</label>
                <input type="number" name="laba_sebelum_pajak"
                       value="<?= $laporan['laba_sebelum_pajak'] ?>" required class="form-input">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Beban Pajak</label>
                <input type="number" name="beban_pajak"
                       value="<?= $laporan['beban_pajak'] ?>" required class="form-input">
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-money-bill-transfer" style="color:#3b82f6;"></i> RPT Ratio — Related Party Receivable
            </div>
            <div class="form-group">
                <label class="form-label">Piutang Pihak Berelasi</label>
                <input type="number" name="piutang_berelasi"
                       value="<?= $laporan['piutang_berelasi'] ?>" required class="form-input">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Total Piutang</label>
                <input type="number" name="total_piutang"
                       value="<?= $laporan['total_piutang'] ?>" required class="form-input">
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-building-columns" style="color:#d97706;"></i> DER — Leverage Indicator
            </div>
            <p style="font-size:11px;color:#94a3b8;margin:-8px 0 10px;padding:0 2px;">Debt-to-Equity Ratio standar. Bukan thin capitalization calculation sesuai PMK 169/2015.</p>
            <div class="form-group">
                <label class="form-label">Total Hutang (Liabilitas)</label>
                <input type="number" name="total_hutang"
                       value="<?= $laporan['total_hutang'] ?>" required class="form-input">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Total Ekuitas</label>
                <input type="number" name="total_ekuitas"
                       value="<?= $laporan['total_ekuitas'] ?>" required class="form-input">
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-chart-line" style="color:#16a34a;"></i> Sales Growth
            </div>
            <div class="form-group">
                <label class="form-label">Penjualan Tahun Ini</label>
                <input type="number" name="penjualan_tahun_ini"
                       value="<?= $laporan['penjualan_tahun_ini'] ?>" required class="form-input">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Penjualan Tahun Lalu</label>
                <input type="number" name="penjualan_tahun_lalu"
                       value="<?= $laporan['penjualan_tahun_lalu'] ?>" required class="form-input">
            </div>
        </div>

    </div>

    <div style="display:flex;gap:10px;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-rotate"></i> Update &amp; Kalkulasi Ulang
        </button>
        <a href="/keuangan" class="btn btn-ghost">Batal</a>
    </div>
</form>

<script>
// Automated Financial Data Extraction
document.getElementById('laporan_file').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;

    var formData = new FormData();
    formData.append('laporan_file', file);

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
            statusDiv.innerHTML = '<i class="fa-solid fa-check-circle"></i> ' + res.message + ' (8/8 indikator ditemukan). Angka di bawah telah diperbarui.';
            
            // Auto fill fields
            document.querySelector('input[name="penjualan_tahun_ini"]').value = res.data.penjualan_tahun_ini;
            document.querySelector('input[name="penjualan_tahun_lalu"]').value = res.data.penjualan_tahun_lalu;
            document.querySelector('input[name="laba_sebelum_pajak"]').value = res.data.laba_sebelum_pajak;
            document.querySelector('input[name="beban_pajak"]').value = res.data.beban_pajak;
            document.querySelector('input[name="total_ekuitas"]').value = res.data.total_ekuitas;
            document.querySelector('input[name="total_hutang"]').value = res.data.total_hutang;
            document.querySelector('input[name="total_piutang"]').value = res.data.total_piutang;
            document.querySelector('input[name="piutang_berelasi"]').value = res.data.piutang_berelasi;
            
            // Note: edit.php currently doesn't have the updateQuality() function, so we don't call it here.
        } else {
            statusDiv.style.backgroundColor = '#fee2e2';
            statusDiv.style.color = '#991b1b';
            statusDiv.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> ' + res.message;
        }
    })
    .catch(err => {
        statusDiv.style.backgroundColor = '#fee2e2';
        statusDiv.style.color = '#991b1b';
        statusDiv.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Terjadi kesalahan jaringan saat mengekstrak data.';
    });
    
    // Reset file input so user can upload same file again if needed
    this.value = '';
});
</script>

<?= $this->endSection(); ?>
