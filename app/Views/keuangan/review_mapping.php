<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mx-auto px-4 py-8 max-w-6xl">
    
    <div class="mb-6 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Review Hasil Import</h1>
            <p class="text-slate-500 mt-2">Periksa kembali hasil ekstraksi dan pemetaan (mapping) data dari dokumen.</p>
        </div>
        <div class="text-right">
            <span class="text-sm text-slate-500 block mb-1">Perusahaan</span>
            <span class="font-bold text-indigo-700 text-lg bg-indigo-50 px-3 py-1 rounded-md">
                <?= esc($perusahaan['nama_perusahaan']); ?> (<?= $tahun; ?>)
            </span>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check text-green-500 mr-3"></i>
                <p class="text-green-700 font-medium"><?= session()->getFlashdata('success'); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Raw Data Preview -->
        <div class="lg:col-span-1 bg-slate-50 rounded-2xl border border-slate-200 shadow-sm flex flex-col h-[600px]">
            <div class="p-5 border-b border-slate-200 bg-white rounded-t-2xl flex justify-between items-center">
                <h3 class="font-semibold text-slate-800"><i class="fa-solid fa-file-lines text-slate-400 mr-2"></i> Data Mentah</h3>
                <span class="text-xs bg-slate-100 text-slate-500 px-2 py-1 rounded"><?= count($rawData); ?> item</span>
            </div>
            
            <div class="p-5 overflow-y-auto flex-1">
                <p class="text-xs text-slate-500 mb-4 pb-2 border-b border-slate-200">
                    Sumber: <span class="font-semibold text-slate-700"><?= esc($source_file); ?></span>
                </p>
                <div class="space-y-3">
                    <?php foreach($rawData as $raw): ?>
                    <div class="text-sm p-3 rounded-lg <?= $raw['mapped_to'] ? 'bg-indigo-50 border border-indigo-100' : 'bg-white border border-slate-200' ?>">
                        <div class="text-slate-500 text-xs mb-1 flex justify-between">
                            <span>Hal. <?= $raw['page'] ?></span>
                            <?php if($raw['mapped_to']): ?>
                                <i class="fa-solid fa-link text-indigo-400" title="Terpetakan"></i>
                            <?php endif; ?>
                        </div>
                        <div class="font-medium text-slate-700 mb-1"><?= esc($raw['label']) ?></div>
                        <div class="text-slate-800 font-bold">Rp <?= number_format($raw['value'], 0, ',', '.') ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Mapping & Confirmation -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 h-[600px] flex flex-col">
            <div class="p-5 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-semibold text-slate-800"><i class="fa-solid fa-diagram-project text-indigo-500 mr-2"></i> Pemetaan Akun (Normalized)</h3>
                <?php 
                    $mappedCount = count(array_filter($mapped, fn($item) => $item !== null));
                    $totalRequired = count($mapped);
                ?>
                <span class="text-sm font-medium px-3 py-1 rounded-full <?= $mappedCount == $totalRequired ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' ?>">
                    <?= $mappedCount ?> / <?= $totalRequired ?> Terpetakan
                </span>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <form action="/keuangan/simpan-mapping" method="POST" id="mappingForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_perusahaan" value="<?= $perusahaan['id_perusahaan'] ?>">
                    <input type="hidden" name="tahun" value="<?= $tahun ?>">
                    <input type="hidden" name="bulan" value="<?= $bulan ?>">

                    <?php foreach($rawData as $i => $raw): ?>
                        <input type="hidden" name="raw_data[<?= $i ?>][label]" value="<?= esc($raw['label']) ?>">
                        <input type="hidden" name="raw_data[<?= $i ?>][value]" value="<?= $raw['value'] ?>">
                        <input type="hidden" name="raw_data[<?= $i ?>][mapped_to]" value="<?= $raw['mapped_to'] ?>">
                    <?php endforeach; ?>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-slate-200">
                                <th class="pb-3 pt-2 text-sm font-semibold text-slate-600">Kebutuhan Analitik</th>
                                <th class="pb-3 pt-2 text-sm font-semibold text-slate-600 w-1/2">Data Mentah Terpilih</th>
                                <th class="pb-3 pt-2 text-sm font-semibold text-slate-600 text-right w-1/4">Nilai (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php 
                                $fields = [
                                    'profit_before_tax' => 'Laba Sebelum Pajak',
                                    'tax_expense' => 'Beban Pajak',
                                    'related_party_receivables' => 'Piutang Berelasi',
                                    'total_receivables' => 'Total Piutang',
                                    'total_liabilities' => 'Total Liabilitas',
                                    'total_equity' => 'Total Ekuitas',
                                    'revenue_current' => 'Penjualan Tahun Ini',
                                    'revenue_previous' => 'Penjualan Tahun Lalu'
                                ];

                                foreach($fields as $key => $label): 
                                    $isMapped = isset($mapped[$key]) && $mapped[$key] !== null;
                                    $value = $isMapped ? $mapped[$key]['value'] : '';
                                    $rawLabel = $isMapped ? $mapped[$key]['label'] : 'Tidak ditemukan';
                            ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <?php if($isMapped): ?>
                                            <i class="fa-solid fa-circle-check text-green-500 mr-3"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-circle-exclamation text-amber-500 mr-3"></i>
                                        <?php endif; ?>
                                        <span class="font-medium text-slate-700"><?= $label ?></span>
                                    </div>
                                    <div class="text-xs text-slate-400 ml-7 mt-1 font-mono"><?= $key ?></div>
                                </td>
                                <td class="py-4 px-2">
                                    <?php if($isMapped): ?>
                                        <span class="text-sm bg-indigo-50 text-indigo-700 border border-indigo-100 px-2 py-1 rounded inline-block max-w-[200px] truncate" title="<?= esc($rawLabel) ?>">
                                            <?= esc($rawLabel) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-sm text-slate-400 italic">Data kosong (Harap input manual)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 text-right">
                                    <input type="number" name="<?= $key ?>" value="<?= $value ?>" class="w-full text-right text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 <?= !$isMapped ? 'bg-amber-50 border-amber-300' : '' ?>" required>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <div class="p-5 border-t border-slate-200 bg-slate-50 rounded-b-2xl flex justify-between items-center">
                <p class="text-sm text-slate-500"><i class="fa-solid fa-circle-info mr-1"></i> Anda dapat mengoreksi nilai secara manual jika diperlukan.</p>
                <button type="button" onclick="document.getElementById('mappingForm').submit();" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors flex items-center shadow-sm">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Konfirmasi & Simpan
                </button>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection(); ?>
