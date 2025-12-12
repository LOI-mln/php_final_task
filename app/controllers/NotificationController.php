<?php
namespace App\Controllers;

use App\Models\Notification;

class NotificationController extends BaseController
{

    private $notifModel;

    public function __construct()
    {
        $this->notifModel = new Notification();
    }

    // Creating a page for notifications
    public function index()
    {
        $this->requireAuth();
        // Since getUnread fetches only unread, maybe we want all notifications or history?
        // Requirement says "page pour les voir". Usually history is nice.
        // But for simplicity let's stick to unread or last 20.
        // Let's implement a 'getAllByUser' in model if needed, but 'getUnread' + fetch is fine for now.
        // Or simply get unread for polling and index for history.
        // I'll assume index displays unread for now or implement getAll later.
        $notifications = $this->notifModel->getUnread($this->getCurrentUserId());
        $this->render('user/notifications', ['notifications' => $notifications]);
    }

    // AJAX Polling
    public function check()
    {
        if (!$this->isAuthenticated()) {
            $this->jsonResponse(['count' => 0, 'notifications' => []]);
        }

        $notifications = $this->notifModel->getUnread($this->getCurrentUserId());
        $this->jsonResponse([
            'count' => count($notifications),
            'notifications' => $notifications
        ]);
    }

    public function markRead()
    {
        $this->requireAuth();
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input && isset($input['id'])) {
            $this->notifModel->markAsRead($input['id']);
            $this->jsonResponse(['status' => 'success']);
        }
    }
}
