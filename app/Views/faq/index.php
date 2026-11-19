<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="ph">
    <div class="ph-left">
        <h1>Frequently Asked Questions</h1>
        <p>Pertanyaan yang sering diajukan tentang penggunaan Fynca Analytics.</p>
    </div>
</div>

<div style="display:flex;flex-direction:column;gap:12px;">

    <!-- FAQ 1 -->
    <div class="card faq-card">
        <button class="faq-toggle" onclick="toggleFaq(this)">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(79,70,229,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-circle-question" style="color:#4f46e5;font-size:14px;"></i>
                </div>
                <span style="font-weight:600;font-size:14px;color:#0f172a;text-align:left;">Apa itu Effective Tax Rate (ETR)?</span>
            </div>
            <i class="fa-solid fa-chevron-down faq-icon"></i>
        </button>
        <div class="faq-answer">
            <p>ETR (Effective Tax Rate) adalah rasio yang mengukur besarnya pajak efektif yang dibayar perusahaan relatif terhadap laba sebelum pajak. ETR dihitung dengan rumus: <strong>ETR = Beban Pajak / Laba Sebelum Pajak</strong>. Nilai ETR di bawah 22% (tarif PPh Badan) mengindikasikan adanya praktik perencanaan pajak yang agresif.</p>
        </div>
    </div>

    <!-- FAQ 2 -->
    <div class="card faq-card">
        <button class="faq-toggle" onclick="toggleFaq(this)">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(79,70,229,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-circle-question" style="color:#4f46e5;font-size:14px;"></i>
                </div>
                <span style="font-weight:600;font-size:14px;color:#0f172a;text-align:left;">Apa yang dimaksud dengan Thin Capitalization?</span>
            </div>
            <i class="fa-solid fa-chevron-down faq-icon"></i>
        </button>
        <div class="faq-answer">
            <p>Thin Capitalization adalah kondisi di mana perusahaan memiliki proporsi hutang yang sangat tinggi dibandingkan modal (ekuitas). Dalam konteks perpajakan, thin capitalization diukur menggunakan Debt-to-Equity Ratio (DER). Berdasarkan <strong>PMK No. 169/PMK.010/2015</strong>, batas DER yang wajar adalah <strong>4:1</strong>, artinya hutang tidak boleh melebihi 4 kali ekuitas.</p>
        </div>
    </div>

    <!-- FAQ 3 -->
    <div class="card faq-card">
        <button class="faq-toggle" onclick="toggleFaq(this)">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(79,70,229,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-circle-question" style="color:#4f46e5;font-size:14px;"></i>
                </div>
                <span style="font-weight:600;font-size:14px;color:#0f172a;text-align:left;">Bagaimana cara menginput data laporan keuangan?</span>
            </div>
            <i class="fa-solid fa-chevron-down faq-icon"></i>
        </button>
        <div class="faq-answer">
            <p>Untuk menginput data laporan keuangan:</p>
            <ol style="margin:8px 0;padding-left:20px;color:#64748b;font-size:13px;line-height:1.8;">
                <li>Pastikan perusahaan (klien) sudah terdaftar di menu <strong>Daftar Klien</strong>.</li>
                <li>Buka menu <strong>Input Laporan</strong> di sidebar.</li>
                <li>Klik tombol <strong>Tambah Data Keuangan</strong>.</li>
                <li>Pilih perusahaan dan tahun, lalu isi data neraca dan laba rugi.</li>
                <li>Sistem akan menghitung rasio ETR, RPT, DER, dan Sales Growth secara otomatis.</li>
            </ol>
        </div>
    </div>

    <!-- FAQ 4 -->
    <div class="card faq-card">
        <button class="faq-toggle" onclick="toggleFaq(this)">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(79,70,229,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-circle-question" style="color:#4f46e5;font-size:14px;"></i>
                </div>
                <span style="font-weight:600;font-size:14px;color:#0f172a;text-align:left;">Apa arti warna badge pada hasil analisis?</span>
            </div>
            <i class="fa-solid fa-chevron-down faq-icon"></i>
        </button>
        <div class="faq-answer">
            <div style="display:flex;flex-direction:column;gap:8px;margin-top:4px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="badge badge-green">HIJAU</span>
                    <span style="font-size:13px;color:#64748b;">Nilai berada dalam batas aman / normal.</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="badge badge-amber">KUNING</span>
                    <span style="font-size:13px;color:#64748b;">Nilai memerlukan perhatian / moderate risk.</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="badge badge-rose">MERAH</span>
                    <span style="font-size:13px;color:#64748b;">Nilai berisiko tinggi / indikasi agresivitas pajak.</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="badge badge-blue">BIRU</span>
                    <span style="font-size:13px;color:#64748b;">Informasi regulasi / batas parameter PMK.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ 5 -->
    <div class="card faq-card">
        <button class="faq-toggle" onclick="toggleFaq(this)">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(79,70,229,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-circle-question" style="color:#4f46e5;font-size:14px;"></i>
                </div>
                <span style="font-weight:600;font-size:14px;color:#0f172a;text-align:left;">Apakah parameter threshold bisa diubah?</span>
            </div>
            <i class="fa-solid fa-chevron-down faq-icon"></i>
        </button>
        <div class="faq-answer">
            <p>Ya, parameter threshold dapat dikonfigurasi melalui menu <strong>Parameter Rasio</strong> di sidebar (bagian Master Data). Anda dapat menambah, mengedit, atau menghapus parameter. Perubahan parameter akan mempengaruhi klasifikasi warna dan badge pada seluruh halaman analisis dan laporan.</p>
        </div>
    </div>

    <!-- FAQ 6 -->
    <div class="card faq-card">
        <button class="faq-toggle" onclick="toggleFaq(this)">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(79,70,229,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-circle-question" style="color:#4f46e5;font-size:14px;"></i>
                </div>
                <span style="font-weight:600;font-size:14px;color:#0f172a;text-align:left;">Bagaimana cara membaca laporan analisis?</span>
            </div>
            <i class="fa-solid fa-chevron-down faq-icon"></i>
        </button>
        <div class="faq-answer">
            <p>Laporan analisis menampilkan ringkasan komprehensif yang terdiri dari:</p>
            <ul style="margin:8px 0;padding-left:20px;color:#64748b;font-size:13px;line-height:1.8;">
                <li><strong>Tabel Data:</strong> Seluruh rasio keuangan per klien per tahun.</li>
                <li><strong>Grafik Visualisasi:</strong> Chart perbandingan antar klien dan tren tahunan.</li>
                <li><strong>Badge Klasifikasi:</strong> Indikator warna berdasarkan parameter threshold.</li>
                <li><strong>Insight Box:</strong> Penjelasan interpretasi dan rekomendasi otomatis.</li>
            </ul>
        </div>
    </div>

</div>

<!-- Help Box -->
<div class="insight-box" style="margin-top:20px;">
    <div class="insight-icon blue"><i class="fa-solid fa-headset"></i></div>
    <div class="insight-content">
        <h4>Butuh Bantuan Lebih?</h4>
        <p>Jika Anda memiliki pertanyaan yang belum terjawab, silakan hubungi administrator sistem atau lihat halaman <a href="/panduan" style="color:#4f46e5;font-weight:600;text-decoration:none;">Dokumentasi & Metrik</a> untuk referensi teknis yang lebih lengkap.</p>
    </div>
</div>

<script>
function toggleFaq(btn) {
    const card = btn.closest('.faq-card');
    const answer = card.querySelector('.faq-answer');
    const icon = btn.querySelector('.faq-icon');
    const isOpen = card.classList.contains('open');

    // Close all other FAQs
    document.querySelectorAll('.faq-card.open').forEach(function(openCard) {
        if (openCard !== card) {
            openCard.classList.remove('open');
            openCard.querySelector('.faq-answer').style.maxHeight = '0';
            openCard.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
        }
    });

    if (isOpen) {
        card.classList.remove('open');
        answer.style.maxHeight = '0';
        icon.style.transform = 'rotate(0deg)';
    } else {
        card.classList.add('open');
        answer.style.maxHeight = answer.scrollHeight + 'px';
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>

<?= $this->endSection(); ?>
