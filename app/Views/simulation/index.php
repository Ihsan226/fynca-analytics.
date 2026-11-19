<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">
        <i class="fa-solid fa-calculator text-indigo-600 mr-2"></i> Tax Risk Simulation
    </h1>
    <p class="text-gray-500 mt-2">Test hypothetical financial data to see the resulting tax risk indicators</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Input Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 h-fit">
        <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">Simulation Parameters</h2>
        
        <form id="sim-form" class="space-y-6">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ETR Ratio (%)</label>
                    <input type="number" step="0.01" id="sim_etr" placeholder="e.g., 22.0" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50">
                    <p class="text-xs text-gray-400 mt-1">Normal is around 22%</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">RPT Ratio (%)</label>
                    <input type="number" step="0.01" id="sim_rpt" placeholder="e.g., 15.0" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50">
                    <p class="text-xs text-gray-400 mt-1">Transactions with Related Parties</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">DER Ratio (X)</label>
                    <input type="number" step="0.01" id="sim_der" placeholder="e.g., 3.5" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50">
                    <p class="text-xs text-gray-400 mt-1">Max limit usually 4.0</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sales Growth (%)</label>
                    <input type="number" step="0.01" id="sim_growth" placeholder="e.g., -5.0" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-all px-4 py-3 bg-gray-50">
                    <p class="text-xs text-gray-400 mt-1">Growth year over year</p>
                </div>
            </div>

            <hr class="border-gray-100">
            
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Company Context Flags</label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" id="sim_has_rpt" checked class="rounded text-indigo-600 focus:ring-indigo-500 w-5 h-5 border-gray-300">
                    <span class="text-sm text-gray-600">Has Related Party Transactions</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="checkbox" id="sim_has_debt" checked class="rounded text-indigo-600 focus:ring-indigo-500 w-5 h-5 border-gray-300">
                    <span class="text-sm text-gray-600">Has Interest-bearing Debt</span>
                </label>
            </div>

            <button type="button" id="btn-simulate" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2 mt-4">
                <i class="fa-solid fa-bolt"></i> Run Simulation
            </button>
        </form>
    </div>

    <!-- Output / Result -->
    <div>
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-xl border border-slate-700 p-8 text-white sticky top-6">
            <h2 class="text-xl font-bold mb-6 border-b border-slate-700 pb-3 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-green-400"></i> Simulation Results
            </h2>
            
            <div id="sim-results" class="hidden space-y-4">
                <!-- Template for result items -->
            </div>

            <div id="sim-empty" class="text-center py-12 text-slate-500">
                <i class="fa-solid fa-gears text-5xl mb-4 text-slate-700"></i>
                <p>Input parameters and click 'Run Simulation' to see the risk analysis.</p>
            </div>
            
            <div id="sim-loading" class="hidden text-center py-12 text-indigo-400">
                <i class="fa-solid fa-circle-notch fa-spin text-4xl mb-4"></i>
                <p>Calculating tax risk...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-simulate').addEventListener('click', function() {
    const etr = document.getElementById('sim_etr').value || 0;
    const rpt = document.getElementById('sim_rpt').value || 0;
    const der = document.getElementById('sim_der').value || 0;
    const growth = document.getElementById('sim_growth').value || 0;
    const has_rpt = document.getElementById('sim_has_rpt').checked ? 1 : 0;
    const has_debt = document.getElementById('sim_has_debt').checked ? 1 : 0;

    const payload = {
        etr: parseFloat(etr) / 100, // backend expects decimal format for engine if inputted as % we divide by 100
        rpt: parseFloat(rpt) / 100,
        der: parseFloat(der), // DER is multiplier usually
        growth: parseFloat(growth) / 100,
        has_related_party: has_rpt,
        has_rpt_transaction: has_rpt,
        has_interest_debt: has_debt
    };

    document.getElementById('sim-empty').classList.add('hidden');
    document.getElementById('sim-results').classList.add('hidden');
    document.getElementById('sim-loading').classList.remove('hidden');

    fetch('<?= base_url('simulation/calculate') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('sim-loading').classList.add('hidden');
        if (data.success) {
            renderResults(data.explanations);
        } else {
            alert('Simulation failed.');
        }
    })
    .catch(error => {
        document.getElementById('sim-loading').classList.add('hidden');
        document.getElementById('sim-empty').classList.remove('hidden');
        alert('An error occurred during simulation.');
        console.error(error);
    });
});

function renderResults(explanations) {
    const resultsContainer = document.getElementById('sim-results');
    resultsContainer.innerHTML = '';
    
    const renderCard = (title, data, valDisplay) => {
        let icon = 'fa-check-circle text-green-400';
        let bg = 'bg-slate-800 border-slate-700';
        
        if (data.level === 'high' || data.level === 'negative') {
            icon = 'fa-exclamation-circle text-red-400';
            bg = 'bg-red-900/30 border-red-500/30';
        } else if (data.level === 'low' || data.level === 'positive') {
            icon = 'fa-check-circle text-green-400';
            bg = 'bg-green-900/30 border-green-500/30';
        } else {
            icon = 'fa-minus-circle text-yellow-400';
            bg = 'bg-yellow-900/30 border-yellow-500/30';
        }
        
        return `
            <div class="p-4 rounded-xl border ${bg} transition-all">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-slate-200 flex items-center gap-2">
                        <i class="fa-solid ${icon}"></i> ${title}
                    </h3>
                    <span class="text-xs font-mono bg-slate-900 px-2 py-1 rounded text-slate-300 border border-slate-700">
                        Level: <span class="uppercase font-bold text-white">${data.level}</span>
                    </span>
                </div>
                <p class="text-sm text-slate-300">${data.explanation}</p>
            </div>
        `;
    };

    resultsContainer.innerHTML += renderCard('Effective Tax Rate (ETR)', explanations.etr);
    resultsContainer.innerHTML += renderCard('Related Party Trans. (RPT)', explanations.rpt);
    resultsContainer.innerHTML += renderCard('Debt-to-Equity (DER)', explanations.der);
    resultsContainer.innerHTML += renderCard('Sales Growth', explanations.growth);

    resultsContainer.classList.remove('hidden');
}
</script>
<?= $this->endSection() ?>
