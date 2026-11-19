<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use InvalidArgumentException;
use RuntimeException;

class JournalPostingService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    public function postCashMovement(
        string $type,
        float $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $date = null
    ): int {
        if (!in_array($type, ['debit', 'kredit'], true)) {
            throw new InvalidArgumentException('Tipe jurnal kas tidak valid.');
        }

        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal jurnal harus lebih besar dari nol.');
        }

        if ($referenceType !== null && $referenceId !== null) {
            $existing = $this->db->table('finance_jurnal_kas')
                ->select('id')
                ->where('referensi_tipe', $referenceType)
                ->where('referensi_id', $referenceId)
                ->get()
                ->getRowArray();

            if ($existing) {
                return (int) $existing['id'];
            }
        }

        $inserted = $this->db->table('finance_jurnal_kas')->insert([
            'tanggal' => $date ?: date('Y-m-d'),
            'tipe' => $type,
            'nominal' => $amount,
            'deskripsi' => $description,
            'referensi_tipe' => $referenceType,
            'referensi_id' => $referenceId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (!$inserted) {
            throw new RuntimeException('Jurnal kas gagal diposting.');
        }

        return (int) $this->db->insertID();
    }

    /**
     * @param list<array{id_coa:int, debit:float, credit:float}> $lines
     */
    public function postJournal(
        int $companyId,
        string $date,
        string $description,
        array $lines,
        ?string $reference = null
    ): int {
        (new AccountingPeriodService($this->db))->assertOpen($companyId, $date);
        if ($lines === []) {
            throw new InvalidArgumentException('Jurnal harus memiliki minimal satu baris.');
        }

        $totalDebit = array_sum(array_column($lines, 'debit'));
        $totalCredit = array_sum(array_column($lines, 'credit'));
        if ($totalDebit <= 0 || abs($totalDebit - $totalCredit) > 0.005) {
            throw new InvalidArgumentException('Jurnal tidak seimbang atau bernilai nol.');
        }

        $this->db->transStart();
        $this->db->table('acc_journals')->insert([
            'id_perusahaan' => $companyId,
            'tanggal' => $date,
            'deskripsi' => $description,
            'referensi' => $reference,
            'status' => 'posted',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $journalId = (int) $this->db->insertID();
        foreach ($lines as $line) {
            if ((float) $line['debit'] < 0 || (float) $line['credit'] < 0 || ((float) $line['debit'] > 0 && (float) $line['credit'] > 0)) {
                $this->db->transRollback();
                throw new InvalidArgumentException('Baris jurnal memiliki debit/kredit tidak valid.');
            }
            $this->db->table('acc_journal_items')->insert([
                'id_journal' => $journalId,
                'id_coa' => (int) $line['id_coa'],
                'debit' => (float) $line['debit'],
                'credit' => (float) $line['credit'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        $this->db->transComplete();
        if ($this->db->transStatus() === false) {
            throw new RuntimeException('Jurnal gagal diposting.');
        }
        return $journalId;
    }

    public function reverseJournal(int $journalId, int $actorId, string $reason): int
    {
        $journal = $this->db->table('acc_journals')->where('id_journal', $journalId)->where('status', 'posted')->get()->getRowArray();
        if (!$journal) {
            throw new RuntimeException('Jurnal posted tidak ditemukan.');
        }

        $items = $this->db->table('acc_journal_items')
            ->where('id_journal', $journalId)
            ->get()->getResultArray();
        $lines = [];
        foreach ($items as $item) {
            $lines[] = [
                'id_coa' => (int) $item['id_coa'],
                'debit' => (float) $item['credit'],
                'credit' => (float) $item['debit'],
            ];
        }

        return $this->postJournal(
            (int) $journal['id_perusahaan'],
            date('Y-m-d'),
            'Reversal: ' . ($journal['deskripsi'] ?? '') . ' - ' . $reason,
            $lines,
            'reversal:' . $journalId . ':actor:' . $actorId
        );
    }
}
