<?php $role = session()->get('role') ?? 'admin'; ?>

<!-- Mobile Sidebar Backdrop/Overlay -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300 md:hidden"></div>

<aside id="app-sidebar" class="
    w-72 md:w-[260px] flex flex-col z-50 md:z-20 overflow-y-auto shrink-0 
    dark-sidebar
    
    fixed md:relative inset-y-0 left-0
    -translate-x-full md:translate-x-0
    transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)]
    h-screen md:h-auto
">
    
    <!-- Mobile Close Button -->
    <div class="md:hidden flex items-center justify-between px-4 pt-4 pb-1">
        <span class="text-xs font-bold text-white/40 uppercase tracking-wider">Menu</span>
        <button id="btn-close-sidebar" class="w-9 h-9 rounded-xl bg-white/10 hover:bg-red-500/30 text-white/60 hover:text-red-300 flex items-center justify-center transition-all duration-200 active:scale-90" aria-label="Close Menu">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Brand Card -->
    <div class="p-5 pb-2">
        <?php
            $brandLabel = 'Enterprise Tax Solution';
            if ($role === 'analyst') {
                $brandLabel = 'Analyst Console';
            } elseif ($role === 'owner') {
                $brandLabel = 'Executive Console';
            } elseif ($role === 'staff') {
                $brandLabel = 'Staff Portal';
            } elseif ($role === 'bendahara') {
                $brandLabel = 'Finance Console';
            }
        ?>
        <div class="sidebar-brand bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-4 flex items-center gap-3 relative overflow-hidden shadow-lg shadow-purple-900/30">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm text-white flex items-center justify-center relative z-10">
                <i class="fa-solid fa-chart-column text-lg"></i>
            </div>
            <div class="sidebar-brand-copy relative z-10">
                <h2 class="text-sm font-bold text-white leading-tight">Fynca Analytics</h2>
                <p class="text-[10px] text-white/70 mt-0.5"><?= $brandLabel ?></p>
            </div>
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-white/10 rounded-full z-0"></div>
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-white/5 rounded-full z-0"></div>
        </div>
    </div>

    <!-- Menu Navigation -->
    <nav class="flex-1 px-4 py-3 space-y-0.5 overflow-y-auto sidebar-nav-dark">

        <?php
            // Dark sidebar active/hover classes
            $activeClass = 'sidebar-link-active';
            $hoverClass  = 'sidebar-link-default';
        ?>

        <?php if ($role === 'admin'): ?>
        <!-- ═══════════════════════════════════════ -->
        <!-- ═══ ADMIN SIDEBAR ═══ -->
        <!-- ═══════════════════════════════════════ -->

        <!-- OVERVIEW -->
        <p class="sidebar-section-label">Overview</p>
        
        <a href="/dashboard" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('dashboard')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
        </a>



        <!-- CONFIGURATION -->
        <p class="sidebar-section-label mt-5">
            <i class="fa-solid fa-gear text-[8px] mr-1"></i> Configuration
        </p>

        <a href="/sektor" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('sektor*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-industry w-5 text-center"></i> Sector Profiles
        </a>

        <a href="/parameter-rasio" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('parameter-rasio*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-sliders w-5 text-center"></i> Tax Parameters
            <span class="ml-auto text-[9px] font-bold bg-amber-500/20 text-amber-300 px-1.5 py-0.5 rounded-full">Tax</span>
        </a>

        <a href="/regulasi" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('regulasi*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-scale-balanced w-5 text-center"></i> Regulatory References
            <span class="ml-auto text-[9px] font-bold bg-blue-500/20 text-blue-300 px-1.5 py-0.5 rounded-full">Ref</span>
        </a>

        <!-- SYSTEM -->
        <p class="sidebar-section-label mt-5">
            <i class="fa-solid fa-shield-halved text-[8px] mr-1"></i> System
        </p>

        <a href="/aktivitas" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('aktivitas*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Activity Logs
        </a>

        <a href="/broadcasts" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('broadcasts*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-bullhorn w-5 text-center"></i> Broadcasts
        </a>

        <a href="/backup" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('backup*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-database w-5 text-center"></i> Backup & Restore
        </a>

        <a href="/tickets" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('tickets*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-headset w-5 text-center"></i> Support Tickets
        </a>

        <a href="/panduan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('panduan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-book-open w-5 text-center"></i> Documentation
        </a>

        <a href="/faq" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('faq*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-comments w-5 text-center"></i> FAQ
        </a>

        <!-- TENANTS & ORGANIZATION -->
        <p class="sidebar-section-label mt-5">
            <i class="fa-solid fa-sitemap text-[8px] mr-1"></i> Tenants & Organization
        </p>

        <a href="/perusahaan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('perusahaan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-building w-5 text-center"></i> Client Companies
        </a>

        <a href="/pengguna" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('pengguna*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-users-gear w-5 text-center"></i> Users & Roles
        </a>

        <a href="/pengaturan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('pengaturan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-gear w-5 text-center"></i> Settings
        </a>


        <?php elseif ($role === 'analyst'): ?>
        <!-- ═══════════════════════════════════════ -->
        <!-- ═══ ANALYST SIDEBAR ═══ -->
        <!-- ═══════════════════════════════════════ -->

        <!-- OVERVIEW -->
        <p class="sidebar-section-label">Overview</p>
        
        <a href="/dashboard" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('dashboard')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
        </a>

        <!-- DATA -->
        <p class="sidebar-section-label mt-5">Data</p>
        
        <a href="/perusahaan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('perusahaan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-building w-5 text-center"></i> Clients
        </a>
        


        <!-- ANALYSIS -->
        <p class="sidebar-section-label mt-5">Analysis</p>
        
        <a href="/analisis" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('analisis*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-chart-pie w-5 text-center"></i> Tax Indicators
        </a>

        <a href="/workspace" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('workspace*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-microscope w-5 text-center"></i> Analysis Workspace
            <span class="ml-auto text-[9px] font-bold bg-blue-500/20 text-blue-300 px-1.5 py-0.5 rounded-full">New</span>
        </a>

        <a href="/simulation" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('simulation*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-calculator w-5 text-center"></i> Simulation
        </a>

        <a href="/laporan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('laporan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-lines w-5 text-center"></i> Analysis Reports
        </a>

        <!-- WORKFLOW & REPORTS -->
        <p class="sidebar-section-label mt-5">Workflow & Reports</p>

        <a href="/my-analyses" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('my-analyses*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-clipboard-list w-5 text-center"></i> My Analyses
        </a>

        <a href="/review-status" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('review-status*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-arrows-rotate w-5 text-center"></i> Review Status
        </a>
        
        <!-- STAFF FINANCE -->
        <p class="sidebar-section-label mt-5">My Finance</p>
        <a href="/staff/claims" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('staff/claims*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-money-bill-transfer w-5 text-center"></i> Claims
        </a>
        <a href="/staff/payslip" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('staff/payslip*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Payslip
        </a>

        <!-- REFERENCE -->
        <p class="sidebar-section-label mt-5">Reference</p>

        <a href="/sektor" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('sektor*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-industry w-5 text-center"></i> Sector Profiles
        </a>

        <a href="/parameter-rasio" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('parameter-rasio*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-sliders w-5 text-center"></i> Tax Parameters
            <span class="ml-auto text-[9px] font-bold bg-white/10 text-white/50 px-1.5 py-0.5 rounded-full">Read</span>
        </a>

        <a href="/regulasi" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('regulasi*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-scale-balanced w-5 text-center"></i> Regulatory References
            <span class="ml-auto text-[9px] font-bold bg-white/10 text-white/50 px-1.5 py-0.5 rounded-full">Read</span>
        </a>

        <!-- (Replaced workflow section previously in reference) -->

        <!-- SYSTEM -->
        <p class="sidebar-section-label mt-5">System</p>

        <a href="/panduan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('panduan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-book-open w-5 text-center"></i> Documentation
        </a>

        <a href="/faq" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('faq*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-comments w-5 text-center"></i> FAQ
        </a>


        <?php elseif ($role === 'staff'): ?>
        <!-- ═══════════════════════════════════════ -->
        <!-- ═══ STAFF SIDEBAR ═══ -->
        <!-- ═══════════════════════════════════════ -->

        <!-- OVERVIEW -->
        <p class="sidebar-section-label">Overview</p>
        
        <a href="/dashboard" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('dashboard')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-house w-5 text-center"></i> Dashboard
        </a>

        <!-- DATA INPUT -->
        <p class="sidebar-section-label mt-5">Data Input</p>
        
        <a href="/perusahaan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('perusahaan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-building w-5 text-center"></i> Clients
        </a>

        <a href="/keuangan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('keuangan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Financial Reports
        </a>

        <!-- STAFF FINANCE -->
        <p class="sidebar-section-label mt-5">My Finance</p>
        <a href="/staff/claims" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('staff/claims*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-money-bill-transfer w-5 text-center"></i> Claims
        </a>
        <a href="/staff/payslip" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('staff/payslip*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Payslip
        </a>

        <!-- SYSTEM -->
        <p class="sidebar-section-label mt-5">System</p>

        <a href="/panduan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('panduan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-book-open w-5 text-center"></i> Documentation
        </a>

        <a href="/faq" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('faq*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-comments w-5 text-center"></i> FAQ
        </a>


        <?php elseif ($role === 'owner'): ?>
        <!-- ═══════════════════════════════════════ -->
        <!-- ═══ OWNER SIDEBAR ═══ -->
        <!-- ═══════════════════════════════════════ -->

        <!-- OVERVIEW -->
        <p class="sidebar-section-label">Overview</p>
        
        <a href="/dashboard" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('dashboard')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-gauge-high w-5 text-center"></i> Executive Dashboard
        </a>

        <!-- BUSINESS -->
        <p class="sidebar-section-label mt-5">Business</p>

        <a href="/financial-overview" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('financial-overview*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-coins w-5 text-center"></i> Financial Overview
        </a>

        <a href="/tax-overview" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('tax-overview*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-receipt w-5 text-center"></i> Tax Overview
        </a>

        <!-- INSIGHTS -->
        <p class="sidebar-section-label mt-5">
            <i class="fa-solid fa-lightbulb text-[8px] mr-1"></i> Insights
        </p>

        <a href="/analysis-results" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('analysis-results*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-chart-column w-5 text-center"></i> Analysis Results
        </a>

        <a href="/risk-compliance" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('risk-compliance*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-shield-halved w-5 text-center"></i> Risk & Compliance
        </a>

        <a href="/recommendations" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('recommendations*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-lightbulb w-5 text-center"></i> Recommendations
        </a>

        <!-- REPORTS -->
        <p class="sidebar-section-label mt-5">Reports</p>

        <a href="/keuangan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('keuangan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Financial Reports
        </a>

        <a href="/laporan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('laporan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-lines w-5 text-center"></i> Tax Reports
        </a>

        <!-- WORKFLOW -->
        <p class="sidebar-section-label mt-5">
            <i class="fa-solid fa-arrows-spin text-[8px] mr-1"></i> Workflow
        </p>

        <a href="/pending-reviews" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('pending-reviews*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-bell w-5 text-center"></i> Pending Reviews
        </a>

        <a href="/approved-analyses" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('approved-analyses*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-circle-check w-5 text-center"></i> Approved Analyses
        </a>

        <!-- FINANCE APPROVALS -->
        <p class="sidebar-section-label mt-5">Finance & Admin</p>
        <a href="/executive/finance-approvals" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('executive/finance-approvals*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-money-check-dollar w-5 text-center"></i> Finance Approvals
        </a>

        <!-- SYSTEM -->
        <p class="sidebar-section-label mt-5">System</p>

        <a href="/panduan" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('panduan*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-book-open w-5 text-center"></i> Documentation
        </a>

        <a href="/faq" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('faq*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-comments w-5 text-center"></i> FAQ
        </a>

        <?php elseif ($role === 'akuntansi'): ?>
        <!-- ═══════════════════════════════════════ -->
        <!-- ═══ AKUNTANSI SIDEBAR ═══ -->
        <!-- ═══════════════════════════════════════ -->

        <!-- OVERVIEW -->
        <p class="sidebar-section-label">Overview</p>
        <a href="/accounting/dashboard" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('accounting/dashboard')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-calculator w-5 text-center"></i> Dashboard
        </a>

        <!-- DATA PROCESSING -->
        <p class="sidebar-section-label mt-5">Data Processing</p>
        <a href="/accounting/process-financial" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('accounting/process-financial*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-gears w-5 text-center"></i> Mesin Jurnal & GL
        </a>
        <a href="/accounting/payroll" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('accounting/payroll*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-money-check-dollar w-5 text-center"></i> Kalkulator Payroll & PPh 21
        </a>
        <a href="/accounting/tax-transactional" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('accounting/tax-transactional*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-receipt w-5 text-center"></i> Pajak Transaksional
        </a>

        <!-- REPORTS -->
        <p class="sidebar-section-label mt-5">Reports</p>
        <a href="/accounting/reports" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('accounting/reports*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Laporan Keuangan (P&L)
        </a>

        <?php elseif ($role === 'bendahara'): ?>
        <!-- ═══════════════════════════════════════ -->
        <!-- ═══ BENDAHARA SIDEBAR ═══ -->
        <!-- ═══════════════════════════════════════ -->

        <!-- OVERVIEW -->
        <p class="sidebar-section-label">Overview</p>
        <a href="/finance/dashboard" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('finance/dashboard')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-chart-line w-5 text-center"></i> Dashboard
        </a>

        <!-- PAYROLL & TAX APPROVAL -->
        <p class="sidebar-section-label mt-5">Disbursement & Approval</p>
        <a href="/finance/recap-payroll" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('finance/recap-payroll*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-money-check-dollar w-5 text-center"></i> Persetujuan Payroll
        </a>
        <a href="/finance/recap-reports" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('finance/recap-reports*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Rekap Laporan & Pajak
        </a>

        <!-- OPERATIONAL & CLAIMS -->
        <p class="sidebar-section-label mt-5">Operational & Claims</p>
        <a href="/finance/claims" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('finance/claims*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-file-invoice w-5 text-center"></i> Reimbursement
        </a>
        <a href="/finance/journal" class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all <?= (url_is('finance/journal*')) ? $activeClass : $hoverClass ?>">
            <i class="fa-solid fa-book w-5 text-center"></i> Jurnal Kas Internal
        </a>

        <?php endif; ?>
        
    </nav>



</aside>