<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * Get unread notifications for the currently logged-in user
     */
    public function getUnread()
    {
        $userId = session()->get('id_user');
        $role   = session()->get('role');

        if (!$userId || !$role) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $notifications = $this->notificationModel->getUnreadForUser($userId, $role);
        $count         = $this->notificationModel->countUnreadForUser($userId, $role);

        return $this->response->setJSON([
            'status'        => 'success',
            'count'         => $count,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $userId = session()->get('id_user');
        $role   = session()->get('role');

        if (!$userId || !$role) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        // Verify ownership/role match
        $notification = $this->notificationModel->find($id);
        if ($notification) {
            if ($notification['target_user_id'] == $userId || $notification['target_role'] == $role) {
                $this->notificationModel->update($id, ['is_read' => 1]);
                return $this->response->setJSON(['status' => 'success']);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Notification not found or access denied']);
    }

    /**
     * Mark all notifications as read for current user
     */
    public function markAllAsRead()
    {
        $userId = session()->get('id_user');
        $role   = session()->get('role');

        if (!$userId || !$role) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $this->notificationModel
            ->groupStart()
                ->where('target_user_id', $userId)
                ->orWhere('target_role', $role)
            ->groupEnd()
            ->where('is_read', 0)
            ->set(['is_read' => 1])
            ->update();

        return $this->response->setJSON(['status' => 'success']);
    }
}
