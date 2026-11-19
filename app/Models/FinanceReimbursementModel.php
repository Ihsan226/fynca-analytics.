<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceReimbursementModel extends Model
{
    protected $table            = 'finance_reimbursement';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user', 'tanggal', 'deskripsi', 
        'nominal', 'bukti_file', 'payment_proof', 'paid_at', 'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getClaimsWithUser()
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table . ' r');
        $builder->select('r.*, u.nama_lengkap, u.role');
        $builder->join('users u', 'r.id_user = u.id_user', 'left');
        if (session()->get('id_organization') !== null) {
            $builder->where('u.id_organization', session()->get('id_organization'));
        }
        $builder->orderBy('r.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function findForCurrentOrganization(int $id): ?array
    {
        $builder = \Config\Database::connect()->table($this->table . ' r')
            ->select('r.*')
            ->join('users u', 'u.id_user = r.id_user')
            ->where('r.id', $id);
        if (session()->get('id_organization') !== null) {
            $builder->where('u.id_organization', session()->get('id_organization'));
        }

        return $builder->get()->getRowArray() ?: null;
    }
}
