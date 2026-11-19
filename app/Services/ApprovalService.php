<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use RuntimeException;

class ApprovalService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    public function transitionReimbursement(
        int $claimId,
        string $fromStatus,
        string $toStatus,
        int $actorId,
        string $reason
    ): void {
        $builder = $this->db->table('finance_reimbursement r')
            ->select('r.id, r.status')
            ->join('users u', 'u.id_user = r.id_user')
            ->where('r.id', $claimId)
            ->where('r.status', $fromStatus);
        if (session()->get('id_organization') !== null) {
            $builder->where('u.id_organization', session()->get('id_organization'));
        }

        $claim = $builder->get()->getRowArray();
        if (!$claim) {
            throw new RuntimeException('Klaim tidak ditemukan, bukan milik organisasi aktif, atau sudah diproses.');
        }

        $this->db->transStart();
        $this->db->table('finance_reimbursement')
            ->where('id', $claimId)
            ->where('status', $fromStatus)
            ->update(['status' => $toStatus]);

        if ($this->db->affectedRows() !== 1) {
            $this->db->transRollback();
            throw new RuntimeException('Status klaim berubah saat diproses.');
        }

        $this->db->table('workflow_history')->insert([
            'entity_type' => 'reimbursement',
            'entity_id' => $claimId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'actor_id' => $actorId,
            'reason' => $reason,
            'request_key' => 'reimbursement.' . $toStatus . '.' . $claimId,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            throw new RuntimeException('Perubahan status klaim gagal disimpan.');
        }
    }
}
