<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

class GeneralLedgerService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function account(int $companyId, int $coaId, string $from, string $to): array
    {
        return $this->db->table('acc_journal_items i')
            ->select('j.tanggal, j.referensi, j.deskripsi, i.debit, i.credit, (i.debit - i.credit) AS movement')
            ->join('acc_journals j', 'j.id_journal = i.id_journal')
            ->where('j.id_perusahaan', $companyId)
            ->where('i.id_coa', $coaId)
            ->where('j.status', 'posted')
            ->where('j.tanggal >=', $from)
            ->where('j.tanggal <=', $to)
            ->orderBy('j.tanggal', 'ASC')
            ->orderBy('j.id_journal', 'ASC')
            ->get()->getResultArray();
    }
}
