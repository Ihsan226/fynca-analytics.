<?php

namespace App\Controllers;

class Finance extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Finance Dashboard | Fynca Analytics'
        ];
        return view('finance/dashboard', $data);
    }

    public function recapReports()
    {
        // View recap of Profit/Loss, Cash, Tax Liabilities
        $data = [
            'title' => 'Rekap Laporan Keuangan | Fynca Analytics'
        ];
        return view('finance/recap_reports', $data);
    }

    public function recapPayroll()
    {
        // View recap of total payroll expense and PPh 21 deductions
        $periode_bulan = $this->request->getGet('bulan') ?? date('m');
        $periode_tahun = $this->request->getGet('tahun') ?? date('Y');

        // Bendahara hanya melihat payroll yang sudah disetujui Owner.
        $db = \Config\Database::connect();
        $payrollBuilder = $db->table('finance_payroll p')
            ->select('p.*')
            ->join('users u', 'u.id_user = p.id_user')
            ->where('p.periode_bulan', $periode_bulan)
            ->where('p.periode_tahun', $periode_tahun)
            ->whereIn('p.status', ['approved', 'paid']);
        if (session()->get('id_organization') !== null) {
            $payrollBuilder->where('u.id_organization', session()->get('id_organization'));
        }
        $payrolls = $payrollBuilder->get()->getResultArray();
        
        $total_beban = 0;
        $total_pph21 = 0;
        $status_batch = null; 
        
        foreach ($payrolls as $p) {
            $total_beban += $p['gaji_pokok'] + $p['tunjangan'] + $p['bonus'];
            $total_pph21 += $p['potongan_pph21'] ?? 0;
            $status_batch = $p['status'];
        }

        $data = [
            'title' => 'Rekap Payroll & Pajak | Fynca Analytics',
            'bulan' => $periode_bulan,
            'tahun' => $periode_tahun,
            'total_beban' => $total_beban,
            'total_pph21' => $total_pph21,
            'status_batch' => $status_batch
        ];
        return view('finance/recap_payroll', $data);
    }

    public function approveDisbursement($bulan, $tahun)
    {
        $paymentProof = $this->request->getFile('payment_proof');
        $paymentProofPath = null;
        if ($paymentProof && $paymentProof->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$paymentProof->isValid() || $paymentProof->hasMoved()) {
                return redirect()->back()->with('error', 'Bukti pembayaran tidak dapat dibaca.');
            }
            if (!in_array($paymentProof->getMimeType(), ['image/jpeg', 'image/png', 'application/pdf'], true) || $paymentProof->getSizeByUnit('mb') > 5) {
                return redirect()->back()->with('error', 'Bukti pembayaran harus berupa JPG, PNG, atau PDF maksimal 5 MB.');
            }
            $paymentProofPath = $paymentProof->store('payment-proofs');
        }

        try {
            $payroll = (new \App\Services\PayrollWorkflowService())->payBatch(
                (int) $bulan,
                (int) $tahun,
                (int) session()->get('id_user'),
                $paymentProofPath
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $userIds = $payroll['user_ids'];
        $notificationModel = new \App\Models\NotificationModel();
        foreach ($userIds as $userId) {
            $notificationModel->insert([
                'target_role' => null,
                'target_user_id' => $userId,
                'title' => 'Payroll Telah Dicairkan',
                'message' => 'Payroll periode ' . sprintf('%02d/%d', $bulan, $tahun) . ' telah dibayarkan.',
                'link' => '/staff/payslip'
            ]);
        }
           
        log_activity('Approve Disbursement', 'Menyetujui pencairan dana payroll/laporan.', 'update');

        return redirect()->back()->with('success', 'Pembayaran telah disetujui (Disbursed). Slip gaji dapat diakses oleh karyawan.');
    }

    public function claims()
    {
        $claimModel = new \App\Models\FinanceReimbursementModel();
        $claims = $claimModel->getClaimsWithUser();

        $data = [
            'title' => 'Klaim Reimbursement | Fynca Analytics',
            'claims' => $claims
        ];
        return view('finance/claims/index', $data);
    }

    public function paymentRequests()
    {
        $builder = \Config\Database::connect()->table('payment_requests');
        $organizationId = session()->get('id_organization');
        if ($organizationId !== null) {
            $builder->where('id_organization', $organizationId);
        }

        return view('finance/payment_requests', [
            'title' => 'Payment Monitoring | Fynca Analytics',
            'requests' => $builder->orderBy('created_at', 'DESC')->get()->getResultArray(),
        ]);
    }

    public function claimsApprove($id)
    {
        $claimModel = new \App\Models\FinanceReimbursementModel();
        $claim = $claimModel->findForCurrentOrganization((int) $id);
        
        if (!$claim || $claim['status'] != 'pending') {
            return redirect()->back()->with('error', 'Klaim tidak valid atau sudah diproses.');
        }

        try {
            (new \App\Services\ApprovalService())->transitionReimbursement(
                (int) $id,
                'pending',
                'approved',
                (int) session()->get('id_user'),
                'Klaim disetujui oleh Bendahara.'
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        (new \App\Models\NotificationModel())->insert([
            'target_role' => null,
            'target_user_id' => $claim['id_user'],
            'title' => 'Reimbursement Disetujui',
            'message' => 'Klaim reimbursement Anda telah disetujui dan menunggu pembayaran.',
            'link' => '/staff/claims',
        ]);

        return redirect()->back()->with('success', 'Klaim disetujui dan menunggu proses pembayaran.');
    }

    public function claimsPay($id)
    {
        $claimModel = new \App\Models\FinanceReimbursementModel();
        $claim = $claimModel->findForCurrentOrganization((int) $id);
        if (!$claim || $claim['status'] !== 'approved') {
            return redirect()->back()->with('error', 'Klaim tidak ditemukan atau belum disetujui.');
        }

        $proof = $this->request->getFile('payment_proof');
        if (!$proof || !$proof->isValid() || $proof->hasMoved()) {
            return redirect()->back()->with('error', 'Bukti pembayaran wajib diunggah.');
        }
        if (!in_array($proof->getMimeType(), ['image/jpeg', 'image/png', 'application/pdf'], true) || $proof->getSizeByUnit('mb') > 5) {
            return redirect()->back()->with('error', 'Bukti pembayaran harus berupa JPG, PNG, atau PDF maksimal 5 MB.');
        }

        $path = $proof->store('payment-proofs');
        $db = \Config\Database::connect();
        $db->transStart();
        $claimModel->update($id, [
            'status' => 'paid',
            'payment_proof' => $path,
            'paid_at' => date('Y-m-d H:i:s'),
        ]);
        (new \App\Services\JournalPostingService($db))->postCashMovement(
            'kredit',
            (float) $claim['nominal'],
            'Pembayaran Reimbursement: ' . $claim['deskripsi'],
            'reimbursement',
            (int) $id
        );
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Pembayaran klaim gagal diproses.');
        }

        (new \App\Models\NotificationModel())->insert([
            'target_role' => null,
            'target_user_id' => $claim['id_user'],
            'title' => 'Reimbursement Telah Dibayar',
            'message' => 'Klaim reimbursement Anda telah dibayar.',
            'link' => '/staff/claims',
        ]);

        return redirect()->back()->with('success', 'Klaim berhasil dibayarkan dan dicatat pada jurnal kas.');
    }

    public function claimsReject($id)
    {
        $claimModel = new \App\Models\FinanceReimbursementModel();
        $claim = $claimModel->findForCurrentOrganization((int) $id);
        
        if (!$claim || $claim['status'] != 'pending') {
            return redirect()->back()->with('error', 'Klaim tidak valid atau sudah diproses.');
        }

        try {
            (new \App\Services\ApprovalService())->transitionReimbursement(
                (int) $id,
                'pending',
                'rejected',
                (int) session()->get('id_user'),
                'Klaim ditolak oleh Bendahara.'
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Klaim berhasil ditolak.');
    }

    public function cancelPaymentRequest($id)
    {
        try {
            (new \App\Services\PayrollWorkflowService())->cancelPaymentRequest(
                (int) $id,
                (int) session()->get('id_user'),
                'Payment request dibatalkan oleh Bendahara.'
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Payment request berhasil dibatalkan.');
    }

    public function retryPaymentRequest($id)
    {
        $proof = $this->request->getFile('payment_proof');
        $proofPath = null;
        if ($proof && $proof->getError() !== UPLOAD_ERR_NO_FILE) {
            if (!$proof->isValid() || $proof->hasMoved()) {
                return redirect()->back()->with('error', 'Bukti pembayaran tidak dapat dibaca.');
            }
            if (!in_array($proof->getMimeType(), ['image/jpeg', 'image/png', 'application/pdf'], true) || $proof->getSizeByUnit('mb') > 5) {
                return redirect()->back()->with('error', 'Bukti pembayaran harus berupa JPG, PNG, atau PDF maksimal 5 MB.');
            }
            $proofPath = $proof->store('payment-proofs');
        }

        try {
            $result = (new \App\Services\PayrollWorkflowService())->retryPaymentRequest(
                (int) $id,
                (int) session()->get('id_user'),
                $proofPath
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $notificationModel = new \App\Models\NotificationModel();
        foreach ($result['user_ids'] as $userId) {
            $notificationModel->insert([
                'target_role' => null,
                'target_user_id' => $userId,
                'title' => 'Payroll Berhasil Dibayarkan',
                'message' => 'Pembayaran payroll berhasil setelah proses retry.',
                'link' => '/staff/payslip',
            ]);
        }

        return redirect()->back()->with('success', 'Retry pembayaran payroll berhasil.');
    }

    public function journal()
    {
        $jurnalModel = new \App\Models\FinanceJurnalKasModel();
        
        $db = \Config\Database::connect();
        $builder = $db->table('finance_jurnal_kas');
        $builder->orderBy('tanggal', 'DESC');
        $builder->orderBy('created_at', 'DESC');
        $jurnals = $builder->get()->getResultArray();

        $saldoAkhir = $jurnalModel->getBalance();

        $data = [
            'title' => 'Jurnal Kas Internal | Fynca Analytics',
            'jurnals' => $jurnals,
            'saldo_akhir' => $saldoAkhir
        ];
        return view('finance/journal/index', $data);
    }

    public function journalStore()
    {
        $nominal = str_replace(',', '', $this->request->getPost('nominal'));
        $tipe = $this->request->getPost('tipe');

        try {
            (new \App\Services\JournalPostingService())->postCashMovement(
                (string) $tipe,
                (float) $nominal,
                (string) $this->request->getPost('deskripsi'),
                'manual',
                null,
                $this->request->getPost('tanggal')
            );
        } catch (\InvalidArgumentException | \RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Catatan jurnal berhasil ditambahkan.');
    }
}
