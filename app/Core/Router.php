<?php
namespace App\Core;

/**
 * Simple front-controller router
 */
class Router
{
    private array $routes = [];
    private array $params = [];

    public function get(string $path, $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, $handler): self
    {
        // Support {param*} wildcards for nested paths
        $pattern = preg_replace('/\{([a-zA-Z_]+)\*\}/', '(?P<$1>.+)', $path);
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#u';
        $this->routes[] = compact('method', 'path', 'pattern', 'handler');
        return $this;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }
            if (!preg_match($route['pattern'], $uri, $matches)) {
                continue;
            }

            $this->params = array_filter(
                $matches,
                fn($k) => !is_int($k),
                ARRAY_FILTER_USE_KEY
            );

            $handler = $route['handler'];
            if (is_callable($handler)) {
                call_user_func_array($handler, array_values($this->params));
                return;
            }

            if (is_string($handler) && str_contains($handler, '@')) {
                [$class, $action] = explode('@', $handler, 2);
                $fqcn = str_starts_with($class, 'App\\') ? $class : 'App\\Controllers\\' . $class;
                if (!class_exists($fqcn)) {
                    throw new \RuntimeException("Controller not found: $fqcn");
                }
                $controller = new $fqcn();
                if (!method_exists($controller, $action)) {
                    throw new \RuntimeException("Action not found: $fqcn@$action");
                }
                call_user_func_array([$controller, $action], array_values($this->params));
                return;
            }
        }

        http_response_code(404);
        View::render('errors/404', ['title' => '404'], null);
    }

    public function params(): array
    {
        return $this->params;
    }
}
