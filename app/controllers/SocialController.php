<?php
/**
 * Social/Community Controller
 */

class SocialController extends Controller {
    private $postModel;
    private $commentModel;
    private $notificationModel;
    
    public function __construct() {
        $this->postModel = new Post();
        $this->commentModel = new Comment();
        $this->notificationModel = new Notification();
    }
    
    public function index() {
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $posts = $this->postModel->getAll($limit, $offset);
        
        // Check which posts are liked by current user
        if (isset($_SESSION['user_id'])) {
            foreach ($posts as &$post) {
                $post['is_liked'] = $this->postModel->isLikedByUser($post['id'], $_SESSION['user_id']);
            }
        }
        
        $this->view('social/index', [
            'posts' => $posts,
            'currentPage' => $page
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contentText = $_POST['content'] ?? '';
            $postType = $_POST['post_type'] ?? 'normal';
            $relatedUserPlantId = !empty($_POST['related_plant_id']) ? intval($_POST['related_plant_id']) : null;
            $imageUrl = $_POST['image_url'] ?? null; // In real app, handle file upload
            
            if (!empty($contentText)) {
                $this->postModel->create($_SESSION['user_id'], $contentText, $imageUrl, $relatedUserPlantId, $postType);
                $this->redirect('/?controller=social&action=index');
            } else {
                $error = 'Post content cannot be empty';
                $userPlantModel = new UserPlant();
                $userPlants = $userPlantModel->findByUserId($_SESSION['user_id']);
                $this->view('social/create', ['error' => $error, 'userPlants' => $userPlants]);
            }
        } else {
            $userPlantModel = new UserPlant();
            $userPlants = $userPlantModel->findByUserId($_SESSION['user_id']);
            $this->view('social/create', ['userPlants' => $userPlants]);
        }
    }
    
    public function detail() {
        $id = intval($_GET['id'] ?? 0);
        
        if (!$id) {
            $this->redirect('/?controller=social&action=index');
            return;
        }
        
        $post = $this->postModel->findById($id);
        
        if (!$post) {
            http_response_code(404);
            require_once VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        $comments = $this->commentModel->findByPostId($id);
        $isLiked = isset($_SESSION['user_id']) ? $this->postModel->isLikedByUser($id, $_SESSION['user_id']) : false;
        $likeCount = $this->postModel->getLikeCount($id);
        
        $this->view('social/detail', [
            'post' => $post,
            'comments' => $comments,
            'isLiked' => $isLiked,
            'likeCount' => $likeCount
        ]);
    }
    
    public function like() {
        // Check if AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
            }
            exit;
        }
        
        $this->requireAuth();
        
        $postId = intval($_POST['post_id'] ?? 0);
        
        if (!$postId) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['error' => 'Post ID required']);
            }
            exit;
        }
        
        $post = $this->postModel->findById($postId);
        if (!$post) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['error' => 'Post not found']);
            }
            exit;
        }
        
        // Toggle like
        $liked = $this->postModel->toggleLike($postId, $_SESSION['user_id']);
        
        // Create notification if liked
        if ($liked && $post['user_id'] != $_SESSION['user_id']) {
            $this->notificationModel->create(
                $post['user_id'],
                NOTIF_POST_LIKED,
                $_SESSION['user_username'] . ' liked your post',
                '/?controller=social&action=detail&id=' . $postId
            );
        }
        
        // Return JSON for AJAX or redirect
        if ($isAjax) {
            header('Content-Type: application/json');
            $likeCount = $this->postModel->getLikeCount($postId);
            echo json_encode([
                'success' => true,
                'liked' => $liked,
                'like_count' => $likeCount
            ]);
        } else {
            $redirect = $_POST['redirect'] ?? '/?controller=social&action=index';
            $this->redirect($redirect);
        }
        exit;
    }
    
    public function uploadAvatar() {
        $this->requireAuth();
        
        header('Content-Type: application/json');
        
        if (!isset($_FILES['avatar'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No file uploaded']);
            exit;
        }
        
        $file = $_FILES['avatar'];
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;
        
        // Validate file
        if (!in_array($file['type'], $allowed)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid file type']);
            exit;
        }
        
        if ($file['size'] > $maxSize) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
            exit;
        }
        
        // Create uploads directory
        $uploadDir = PUBLIC_PATH . '/uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
        $filepath = $uploadDir . $filename;
        
        // Move file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $avatarUrl = '/uploads/avatars/' . $filename;
            
            // Update user avatar
            $userModel = new User();
            $userModel->updateAvatar($_SESSION['user_id'], $avatarUrl);
            
            echo json_encode([
                'success' => true,
                'avatar_url' => $avatarUrl,
                'message' => 'Avatar uploaded successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Upload failed']);
        }
        exit;
    }
    
    public function updateProfile() {
        $this->requireAuth();
        
        header('Content-Type: application/json');
        
        $username = htmlspecialchars(trim($_POST['username'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $bio = htmlspecialchars(trim($_POST['bio'] ?? ''));
        $city = htmlspecialchars(trim($_POST['city'] ?? ''));
        
        if (empty($username) || empty($email)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Username and email are required']);
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit;
        }
        
        $userModel = new User();
        $updated = $userModel->update($_SESSION['user_id'], [
            'username' => $username,
            'email' => $email,
            'bio' => $bio,
            'city' => $city
        ]);
        
        if ($updated) {
            $_SESSION['user_username'] = $username;
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
        }
        exit;
    }
    
    public function changePassword() {
        $this->requireAuth();
        
        header('Content-Type: application/json');
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'All password fields are required']);
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'New passwords do not match']);
            exit;
        }
        
        if (strlen($newPassword) < 8) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
            exit;
        }
        
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
            exit;
        }
        
        // Update password
        $updated = $userModel->updatePassword($_SESSION['user_id'], $newPassword);
        
        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to change password']);
        }
        exit;
    }
    
    public function comment() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = intval($_POST['post_id'] ?? 0);
            $contentText = $_POST['content'] ?? '';
            
            if ($postId && !empty($contentText)) {
                $post = $this->postModel->findById($postId);
                if ($post) {
                    $this->commentModel->create($postId, $_SESSION['user_id'], $contentText);
                    
                    // Create notification
                    if ($post['user_id'] != $_SESSION['user_id']) {
                        $this->notificationModel->create(
                            $post['user_id'],
                            NOTIF_POST_COMMENTED,
                            $_SESSION['user_username'] . ' commented on your post',
                            '/?controller=social&action=detail&id=' . $postId
                        );
                    }
                }
            }
        }
        
        $this->redirect('/?controller=social&action=detail&id=' . $postId);
    }
    
    public function profile() {
        $userId = intval($_GET['user_id'] ?? $_SESSION['user_id'] ?? 0);
        
        if (!$userId) {
            $this->redirect('/?controller=home&action=index');
            return;
        }
        
        $userModel = new User();
        $user = $userModel->findById($userId);
        
        if (!$user) {
            http_response_code(404);
            require_once VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        $userPlantModel = new UserPlant();
        $userPlants = $userPlantModel->findByUserId($userId);
        
        $posts = $this->postModel->findByUserId($userId);
        
        $this->view('social/profile', [
            'user' => $user,
            'userPlants' => $userPlants,
            'posts' => $posts,
            'isOwnProfile' => isset($_SESSION['user_id']) && $userId == $_SESSION['user_id']
        ]);
    }
}




