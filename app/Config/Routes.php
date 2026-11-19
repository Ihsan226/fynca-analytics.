<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/testlog', 'TestLog::index');

// =========================================================
// 1. ROUTES AUTENTIKASI (Bebas Akses)
// =========================================================
$routes->get('/', 'Auth::index'); 
$routes->get('/auth', 'Auth::index');
$routes->post('/auth/loginProcess', 'Auth::loginProcess');

$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/registerProcess', 'Auth::registerProcess');

$routes->get('/auth/logout', 'Auth::logout');


// =========================================================
// 2. ROUTES PROTECTED (Wajib Login - Dilindungi Filter)
// =========================================================
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // ═══ OVERVIEW ═══
    $routes->get('/dashboard', 'Dashboard::index');

    // ═══ DATA ═══
    $routes->get('/perusahaan', 'Perusahaan::index');
    $routes->get('/perusahaan/create', 'Perusahaan::create', ['filter' => 'role:admin,staff']);
    $routes->post('/perusahaan/store', 'Perusahaan::store', ['filter' => 'role:admin,staff']);
    $routes->get('/perusahaan/edit/(:num)', 'Perusahaan::edit/$1', ['filter' => 'role:admin,staff']);
    $routes->post('/perusahaan/update/(:num)', 'Perusahaan::update/$1', ['filter' => 'role:admin,staff']);
    $routes->get('/perusahaan/delete/(:num)', 'Perusahaan::delete/$1', ['filter' => 'role:admin,staff']);

    $routes->get('/keuangan', 'Keuangan::index');
    $routes->get('/keuangan/import', 'Keuangan::import', ['filter' => 'role:admin,staff']);
    $routes->get('/keuangan/create', 'Keuangan::create', ['filter' => 'role:admin,staff']);
    $routes->post('/keuangan/store', 'Keuangan::store', ['filter' => 'role:admin,staff']);
    $routes->get('/keuangan/edit/(:num)', 'Keuangan::edit/$1', ['filter' => 'role:admin,staff']);
    $routes->post('/keuangan/update/(:num)', 'Keuangan::update/$1', ['filter' => 'role:admin,staff']);
    $routes->get('/keuangan/delete/(:num)', 'Keuangan::delete/$1', ['filter' => 'role:admin,staff']);
    $routes->post('/keuangan/extract-data', 'Keuangan::extractData', ['filter' => 'role:admin,staff']);
    $routes->post('/keuangan/simpan-mapping', 'Keuangan::simpanMapping', ['filter' => 'role:admin,staff']);
    $routes->get('/keuangan/review-mapping/(:num)/(:num)', 'Keuangan::reviewMapping/$1/$2', ['filter' => 'role:admin,staff']);
    $routes->get('/keuangan/send-to-processing/(:num)', 'Keuangan::sendToProcessing/$1', ['filter' => 'role:admin,staff']);
    
    // ═══ IMPORT EXCEL ═══
    $routes->get('/import', 'Import::index', ['filter' => 'role:admin,staff']);
    $routes->post('/import/process', 'Import::process', ['filter' => 'role:admin,staff']);
    $routes->get('/import/download-template', 'Import::downloadTemplate', ['filter' => 'role:admin,staff']);

    // ═══ ANALYTICS ═══
    $routes->get('/analisis', 'Analisis::index', ['filter' => 'role:admin,owner,analyst']);
    $routes->get('/analisis/detail/(:num)', 'Analisis::detail/$1', ['filter' => 'role:admin,owner,analyst']);

    // ═══ ANALYST WORKFLOW ═══
    $routes->get('/workspace', 'AnalystWorkspace::index', ['filter' => 'role:admin,owner,analyst']);
    $routes->get('/workspace/analyze/(:num)/(:num)', 'AnalystWorkspace::analyze/$1/$2', ['filter' => 'role:admin,owner,analyst']);
    $routes->post('/workspace/store', 'AnalystWorkspace::store', ['filter' => 'role:admin,owner,analyst']);
    $routes->get('/workspace/edit/(:num)', 'AnalystWorkspace::edit/$1', ['filter' => 'role:admin,owner,analyst']);
    $routes->post('/workspace/update/(:num)', 'AnalystWorkspace::update/$1', ['filter' => 'role:admin,owner,analyst']);
    $routes->get('/workspace/submit/(:num)', 'AnalystWorkspace::submit/$1', ['filter' => 'role:admin,owner,analyst']);
    
    $routes->get('/my-analyses', 'AnalystWorkspace::myAnalyses', ['filter' => 'role:admin,owner,analyst']);
    $routes->get('/review-status', 'AnalystWorkspace::reviewStatus', ['filter' => 'role:admin,owner,analyst']);
    $routes->get('/simulation', 'Simulation::index', ['filter' => 'role:admin,owner,analyst']);
    $routes->post('/simulation/calculate', 'Simulation::calculate', ['filter' => 'role:admin,owner,analyst']);

    // ═══ OWNER WORKFLOW ═══
    $routes->get('/financial-overview', 'OwnerConsole::financialOverview', ['filter' => 'role:admin,owner']);
    $routes->get('/tax-overview', 'OwnerConsole::taxOverview', ['filter' => 'role:admin,owner']);
    $routes->get('/analysis-results', 'OwnerConsole::analysisResults', ['filter' => 'role:admin,owner']);
    $routes->get('/risk-compliance', 'OwnerConsole::riskCompliance', ['filter' => 'role:admin,owner']);
    $routes->get('/recommendations', 'OwnerConsole::recommendations', ['filter' => 'role:admin,owner']);
    $routes->get('/pending-reviews', 'OwnerConsole::pendingReviews', ['filter' => 'role:admin,owner']);
    $routes->get('/approved-analyses', 'OwnerConsole::approvedAnalyses', ['filter' => 'role:admin,owner']);
    $routes->post('/review-report/(:num)', 'OwnerConsole::reviewReport/$1', ['filter' => 'role:admin,owner']);

    // ═══ REPORTS ═══
    $routes->get('/laporan', 'Laporan::index');

    // ═══ FINANCE (BENDAHARA) ═══
    $routes->get('/finance/dashboard', 'Finance::dashboard', ['filter' => 'role:admin,bendahara']);
    $routes->get('/finance/recap-reports', 'Finance::recapReports', ['filter' => 'role:admin,bendahara']);
    $routes->get('/finance/recap-payroll', 'Finance::recapPayroll', ['filter' => 'role:admin,bendahara']);
    $routes->post('/finance/approve-disbursement/(:num)/(:num)', 'Finance::approveDisbursement/$1/$2', ['filter' => 'permission:treasury.payment.execute']);
    $routes->post('/finance/payment-request/cancel/(:num)', 'Finance::cancelPaymentRequest/$1', ['filter' => 'permission:treasury.payment.execute']);
    $routes->post('/finance/payment-request/retry/(:num)', 'Finance::retryPaymentRequest/$1', ['filter' => 'permission:treasury.payment.execute']);
    $routes->get('/finance/claims', 'Finance::claims', ['filter' => 'role:admin,bendahara']);
    $routes->get('/finance/payment-requests', 'Finance::paymentRequests', ['filter' => 'permission:treasury.payment.execute']);
    $routes->post('/finance/claims/approve/(:num)', 'Finance::claimsApprove/$1', ['filter' => 'permission:treasury.reimbursement.approve']);
    $routes->post('/finance/claims/pay/(:num)', 'Finance::claimsPay/$1', ['filter' => 'permission:treasury.payment.execute']);
    $routes->post('/finance/claims/reject/(:num)', 'Finance::claimsReject/$1', ['filter' => 'permission:treasury.reimbursement.approve']);
    $routes->get('/finance/journal', 'Finance::journal', ['filter' => 'role:admin,bendahara']);
    $routes->post('/finance/journal/store', 'Finance::journalStore', ['filter' => 'role:admin,bendahara']);

    // ═══ ACCOUNTING (AKUNTANSI) ═══
    $routes->get('/accounting/dashboard', 'Accounting::dashboard', ['filter' => 'role:admin,akuntansi']);
    $routes->get('/accounting/process-financial', 'Accounting::processFinancial', ['filter' => 'role:admin,akuntansi']);
    $routes->get('/accounting/generate-journal/(:num)', 'Accounting::generateJournal/$1', ['filter' => 'role:admin,akuntansi']);
    $routes->get('/accounting/payroll', 'Accounting::payroll', ['filter' => 'role:admin,akuntansi']);
    $routes->post('/accounting/payroll-generate', 'Accounting::payrollGenerate', ['filter' => 'permission:payroll.generate']);
    $routes->get('/accounting/tax-transactional', 'Accounting::taxTransactional', ['filter' => 'role:admin,akuntansi']);
    $routes->post('/accounting/submit-to-treasurer/(:num)', 'Accounting::submitToTreasurer/$1', ['filter' => 'role:admin,akuntansi']);
    $routes->post('/accounting/submit-to-treasurer', 'Accounting::submitToTreasurer', ['filter' => 'role:admin,akuntansi']);
    $routes->get('/accounting/reports', 'Accounting::reports', ['filter' => 'role:admin,akuntansi']);
    $routes->get('/accounting/general-ledger', 'Accounting::generalLedger', ['filter' => 'role:admin,akuntansi']);
    $routes->get('/accounting/trial-balance', 'Accounting::trialBalance', ['filter' => 'role:admin,akuntansi']);
    $routes->post('/accounting/period/close/(:num)', 'Accounting::closePeriod/$1', ['filter' => 'permission:accounting.period.close']);
    $routes->post('/accounting/journal/reverse/(:num)', 'Accounting::reverseJournal/$1', ['filter' => 'permission:accounting.journal.reverse']);

    // ═══ EXECUTIVE FINANCE (OWNER) ═══
    $routes->get('/executive/finance-approvals', 'ExecutiveFinance::approvals', ['filter' => 'role:admin,owner']);
    $routes->post('/executive/finance/approve-payroll/(:num)/(:num)', 'ExecutiveFinance::approvePayroll/$1/$2', ['filter' => 'permission:payroll.approve']);
    $routes->post('/executive/finance/reject-payroll/(:num)/(:num)', 'ExecutiveFinance::rejectPayroll/$1/$2', ['filter' => 'permission:payroll.reject']);

    // ═══ STAFF FINANCE ═══
    $routes->get('/staff/claims', 'StaffFinance::claims', ['filter' => 'role:admin,staff,analyst']);
    $routes->post('/staff/claims/store', 'StaffFinance::claimsStore', ['filter' => 'role:admin,staff,analyst']);
    $routes->get('/staff/payslip', 'StaffFinance::payslip', ['filter' => 'role:admin,staff,analyst']);
    $routes->get('/staff/payslip/download/(:num)', 'StaffFinance::payslipDownload/$1', ['filter' => 'role:admin,staff,analyst']);


    // ═══ CONFIGURATION ═══
    $routes->get('/sektor', 'Sektor::index');
    $routes->post('/sektor/store', 'Sektor::store', ['filter' => 'role:admin']);
    $routes->post('/sektor/update/(:num)', 'Sektor::update/$1', ['filter' => 'role:admin']);
    $routes->get('/sektor/delete/(:num)', 'Sektor::delete/$1', ['filter' => 'role:admin']);

    $routes->get('/parameter-rasio', 'ParameterRasio::index');
    $routes->post('/parameter-rasio/store', 'ParameterRasio::store', ['filter' => 'role:admin']);
    $routes->post('/parameter-rasio/update/(:num)', 'ParameterRasio::update/$1', ['filter' => 'role:admin']);
    $routes->get('/parameter-rasio/delete/(:num)', 'ParameterRasio::delete/$1', ['filter' => 'role:admin']);

    $routes->get('/regulasi', 'RegulasiReferensi::index');
    $routes->post('/regulasi/store', 'RegulasiReferensi::store', ['filter' => 'role:admin']);
    $routes->post('/regulasi/update/(:num)', 'RegulasiReferensi::update/$1', ['filter' => 'role:admin']);
    $routes->get('/regulasi/delete/(:num)', 'RegulasiReferensi::delete/$1', ['filter' => 'role:admin']);

    // ═══ SYSTEM ═══
    $routes->get('/aktivitas', 'Aktivitas::index', ['filter' => 'role:admin']);
    $routes->get('/aktivitas/clear', 'Aktivitas::clear', ['filter' => 'role:admin']);

    $routes->get('/broadcasts', 'Broadcasts::index', ['filter' => 'role:admin']);
    $routes->post('/broadcasts/store', 'Broadcasts::store', ['filter' => 'role:admin']);
    $routes->get('/broadcasts/toggle/(:num)', 'Broadcasts::toggle/$1', ['filter' => 'role:admin']);
    $routes->get('/broadcasts/delete/(:num)', 'Broadcasts::delete/$1', ['filter' => 'role:admin']);

    $routes->get('/backup', 'Backup::index', ['filter' => 'role:admin']);
    $routes->get('/backup/download', 'Backup::download', ['filter' => 'role:admin']);

    $routes->get('/tickets', 'Tickets::index', ['filter' => 'role:admin']);
    $routes->get('/tickets/(:num)', 'Tickets::detail/$1', ['filter' => 'role:admin']);
    $routes->post('/tickets/reply/(:num)', 'Tickets::reply/$1', ['filter' => 'role:admin']);
    $routes->get('/tickets/resolve/(:num)', 'Tickets::resolve/$1', ['filter' => 'role:admin']);

    $routes->get('/panduan', 'Panduan::index');
    $routes->get('/faq', 'Faq::index');

    // ═══ NOTIFICATIONS ═══
    $routes->get('/notifications/get', 'NotificationController::getUnread');
    $routes->post('/notifications/mark_read/(:num)', 'NotificationController::markAsRead/$1');
    $routes->post('/notifications/mark_all_read', 'NotificationController::markAllAsRead');

    // ═══ USER & SETTINGS ═══
    $routes->get('/profile', 'Profile::index');
    $routes->post('/profile/update', 'Profile::update');
    
    $routes->get('/pengguna', 'Pengguna::index', ['filter' => 'role:admin']);
    $routes->post('/pengguna/store', 'Pengguna::store', ['filter' => 'role:admin']);
    $routes->post('/pengguna/update/(:num)', 'Pengguna::update/$1', ['filter' => 'role:admin']);
    $routes->get('/pengguna/delete/(:num)', 'Pengguna::delete/$1', ['filter' => 'role:admin']);
    $routes->get('/pengaturan', 'Pengaturan::index', ['filter' => 'role:admin']);
});