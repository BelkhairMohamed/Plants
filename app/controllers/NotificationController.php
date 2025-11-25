<?php
/**
 * Notification Controller
 */

class NotificationController extends Controller {
    private $notificationModel;
    
    public function __construct() {
        $this->requireAuth();
        $this->notificationModel = new Notification();
    }
    
    public function index() {
        $unreadOnly = isset($_GET['unread']) && $_GET['unread'] == '1';
        $notifications = $this->notificationModel->findByUserId($_SESSION['user_id'], $unreadOnly);
        
        $this->view('notification/index', [
            'notifications' => $notifications,
            'unreadOnly' => $unreadOnly
        ]);
    }
    
    public function markRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            if ($id) {
                $this->notificationModel->markAsRead($id);
            }
        }
        
        $this->json(['success' => true]);
    }
    
    public function markAllRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->notificationModel->markAllAsRead($_SESSION['user_id']);
        }
        
        $this->json(['success' => true]);
    }
    
    public function getUnreadCount() {
        $count = $this->notificationModel->getUnreadCount($_SESSION['user_id']);
        $this->json(['count' => $count]);
    }
}




