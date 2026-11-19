<?php

namespace App\Controllers;

class StaffFinance extends BaseController
{
    public function claims()
    {
        $claimModel = new \App\Models\FinanceReimbursementModel();
        $id_user = session()->get('id_user');
        
        $claims = $claimModel->where('id_user', $id_user)
                             ->orderBy('created_at', 'DESC')
                             ->findAll();

        $data = [
            'title' => 'Klaim / Reimbursement | Fynca Analytics',
            'claims' => $claims
        ];
        return view('staff/claims', $data);
    }

    public function claimsStore()
    {
        $claimModel = new \App\Models\FinanceReimbursementModel();
        
        $claimModel->insert([
            'id_user' => session()->get('id_user'),
            'tanggal' => $this->request->getPost('tanggal'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'nominal' => str_replace(',', '', $this->request->getPost('nominal')),
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Klaim berhasil diajukan dan sedang direview oleh Bendahara.');
    }

    public function payslip()
    {
        $data = [
            'title' => 'Slip Gaji | Fynca Analytics'
        ];
        return view('staff/payslip', $data);
    }

    public function payslipDownload($id)
    {
        // TODO: Generate PDF logic for specific payslip
        return redirect()->back()->with('error', 'Fitur download PDF belum diimplementasi.');
    }
}
