<?php

namespace App\Core;

use RuntimeException;

/**
 * Class Router
 * 
 * Handles HTTP request routing in the application.
 */
class Router
{
    /**
     * @var array $routes Stores the defined routes.
     */
    private array $routes = [];
    
    /**
     * @var string $namespace Default namespace for controllers.
     */
    private string $namespace = 'App\\Controllers\\';
    
    /**
     * @var Request $request The current HTTP request instance.
     */
    private Request $request;

    /**
     * Adds a route to the router.
     *
     * @param string $method HTTP method (GET, POST, etc.).
     * @param string $path Route path with optional parameters.
     * @param string $handler Controller and method in 'Controller@method' format.
     * 
     * @return void
     */
    public function add($method, $path, $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
        $this->request = new Request();
    }

    // 'GET', '/home', 'HomeController@index'

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);
                $args = array_values($matches);
                
                // Extract controller and method
                $handlerName = explode('@', $route['handler']);
                if (count($handlerName) !== 2) {
                    throw new RuntimeException("Invalid handler format. Expected 'Controller@method'.");
                }
                
                $className = $this->namespace . $handlerName[0];
                $methodName = $handlerName[1];

                if (class_exists($className) && method_exists($className, $methodName)) {
                    $this->request->merge($args);
                    $controller = new $className();
                    $controller->$methodName($this->request);
                    return;
                } else {
                    throw new RuntimeException("Controller or method not found: $className@$methodName");
                }
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    /**
     * Converts a route path with parameters into a regular expression pattern.
     *
     * @param string $path Route path with parameters enclosed in {}.
     * 
     * @return string Regular expression pattern for route matching.
     */
    private function convertToRegex($path): string
    {
        $pattern = preg_replace('/\{([^}]+)\}/', '(?P<\1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
