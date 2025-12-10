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
        $this->requireAuth();
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = intval($_POST['post_id'] ?? 0);
            
            if ($postId) {
                $post = $this->postModel->findById($postId);
                if ($post) {
                    $liked = $this->postModel->toggleLike($postId, $_SESSION['user_id']);
                    $likeCount = $this->postModel->getLikeCount($postId);
                    
                    // Create notification if liked
                    if ($liked && $post['user_id'] != $_SESSION['user_id']) {
                        $this->notificationModel->create(
                            $post['user_id'],
                            NOTIF_POST_LIKED,
                            $_SESSION['user_username'] . ' liked your post',
                            '/?controller=social&action=detail&id=' . $postId
                        );
                    }
                    
                    // Return JSON for AJAX requests
                    if ($isAjax) {
                        $this->json([
                            'success' => true,
                            'liked' => $liked,
                            'likeCount' => $likeCount
                        ]);
                        return;
                    }
                }
            }
        }
        
        // Fallback to redirect for non-AJAX requests
        $redirect = $_POST['redirect'] ?? '/?controller=social&action=index';
        // Remove BASE_URL if it's already in the redirect
        if (strpos($redirect, BASE_URL) === 0) {
            $redirect = str_replace(BASE_URL, '', $redirect);
        }
        $this->redirect($redirect);
    }
    
    public function comment() {
        $this->requireAuth();
        
        $postId = intval($_POST['post_id'] ?? $_GET['id'] ?? 0);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contentText = trim($_POST['content'] ?? '');
            
            if ($postId && !empty($contentText)) {
                $post = $this->postModel->findById($postId);
                if ($post) {
                    if ($this->commentModel->create($postId, $_SESSION['user_id'], $contentText)) {
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
            
            // Always redirect back to the post detail page
            if ($postId) {
                $this->redirect('/?controller=social&action=detail&id=' . $postId);
            } else {
                $this->redirect('/?controller=social&action=index');
            }
            return;
        }
        
        // If not POST, redirect to index
        $this->redirect('/?controller=social&action=index');
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



