<?php

namespace App\Controllers;

class Accounting extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Accounting Dashboard | Fynca Analytics'
        ];
        return view('accounting/dashboard', $data);
    }

    public function processFinancial()
    {
        // View unmapped raw financial data or data that needs journaling
        $db = \Config\Database::connect();
        
        $pendingReports = $db->table('laporan_keuangan')
            ->select('laporan_keuangan.*, perusahaan.nama_perusahaan, report_workflows.accounting_status')
            ->join('perusahaan', 'perusahaan.id_perusahaan = laporan_keuangan.id_perusahaan')
            ->join('report_workflows', 'report_workflows.id_laporan = laporan_keuangan.id_laporan')
            ->where('report_workflows.accounting_status', 'pending')
            ->get()->getResultArray();

        $data = [
            'title' => 'Process Financial Data | Fynca Analytics',
            'pendingReports' => $pendingReports
        ];
        return view('accounting/process_financial', $data);
    }

    public function generateJournal($id_laporan)
    {
        // Karena jurnal sudah tergenerate oleh sistem saat Staff menekan "Kirim Sekarang",
        // tugas fungsi ini hanya mem-validasi (Review) dan menyelesaikan status workflow Akuntansi.
        $db = \Config\Database::connect();
        
        $db->table('report_workflows')
            ->where('id_laporan', $id_laporan)
            ->update(['accounting_status' => 'completed']);
            
        // Optional: Update journal status to posted
        // $db->table('acc_journals')->where('id_perusahaan', ...)->update(['status' => 'posted']);
        
        return redirect()->back()->with('success', 'Review selesai! Jurnal telah sukses terposting dan diserahkan.');
    }

    public function payroll()
    {
        $gajiModel = new \App\Models\FinanceGajiKomponenModel();
        $users = $gajiModel->getUsersWithSalary();
        
        $periode_bulan = $this->request->getGet('bulan') ?? date('m');
        $periode_tahun = $this->request->getGet('tahun') ?? date('Y');

        $payrollModel = new \App\Models\FinancePayrollModel();
        $payrolls = $payrollModel->where('periode_bulan', $periode_bulan)
                                 ->where('periode_tahun', $periode_tahun)
                                 ->findAll();
        
        $payrollMap = [];
        $status_batch = null; 
        foreach ($payrolls as $p) {
            $payrollMap[$p['id_user']] = $p;
            $status_batch = $p['status'];
        }

        $data = [
            'title' => 'Kalkulasi Payroll & PPh 21 | Fynca Analytics',
            'users' => $users,
            'bulan' => $periode_bulan,
            'tahun' => $periode_tahun,
            'payrollMap' => $payrollMap,
            'status_batch' => $status_batch
        ];
        return view('accounting/payroll', $data);
    }

    public function payrollGenerate()
    {
        $payrollModel = new \App\Models\FinancePayrollModel();
        $periode_bulan = $this->request->getPost('periode_bulan');
        $periode_tahun = $this->request->getPost('periode_tahun');
        $id_users = $this->request->getPost('id_users');
        $gaji_pokoks = $this->request->getPost('gaji_pokoks');
        $tunjangans = $this->request->getPost('tunjangans');
        $bonuses = $this->request->getPost('bonuses');
        $potongan_bpjs = $this->request->getPost('potongan_bpjs');
        $potongan_lain = $this->request->getPost('potongan_lain');

        if (!$id_users) {
            return redirect()->back()->with('error', 'Tidak ada data karyawan.');
        }

        $existing = $payrollModel->where('periode_bulan', $periode_bulan)
                                 ->where('periode_tahun', $periode_tahun)
                                 ->first();
        if ($existing && in_array($existing['status'], ['pending_approval', 'approved', 'locked'])) {
            return redirect()->back()->with('error', 'Payroll periode ini sudah diajukan, tidak bisa diubah.');
        }

        $payrollModel->where('periode_bulan', $periode_bulan)
                     ->where('periode_tahun', $periode_tahun)
                     ->delete();

        $gajiModel = new \App\Models\FinanceGajiKomponenModel();
        $taxService = new \App\Services\TaxRuleService();

        foreach ($id_users as $i => $id_user) {
            $gp = floatval(str_replace(',', '', $gaji_pokoks[$i] ?? 0));
            $tj = floatval(str_replace(',', '', $tunjangans[$i] ?? 0));
            $bn = floatval(str_replace(',', '', $bonuses[$i] ?? 0));
            $pb = floatval(str_replace(',', '', $potongan_bpjs[$i] ?? 0));
            $pl = floatval(str_replace(',', '', $potongan_lain[$i] ?? 0));
            
            $gajiData = $gajiModel->where('id_user', $id_user)->first();
            $status = $gajiData['status_pernikahan'] ?? 'TK/0';

            $brutoBulan = $gp + $tj + $bn;
            $taxResult = $taxService->calculatePph21($brutoBulan, $status, (int) $periode_tahun);
            $pph21 = $taxResult['tax'];
            
            $thp = $brutoBulan - $pb - $pl - $pph21;

            $payrollModel->insert([
                'id_user' => $id_user,
                'periode_bulan' => $periode_bulan,
                'periode_tahun' => $periode_tahun,
                'gaji_pokok' => $gp,
                'tunjangan' => $tj,
                'bonus' => $bn,
                'potongan_bpjs' => $pb,
                'potongan_lain' => $pl,
                'potongan_pph21' => $pph21,
                'tax_gross_monthly' => $taxResult['gross_monthly'],
                'tax_gross_annual' => $taxResult['gross_annual'],
                'tax_ptkp' => $taxResult['ptkp'],
                'tax_pkp' => $taxResult['pkp'],
                'tax_rule_version' => $taxResult['rule_version'],
                'total_bersih' => $thp, // Total bersih after PPh 21
                'status' => 'pending_approval' // Changed from draft to pending_approval (sent to Treasurer)
            ]);
        }

        return redirect()->back()->with('success', 'Payroll dan PPh 21 berhasil dihitung dan dikirim ke Bendahara untuk persetujuan.');
    }

    public function taxTransactional()
    {
        $db = \Config\Database::connect();
        
        // Query from potongan_pajak joined with acc_journal_items and acc_journals
        $builder = $db->table('potongan_pajak');
        $builder->select('potongan_pajak.*, acc_journal_items.debit, acc_journal_items.credit, acc_journals.tanggal, acc_journals.deskripsi');
        $builder->join('acc_journal_items', 'acc_journal_items.id_item = potongan_pajak.id_journal_item');
        $builder->join('acc_journals', 'acc_journals.id_journal = acc_journal_items.id_journal');
        $taxQuery = $builder->get()->getResultArray();

        $taxDetected = [];
        $totalPajak = 0;

        foreach ($taxQuery as $row) {
            $nominal_trx = max($row['debit'], $row['credit']);
            $taxDetected[] = [
                'trx' => [
                    'tanggal' => $row['tanggal'],
                    'deskripsi' => $row['deskripsi'],
                    'nominal' => $nominal_trx
                ],
                'jenis' => $row['jenis_pajak'],
                'tarif' => floatval($row['tarif']) . '%',
                'potongan' => $row['nominal_potongan']
            ];
            $totalPajak += $row['nominal_potongan'];
        }

        $data = [
            'title' => 'Pajak Transaksional (PPh 22, 23, 24) | Fynca Analytics',
            'taxDetected' => $taxDetected,
            'totalPajak' => $totalPajak
        ];
        return view('accounting/tax_transactional', $data);
    }

    public function submitToTreasurer($id_laporan = null)
    {
        $db = \Config\Database::connect();
        
        // 1. Update report_workflows
        if ($id_laporan) {
            $db->table('report_workflows')->where('id_laporan', $id_laporan)->update(['accounting_status' => 'submitted_to_treasurer']);
        } else {
            $db->table('report_workflows')->where('accounting_status', 'completed')->update(['accounting_status' => 'submitted_to_treasurer']);
        }

        // 2. Lock all draft journals
        $db->table('acc_journals')->where('status', 'draft')->update(['status' => 'locked']);

        // 3. Lock all pending_approval payrolls
        $db->table('finance_payroll')->where('status', 'pending_approval')->update(['status' => 'locked']);
        
        return redirect()->back()->with('success', 'Laporan berhasil di-Lock dan dikirim ke Bendahara.');
    }

    public function reports()
    {
        $db = \Config\Database::connect();
        
        // P&L Engine: Query SUM from acc_journal_items joined with acc_coa
        $builder = $db->table('acc_journal_items');
        $builder->select('acc_coa.tipe_akun, acc_coa.nama_akun, SUM(acc_journal_items.debit) as total_debit, SUM(acc_journal_items.credit) as total_credit');
        $builder->join('acc_coa', 'acc_coa.id_coa = acc_journal_items.id_coa');
        $builder->join('acc_journals', 'acc_journals.id_journal = acc_journal_items.id_journal');
        $builder->whereIn('acc_coa.tipe_akun', ['revenue', 'expense']);
        // If we want a specific period, we could add $builder->where('acc_journals.tanggal >=', ...);
        $builder->groupBy('acc_coa.id_coa');
        $queryResult = $builder->get()->getResultArray();

        $pendapatan = 0;
        $hpp = 0;
        $beban_operasional = [];
        $total_beban = 0;

        foreach ($queryResult as $row) {
            if ($row['tipe_akun'] === 'revenue') {
                // Revenue normal balance is credit
                $pendapatan += ($row['total_credit'] - $row['total_debit']);
            } elseif ($row['tipe_akun'] === 'expense') {
                // Expense normal balance is debit
                $val = ($row['total_debit'] - $row['total_credit']);
                if (strtolower($row['nama_akun']) == 'hpp' || strpos(strtolower($row['nama_akun']), 'pokok') !== false) {
                    $hpp += $val;
                } else {
                    $beban_operasional[$row['nama_akun']] = $val;
                    $total_beban += $val;
                }
            }
        }

        $laba_kotor = $pendapatan - $hpp;
        $laba_sebelum_pajak = $laba_kotor - $total_beban;
        $estimasi_beban_pajak = $laba_sebelum_pajak * 0.22; // Asumsi PPh Badan 22%
        $laba_bersih = $laba_sebelum_pajak - $estimasi_beban_pajak;

        // Check if there are any locked journals
        $lockedCheck = $db->table('acc_journals')->where('status', 'locked')->countAllResults();
        $status = ($lockedCheck > 0) ? 'locked' : 'draft';

        $pnl = [
            'pendapatan' => $pendapatan,
            'hpp' => $hpp,
            'laba_kotor' => $laba_kotor,
            'beban_operasional' => empty($beban_operasional) ? ['Belum ada rincian beban' => 0] : $beban_operasional,
            'total_beban_operasional' => $total_beban,
            'laba_sebelum_pajak' => $laba_sebelum_pajak,
            'estimasi_beban_pajak' => $estimasi_beban_pajak,
            'laba_bersih' => $laba_bersih,
            'status' => $status
        ];

        $data = [
            'title' => 'Laporan Keuangan (Balance Sheet, P&L) | Fynca Analytics',
            'pnl' => $pnl
        ];
        return view('accounting/reports', $data);
    }

    public function generalLedger()
    {
        $companyId = (int) ($this->request->getGet('company_id') ?? 0);
        $from = $this->request->getGet('from') ?? date('Y-m-01');
        $to = $this->request->getGet('to') ?? date('Y-m-t');
        $coaId = (int) ($this->request->getGet('coa_id') ?? 0);

        $ledger = $coaId > 0
            ? (new \App\Services\GeneralLedgerService())->account($companyId, $coaId, $from, $to)
            : [];

        return view('accounting/general_ledger', [
            'title' => 'General Ledger | Fynca Analytics',
            'ledger' => $ledger,
            'companyId' => $companyId,
            'coaId' => $coaId,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function trialBalance()
    {
        $companyId = (int) ($this->request->getGet('company_id') ?? 0);
        $from = $this->request->getGet('from') ?? date('Y-m-01');
        $to = $this->request->getGet('to') ?? date('Y-m-t');
        $trialBalance = $companyId > 0
            ? (new \App\Services\TrialBalanceService())->forPeriod($companyId, $from, $to)
            : ['accounts' => [], 'total_debit' => 0, 'total_credit' => 0, 'balanced' => true];

        return view('accounting/trial_balance', [
            'title' => 'Trial Balance | Fynca Analytics',
            'trialBalance' => $trialBalance,
            'companyId' => $companyId,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function closePeriod($id)
    {
        try {
            (new \App\Services\AccountingPeriodService())->close(
                (int) $id,
                (int) session()->get('id_user')
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Periode akuntansi berhasil ditutup.');
    }

    public function reverseJournal($id)
    {
        $reason = trim((string) $this->request->getPost('reason'));
        if ($reason === '') {
            return redirect()->back()->with('error', 'Alasan reversal wajib diisi.');
        }

        try {
            (new \App\Services\JournalPostingService())->reverseJournal(
                (int) $id,
                (int) session()->get('id_user'),
                $reason
            );
        } catch (\InvalidArgumentException | \RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Jurnal reversal berhasil diposting.');
    }
}
