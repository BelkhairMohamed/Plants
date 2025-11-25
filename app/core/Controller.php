<?php
/**
 * Base Controller
 */

class Controller {
    protected function view($viewName, $data = []) {
        extract($data);
        require_once VIEWS_PATH . '/layouts/header.php';
        require_once VIEWS_PATH . '/' . $viewName . '.php';
        require_once VIEWS_PATH . '/layouts/footer.php';
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/?controller=auth&action=login');
        }
    }
    
    protected function requireAdmin() {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== ROLE_ADMIN) {
            $this->redirect('/?controller=home&action=index');
        }
    }
    
    protected function getCurrentUser() {
        if (isset($_SESSION['user_id'])) {
            $userModel = new User();
            return $userModel->findById($_SESSION['user_id']);
        }
        return null;
    }
}



