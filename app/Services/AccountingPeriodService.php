<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use RuntimeException;

class AccountingPeriodService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    public function close(int $periodId, int $actorId): void
    {
        $period = $this->db->table('accounting_periods')->where('id', $periodId)->get()->getRowArray();
        if (!$period || $period['status'] !== 'open') {
            throw new RuntimeException('Periode tidak ditemukan atau sudah ditutup.');
        }

        $trialBalance = (new TrialBalanceService($this->db))->forPeriod(
            (int) $period['id_perusahaan'],
            $period['period_start'],
            $period['period_end']
        );
        if (!$trialBalance['balanced']) {
            throw new RuntimeException('Periode tidak dapat ditutup karena Trial Balance tidak seimbang.');
        }

        $this->db->table('accounting_periods')->where('id', $periodId)->update([
            'status' => 'closed',
            'closed_by' => $actorId,
            'closed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function assertOpen(int $companyId, string $date): void
    {
        $closed = $this->db->table('accounting_periods')
            ->where('id_perusahaan', $companyId)
            ->where('status', 'closed')
            ->where('period_start <=', $date)
            ->where('period_end >=', $date)
            ->countAllResults();
        if ($closed > 0) {
            throw new RuntimeException('Tanggal transaksi berada pada periode yang sudah ditutup.');
        }
    }
}
