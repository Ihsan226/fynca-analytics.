<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

class TrialBalanceService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    /**
     * @return array{accounts:list<array<string,mixed>>, total_debit:float, total_credit:float, balanced:bool}
     */
    public function forPeriod(int $companyId, string $from, string $to): array
    {
        $accounts = $this->db->table('acc_coa c')
            ->select('c.id_coa, c.kode_akun, c.nama_akun, c.tipe_akun, SUM(i.debit) AS total_debit, SUM(i.credit) AS total_credit')
            ->join('acc_journal_items i', 'i.id_coa = c.id_coa', 'left')
            ->join('acc_journals j', "j.id_journal = i.id_journal AND j.id_perusahaan = " . $this->db->escape($companyId) . " AND j.status = 'posted' AND j.tanggal >= " . $this->db->escape($from) . " AND j.tanggal <= " . $this->db->escape($to), 'left')
            ->groupBy('c.id_coa')
            ->orderBy('c.kode_akun', 'ASC')
            ->get()->getResultArray();

        $debit = 0.0;
        $credit = 0.0;
        foreach ($accounts as &$account) {
            $account['total_debit'] = (float) ($account['total_debit'] ?? 0);
            $account['total_credit'] = (float) ($account['total_credit'] ?? 0);
            $debit += $account['total_debit'];
            $credit += $account['total_credit'];
        }

        return [
            'accounts' => $accounts,
            'total_debit' => $debit,
            'total_credit' => $credit,
            'balanced' => abs($debit - $credit) <= 0.005,
        ];
    }
}
