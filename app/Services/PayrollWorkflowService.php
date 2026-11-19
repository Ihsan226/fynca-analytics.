<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use RuntimeException;

class PayrollWorkflowService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    /**
     * @return array{count: int, total: float, user_ids: list<int>}
     */
    public function approveBatch(int $month, int $year, int $actorId): array
    {
        $rows = $this->getBatch($month, $year, 'pending_approval');

        if ($rows === []) {
            throw new RuntimeException('Payroll tidak ditemukan atau sudah diproses.');
        }

        return $this->transitionBatch(
            $rows,
            'approved',
            $actorId,
            'Payroll disetujui Owner.',
            'payroll.approve.' . $month . '.' . $year
        );
    }

    /**
     * @return array{count: int, total: float, user_ids: list<int>}
     */
    public function rejectBatch(int $month, int $year, int $actorId, ?string $reason = null): array
    {
        $rows = $this->getBatch($month, $year, 'pending_approval');

        if ($rows === []) {
            throw new RuntimeException('Payroll tidak ditemukan atau sudah diproses.');
        }

        return $this->transitionBatch(
            $rows,
            'draft',
            $actorId,
            $reason ?: 'Payroll dikembalikan untuk revisi.',
            'payroll.reject.' . $month . '.' . $year
        );
    }

    /**
     * @return array{count: int, total: float, user_ids: list<int>}
     */
    public function payBatch(int $month, int $year, int $actorId, ?string $paymentProof = null): array
    {
        $rows = $this->getBatch($month, $year, 'approved');

        if ($rows === []) {
            throw new RuntimeException('Payroll tidak ditemukan atau belum disetujui Owner.');
        }

        $paymentRequestId = $this->createPaymentRequest($month, $year, $actorId, (float) array_sum(array_column($rows, 'total_bersih')));

        try {
            return $this->executePayment(
                $rows,
                $paymentRequestId,
                $actorId,
                'Pembayaran payroll dikonfirmasi oleh Bendahara.',
                $paymentProof
            );
        } catch (RuntimeException $exception) {
            if ($this->db->transDepth() > 0) {
                $this->db->transRollback();
            }
            $this->markPaymentFailed($paymentRequestId, $exception->getMessage());
            throw $exception;
        }
    }

    public function cancelPaymentRequest(int $paymentRequestId, int $actorId, string $reason): void
    {
        $request = $this->db->table('payment_requests')
            ->where('id', $paymentRequestId)
            ->where('entity_type', 'payroll')
            ->whereIn('status', ['requested', 'processing'])
            ->get()
            ->getRowArray();

        if (!$request) {
            throw new RuntimeException('Payment request tidak ditemukan atau sudah selesai.');
        }

        $this->db->transStart();
        $this->db->table('payment_requests')->where('id', $paymentRequestId)->update([
            'status' => 'cancelled',
            'executed_by' => $actorId,
            'failure_reason' => $reason,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $this->db->table('workflow_history')->insert([
            'entity_type' => 'payment_request',
            'entity_id' => $paymentRequestId,
            'from_status' => $request['status'],
            'to_status' => 'cancelled',
            'actor_id' => $actorId,
            'reason' => $reason,
            'request_key' => 'payment.cancel.' . $paymentRequestId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            throw new RuntimeException('Payment request gagal dibatalkan.');
        }
    }

    /**
     * @return array{count: int, total: float, user_ids: list<int>, payment_request_id: int}
     */
    public function retryPaymentRequest(int $paymentRequestId, int $actorId, ?string $paymentProof = null): array
    {
        $request = $this->db->table('payment_requests')
            ->where('id', $paymentRequestId)
            ->where('entity_type', 'payroll')
            ->where('status', 'failed')
            ->get()
            ->getRowArray();

        if (!$request) {
            throw new RuntimeException('Payment request gagal tidak ditemukan atau belum siap untuk retry.');
        }

        $rows = $this->getBatch(
            (int) $request['entity_period_month'],
            (int) $request['entity_period_year'],
            'approved'
        );
        if ($rows === []) {
            throw new RuntimeException('Payroll untuk payment request ini tidak lagi berstatus approved.');
        }

        $this->db->table('payment_requests')->where('id', $paymentRequestId)->update([
            'status' => 'processing',
            'failure_reason' => null,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        try {
            return $this->executePayment(
                $rows,
                $paymentRequestId,
                $actorId,
                'Retry pembayaran payroll berhasil dikonfirmasi oleh Bendahara.',
                $paymentProof
            );
        } catch (RuntimeException $exception) {
            if ($this->db->transDepth() > 0) {
                $this->db->transRollback();
            }
            $this->db->table('payment_requests')->where('id', $paymentRequestId)->update([
                'status' => 'failed',
                'failure_reason' => $exception->getMessage(),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            throw $exception;
        }
    }

    /**
     * @return array{count: int, total: float, user_ids: list<int>, payment_request_id: int}
     */
    public function executePayment(array $rows, int $paymentRequestId, int $actorId, string $reason, ?string $paymentProof = null): array
    {
        $paymentRequest = $this->db->table('payment_requests')
            ->where('id', $paymentRequestId)
            ->where('entity_type', 'payroll')
            ->get()
            ->getRowArray();

        if (!$paymentRequest || !in_array($paymentRequest['status'], ['requested', 'processing'], true)) {
            throw new RuntimeException('Payment request tidak ditemukan atau sudah diproses.');
        }

        $this->db->transStart();
        $result = $this->transitionBatch(
            $rows,
            'paid',
            $actorId,
            $reason,
            'payroll.payment.' . $paymentRequestId,
            false
        );

        $this->db->table('payment_requests')
            ->where('id', $paymentRequestId)
            ->update([
                'status' => 'paid',
                'executed_by' => $actorId,
                'payment_proof' => $paymentProof,
                'paid_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        (new JournalPostingService($this->db))->postCashMovement(
            'kredit',
            $result['total'],
            'Pencairan Payroll Periode ' . $paymentRequest['entity_period_month'] . '/' . $paymentRequest['entity_period_year'],
            'payment_request',
            $paymentRequestId
        );
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            throw new RuntimeException('Pembayaran payroll gagal diselesaikan.');
        }

        $result['payment_request_id'] = $paymentRequestId;
        return $result;
    }

    /**
     * @return int
     */
    private function createPaymentRequest(int $month, int $year, int $actorId, float $amount): int
    {
        $organizationId = session()->get('id_organization');
        $query = $this->db->table('payment_requests')
            ->where('entity_type', 'payroll')
            ->where('entity_period_month', $month)
            ->where('entity_period_year', $year);
        if ($organizationId !== null) {
            $query->where('id_organization', $organizationId);
        } else {
            $query->where('id_organization IS NULL', null, false);
        }

        $existing = $query->get()->getRowArray();
        if ($existing) {
            if ($existing['status'] === 'paid') {
                throw new RuntimeException('Payment payroll periode ini sudah selesai.');
            }
            return (int) $existing['id'];
        }

        $this->db->table('payment_requests')->insert([
            'entity_type' => 'payroll',
            'id_organization' => $organizationId,
            'entity_period_month' => $month,
            'entity_period_year' => $year,
            'amount' => $amount,
            'status' => 'processing',
            'requested_by' => $actorId,
            'requested_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return (int) $this->db->insertID();
    }

    private function markPaymentFailed(int $paymentRequestId, string $reason): void
    {
        $this->db->table('payment_requests')->where('id', $paymentRequestId)->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return list<array{id: int, id_user: int, status: string, total_bersih: float}>
     */
    private function getBatch(int $month, int $year, string $status): array
    {
        $builder = $this->db->table('finance_payroll p')
            ->select('p.id, p.id_user, p.status, p.total_bersih')
            ->join('users u', 'u.id_user = p.id_user')
            ->where('p.periode_bulan', $month)
            ->where('p.periode_tahun', $year)
            ->where('p.status', $status);

        $organizationId = session()->get('id_organization');
        if ($organizationId !== null) {
            $builder->where('u.id_organization', $organizationId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * @param list<array{id: int, id_user: int, status: string, total_bersih: float}> $rows
     * @return array{count: int, total: float, user_ids: list<int>}
     */
    private function transitionBatch(
        array $rows,
        string $targetStatus,
        int $actorId,
        string $reason,
        string $requestKey,
        bool $manageTransaction = true
    ): array {
        if ($manageTransaction) {
            $this->db->transStart();
        }

        $ids = array_column($rows, 'id');
        $this->db->table('finance_payroll')
            ->whereIn('id', $ids)
            ->where('status', $rows[0]['status'])
            ->update(['status' => $targetStatus]);

        if ($this->db->affectedRows() !== count($rows)) {
            $this->db->transRollback();
            throw new RuntimeException('Payroll berubah saat diproses. Silakan muat ulang halaman.');
        }

        foreach ($rows as $row) {
            $this->db->table('workflow_history')->insert([
                'entity_type' => 'payroll',
                'entity_id' => $row['id'],
                'from_status' => $row['status'],
                'to_status' => $targetStatus,
                'actor_id' => $actorId,
                'reason' => $reason,
                'request_key' => $requestKey . '.' . $row['id'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($manageTransaction) {
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new RuntimeException('Perubahan status payroll gagal disimpan.');
            }
        }

        return [
            'count' => count($rows),
            'total' => (float) array_sum(array_column($rows, 'total_bersih')),
            'user_ids' => array_values(array_unique(array_map('intval', array_column($rows, 'id_user')))),
        ];
    }
}
