<?php

namespace App\Controllers;

class ExecutiveFinance extends BaseController
{
    public function approvals()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('finance_payroll p');
        $builder->select('p.periode_bulan, p.periode_tahun, COUNT(p.id_user) as total_karyawan, SUM(p.gaji_pokok) as sum_gp, SUM(p.tunjangan) as sum_tj, SUM(p.bonus) as sum_bn, SUM(p.potongan_bpjs) as sum_pb, SUM(p.potongan_lain) as sum_pl, SUM(p.total_bersih) as sum_thp');
        $builder->join('users u', 'u.id_user = p.id_user');
        $builder->where('p.status', 'pending_approval');
        if (session()->get('id_organization') !== null) {
            $builder->where('u.id_organization', session()->get('id_organization'));
        }
        $builder->groupBy('p.periode_bulan, p.periode_tahun');
        $builder->orderBy('p.periode_tahun', 'DESC');
        $builder->orderBy('p.periode_bulan', 'DESC');
        
        $pendingBatches = $builder->get()->getResultArray();

        $data = [
            'title' => 'Finance Approvals | Fynca Analytics',
            'pendingBatches' => $pendingBatches
        ];
        return view('executive/finance_approvals', $data);
    }

    public function approvePayroll($bulan, $tahun)
    {
        try {
            (new \App\Services\PayrollWorkflowService())->approveBatch(
                (int) $bulan,
                (int) $tahun,
                (int) session()->get('id_user')
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $db = \Config\Database::connect();
        $treasurerQuery = $db->table('users')
            ->select('id_user')
            ->where('role', 'bendahara');
        if (session()->get('id_organization') !== null) {
            $treasurerQuery->where('id_organization', session()->get('id_organization'));
        }

        $notificationModel = new \App\Models\NotificationModel();
        foreach ($treasurerQuery->get()->getResultArray() as $treasurer) {
            $notificationModel->insert([
                'target_role' => null,
                'target_user_id' => $treasurer['id_user'],
                'title' => 'Payroll Siap Dicairkan',
                'message' => 'Payroll periode ' . sprintf('%02d/%d', $bulan, $tahun) . ' telah disetujui Owner dan menunggu pencairan.',
                'link' => '/finance/recap-payroll?bulan=' . (int) $bulan . '&tahun=' . (int) $tahun
            ]);
        }

        return redirect()->back()->with('success', 'Payroll berhasil disetujui dan diteruskan ke Bendahara untuk pencairan.');
    }

    public function rejectPayroll($bulan, $tahun)
    {
        try {
            (new \App\Services\PayrollWorkflowService())->rejectBatch(
                (int) $bulan,
                (int) $tahun,
                (int) session()->get('id_user'),
                'Payroll ditolak oleh Owner dan dikembalikan untuk revisi.'
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Payroll ditolak dan dikembalikan ke Bendahara sebagai draf.');
    }
}
