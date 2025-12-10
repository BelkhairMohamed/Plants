<?php
/**
 * Simple Router for MVC
 */

class Router {
    private $routes = [];
    
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = str_replace('/Plants', '', $uri);
        $uri = rtrim($uri, '/') ?: '/';
        
        // Try to match route
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri, $params)) {
                $controllerName = $route['controller'] . 'Controller';
                $action = $route['action'];
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $action)) {
                        call_user_func_array([$controller, $action], $params);
                        return;
                    }
                }
            }
        }
        
        // Fallback to query string routing
        $controller = $_GET['controller'] ?? 'Home';
        $action = $_GET['action'] ?? 'index';
        
        $controllerName = ucfirst($controller) . 'Controller';
        
        if (class_exists($controllerName)) {
            $controllerInstance = new $controllerName();
            if (method_exists($controllerInstance, $action)) {
                call_user_func([$controllerInstance, $action]);
                return;
            }
        }
        
        // 404
        http_response_code(404);
        require_once VIEWS_PATH . '/errors/404.php';
    }
    
    private function matchPath($pattern, $uri, &$params) {
        $params = [];
        $pattern = '#^' . preg_replace('#\{(\w+)\}#', '([^/]+)', $pattern) . '$#';
        return preg_match($pattern, $uri, $matches) && array_shift($matches) && ($params = $matches);
    }
}



