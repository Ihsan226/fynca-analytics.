<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'target_role',
        'target_user_id',
        'title',
        'message',
        'link',
        'is_read'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get unread notifications for a specific user based on their ID and role.
     */
    public function getUnreadForUser($userId, $role, $limit = 5)
    {
        return $this->groupStart()
                        ->where('target_user_id', $userId)
                        ->orWhere('target_role', $role)
                    ->groupEnd()
                    ->where('is_read', 0)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }

    /**
     * Get count of unread notifications for a user.
     */
    public function countUnreadForUser($userId, $role)
    {
        return $this->groupStart()
                        ->where('target_user_id', $userId)
                        ->orWhere('target_role', $role)
                    ->groupEnd()
                    ->where('is_read', 0)
                    ->countAllResults();
    }
}
