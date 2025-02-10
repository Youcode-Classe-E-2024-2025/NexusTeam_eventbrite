<?php

namespace App\Core;

use RuntimeException;

class Router
{
    private array $routes = [];
    private string $namespace = 'App\Controllers\\';

    public function add($method, $path, $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {

                array_shift($matches);

                $args = array_values($matches);

                $handlerName = explode('@', $route['handler']);
                if (count($handlerName) !== 2) {
                    throw new RuntimeException("Invalid handler format. Expected 'Controller@method'.");
                }

                $className = $this->namespace . $handlerName[0];
                $methodName = $handlerName[1];

                if (class_exists($className) && method_exists($className, $methodName)) {
                    $controller = new $className();
                    $controller->$methodName(...$args);
                    return;
                } else {
                    throw new RuntimeException("Controller or method not found: $className@$methodName");
                }
            }
        }

        http_response_code(404);
        echo '404 Not found';
    }


    private function convertToRegex($path): string
    {
        $pattern = preg_replace('/\{([^}]+)\}/', '(?P<\1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}