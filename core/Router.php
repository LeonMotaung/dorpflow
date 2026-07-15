<?php
/**
 * DorpFlow ERP - Custom Lightweight Router
 */

class Router {
    protected $routes = [];

    /**
     * Add a route with target controller, action, and method
     */
    public function add($route, $controller, $action, $method = 'GET') {
        // Convert route like '/tickets/view/{id}' to regex
        $routeRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $route);
        $routeRegex = '#^' . trim($routeRegex, '/') . '$#i';
        
        $this->routes[] = [
            'pattern' => $routeRegex,
            'controller' => $controller,
            'action' => $action,
            'method' => $method
        ];
    }

    /**
     * Dispatch the current request URI
     */
    public function dispatch($url) {
        $url = trim(parse_url($url, PHP_URL_PATH), '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Remove base folder from path if present (e.g. dorpflow/public/index.php)
        $scriptName = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if (!empty($scriptName) && strpos($url, $scriptName) === 0) {
            $url = trim(substr($url, strlen($scriptName)), '/');
        }

        // Remove script filename (index.php) from URL if present
        if (strpos($url, 'index.php') === 0) {
            $url = trim(substr($url, strlen('index.php')), '/');
        }

        // If URL is empty, default to home page
        if (empty($url)) {
            $url = '';
        }

        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $url, $matches) && $requestMethod === $route['method']) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];

                // Extract named query parameters
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = $value;
                    }
                }

                // Check if controller exists
                $controllerFile = ROOT_PATH . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controllerInstance = new $controllerName();
                    
                    // Call controller action with parameters
                    call_user_func_array([$controllerInstance, $actionName], [$params]);
                    return;
                } else {
                    $this->abort("Controller $controllerName not found.", 500);
                    return;
                }
            }
        }

        $this->abort("Page not found ($url).", 404);
    }

    /**
     * Render HTTP Error Pages
     */
    protected function abort($message = '', $code = 404) {
        http_response_code($code);
        echo "<h1>Error $code</h1>";
        echo "<p>$message</p>";
        echo "<hr><p>DorpFlow ERP Enterprise System Gateway</p>";
        exit;
    }
}
