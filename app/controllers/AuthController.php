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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                
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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                
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
}

