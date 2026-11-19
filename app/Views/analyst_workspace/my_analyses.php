<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">My Analyses</h1>
        <p class="text-gray-500 mt-2">Manage your drafted and submitted tax analysis reports</p>
    </div>
    <a href="<?= base_url('workspace') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all shadow-md shadow-blue-200 flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> New Analysis
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
    <div class="relative w-full md:w-96">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
            <i class="fa-solid fa-search"></i>
        </div>
        <input type="text" id="searchInput" class="pl-10 w-full rounded-xl border-gray-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all text-sm" placeholder="Search report, client, year...">
    </div>
    <div class="flex gap-2 flex-wrap" id="statusFilters">
        <button type="button" class="filter-btn active px-4 py-2 rounded-lg text-xs font-bold bg-gray-800 text-white transition-all" data-filter="all">All</button>
        <button type="button" class="filter-btn px-4 py-2 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all" data-filter="draft">Draft</button>
        <button type="button" class="filter-btn px-4 py-2 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all" data-filter="submitted">Submitted</button>
        <button type="button" class="filter-btn px-4 py-2 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all" data-filter="approved">Approved</button>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="analysesTable">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="py-4 px-6 font-semibold">Report</th>
                    <th class="py-4 px-6 font-semibold">Client</th>
                    <th class="py-4 px-6 font-semibold text-center">Tahun</th>
                    <th class="py-4 px-6 font-semibold text-center">Status</th>
                    <th class="py-4 px-6 font-semibold">Updated</th>
                    <th class="py-4 px-6 font-semibold text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($reports)): ?>
                    <tr id="emptyRow">
                        <td colspan="6" class="py-12 px-6 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-folder-open text-2xl text-gray-300"></i>
                                </div>
                                <p>You haven't created any analyses yet.</p>
                                <a href="<?= base_url('workspace') ?>" class="text-blue-600 hover:underline mt-2 text-sm font-semibold">Start your first analysis</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reports as $r): ?>
                        <tr class="analysis-row hover:bg-gray-50 transition-colors" data-status="<?= strtolower($r['status']) ?>" data-search="<?= strtolower($r['judul'] . ' ' . $r['kode_saham'] . ' ' . $r['nama_perusahaan'] . ' ' . $r['tahun']) ?>">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-800"><?= htmlspecialchars($r['judul']) ?></div>
                                <?php
                                $typeLabels = [
                                    'tax_indicator' => 'Tax Indicator',
                                    'transfer_pricing' => 'Transfer Pricing',
                                    'compliance' => 'Compliance'
                                ];
                                $type = $typeLabels[$r['jenis_analisis']] ?? 'General';
                                ?>
                                <div class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider font-semibold"><?= $type ?></div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-gray-700"><?= htmlspecialchars($r['kode_saham']) ?></div>
                                <div class="text-xs text-gray-500 truncate w-32" title="<?= htmlspecialchars($r['nama_perusahaan']) ?>"><?= htmlspecialchars($r['nama_perusahaan']) ?></div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-gray-100 text-gray-700 px-2.5 py-1 rounded-md text-xs font-bold"><?= $r['tahun'] ?></span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php 
                                    $statusClass = 'bg-gray-100 text-gray-700 border-gray-200';
                                    $icon = 'fa-file';
                                    
                                    if ($r['status'] == 'draft') {
                                        $statusClass = 'bg-slate-100 text-slate-700 border-slate-200';
                                        $icon = 'fa-pen-ruler';
                                    } elseif ($r['status'] == 'submitted') {
                                        $statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                        $icon = 'fa-paper-plane';
                                    } elseif ($r['status'] == 'revision') {
                                        $statusClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                        $icon = 'fa-rotate-left';
                                    } elseif ($r['status'] == 'approved') {
                                        $statusClass = 'bg-green-50 text-green-700 border-green-200';
                                        $icon = 'fa-check-double';
                                    } elseif ($r['status'] == 'completed') {
                                        $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        $icon = 'fa-trophy';
                                    }
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] uppercase tracking-wide font-bold border <?= $statusClass ?>">
                                    <i class="fa-solid <?= $icon ?>"></i> <?= ucfirst($r['status']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-gray-500 whitespace-nowrap">
                                <?= date('d M Y', strtotime($r['updated_at'])) ?>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <?php if (in_array($r['status'], ['draft', 'revision'])): ?>
                                    <a href="<?= base_url('workspace/edit/'.$r['id']) ?>" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all text-xs font-bold" title="Edit">
                                        <i class="fa-solid fa-pen mr-1"></i> Edit
                                    </a>
                                <?php else: ?>
                                    <a href="#" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-200 transition-all text-xs font-bold" title="View (Read-only)">
                                        <i class="fa-solid fa-eye mr-1"></i> View
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('.analysis-row');
    
    let currentFilter = 'all';
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;
        
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const searchData = row.getAttribute('data-search');
            
            const matchesSearch = searchData.includes(searchTerm);
            const matchesStatus = (currentFilter === 'all' || status === currentFilter);
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Handle empty state if all rows are filtered out
        let emptyMsg = document.getElementById('filterEmptyMsg');
        if (visibleCount === 0 && rows.length > 0) {
            if (!emptyMsg) {
                const tbody = document.querySelector('#analysesTable tbody');
                const tr = document.createElement('tr');
                tr.id = 'filterEmptyMsg';
                tr.innerHTML = '<td colspan="6" class="py-12 px-6 text-center text-gray-500 italic">No analyses found matching your filters.</td>';
                tbody.appendChild(tr);
            } else {
                emptyMsg.style.display = '';
            }
        } else if (emptyMsg) {
            emptyMsg.style.display = 'none';
        }
    }
    
    searchInput.addEventListener('input', filterTable);
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            filterBtns.forEach(b => {
                b.classList.remove('bg-gray-800', 'text-white');
                b.classList.add('bg-gray-100', 'text-gray-600');
            });
            this.classList.remove('bg-gray-100', 'text-gray-600');
            this.classList.add('bg-gray-800', 'text-white');
            
            currentFilter = this.getAttribute('data-filter');
            filterTable();
        });
    });
});
</script>

<?= $this->endSection() ?>
