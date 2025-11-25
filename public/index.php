<?php
/**
 * Front Controller - Entry Point
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

// Simple routing
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

$controllerName = ucfirst($controller) . 'Controller';

if (class_exists($controllerName)) {
    $controllerInstance = new $controllerName();
    if (method_exists($controllerInstance, $action)) {
        call_user_func([$controllerInstance, $action]);
    } else {
        http_response_code(404);
        require_once VIEWS_PATH . '/errors/404.php';
    }
} else {
    http_response_code(404);
    require_once VIEWS_PATH . '/errors/404.php';
}

