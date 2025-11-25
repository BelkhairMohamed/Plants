<?php
/**
 * Authentication Controller
 */

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        // Session is already started in public/index.php
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->findByEmail($email);
            
            if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                // Save session cart before login (if exists)
                $sessionCart = $_SESSION['cart'] ?? [];
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                
                // Sync session cart to database
                if (!empty($sessionCart)) {
                    $cartModel = new Cart();
                    $cartModel->syncFromSession($user['id'], $sessionCart);
                }
                
                // Load cart from database into session for compatibility
                $cartModel = new Cart();
                $_SESSION['cart'] = $cartModel->toSessionFormat($user['id']);
                
                $this->redirect('/?controller=dashboard&action=index');
            } else {
                $error = 'Invalid email or password';
                $this->view('auth/login', ['error' => $error]);
            }
        } else {
            $this->view('auth/login');
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $username = $_POST['username'] ?? '';
            $bio = $_POST['bio'] ?? null;
            
            // Validation
            if (empty($email) || empty($password) || empty($username)) {
                $error = 'All required fields must be filled';
                $this->view('auth/register', ['error' => $error]);
                return;
            }
            
            // Check if email exists
            if ($this->userModel->findByEmail($email)) {
                $error = 'Email already registered';
                $this->view('auth/register', ['error' => $error]);
                return;
            }
            
            // Create user
            if ($this->userModel->create($email, $password, $username, $bio)) {
                // Auto login
                $user = $this->userModel->findByEmail($email);
                
                // Save session cart before registration (if exists)
                $sessionCart = $_SESSION['cart'] ?? [];
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                
                // Sync session cart to database
                if (!empty($sessionCart)) {
                    $cartModel = new Cart();
                    $cartModel->syncFromSession($user['id'], $sessionCart);
                }
                
                // Load cart from database into session for compatibility
                $cartModel = new Cart();
                $_SESSION['cart'] = $cartModel->toSessionFormat($user['id']);
                
                $this->redirect('/?controller=dashboard&action=index');
            } else {
                $error = 'Registration failed. Please try again.';
                $this->view('auth/register', ['error' => $error]);
            }
        } else {
            $this->view('auth/register');
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/?controller=home&action=index');
    }
    
    public function profile() {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle profile picture upload
            if (isset($_POST['update_avatar']) && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = PUBLIC_PATH . '/uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileExtension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $fileExtension;
                    $filePath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath)) {
                        $avatarUrl = BASE_URL . '/public/uploads/avatars/' . $fileName;
                        
                        // Delete old avatar if exists
                        if (!empty($user['avatar_url']) && strpos($user['avatar_url'], '/uploads/avatars/') !== false) {
                            $oldFile = PUBLIC_PATH . str_replace(BASE_URL, '', $user['avatar_url']);
                            if (file_exists($oldFile)) {
                                @unlink($oldFile);
                            }
                        }
                        
                        $this->userModel->update($_SESSION['user_id'], ['avatar_url' => $avatarUrl]);
                        $user['avatar_url'] = $avatarUrl;
                    }
                }
            }
            
            // Handle profile update
            if (isset($_POST['update_profile'])) {
                $updateData = [];
                if (!empty($_POST['username'])) {
                    $updateData['username'] = $_POST['username'];
                }
                if (isset($_POST['bio'])) {
                    $updateData['bio'] = $_POST['bio'];
                }
                if (!empty($_POST['city'])) {
                    $updateData['city'] = $_POST['city'];
                }
                
                if (!empty($updateData)) {
                    $this->userModel->update($_SESSION['user_id'], $updateData);
                    $_SESSION['user_username'] = $updateData['username'] ?? $_SESSION['user_username'];
                    $user = $this->userModel->findById($_SESSION['user_id']);
                }
            }
            
            // Handle password change
            if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && isset($_POST['change_password'])) {
                if ($_POST['new_password'] !== ($_POST['confirm_password'] ?? '')) {
                    $error = 'Les nouveaux mots de passe ne correspondent pas';
                } elseif (strlen($_POST['new_password']) < 6) {
                    $error = 'Le nouveau mot de passe doit contenir au moins 6 caractères';
                } elseif ($this->userModel->verifyPassword($_POST['current_password'], $user['password'])) {
                    $this->userModel->updatePassword($_SESSION['user_id'], $_POST['new_password']);
                    $success = 'Mot de passe modifié avec succès';
                } else {
                    $error = 'Mot de passe actuel incorrect';
                }
            }
            
            if (!isset($error)) {
                $success = $success ?? 'Profil mis à jour avec succès';
            }
        }
        
        $this->view('auth/profile', [
            'user' => $user,
            'error' => $error ?? null,
            'success' => $success ?? null
        ]);
    }
}

