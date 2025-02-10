<?php
namespace App\Core;
class Router
{
    private static ?Router $instance = null;
    private array $routes = [];

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function create(): Router
    {
        return self::$instance ?? new self();
    }

    public static function get($uri, $action): void
    {
        self::create()->add('GET', $uri, $action);
    }

    public static function post($uri, $action): void
    {
        self::create()->add('POST', $uri, $action);
    }

    public function add($method, $uri, $action): void
    {
        $this->routes[strtoupper($method)][$uri] = $action;
    }

    public function dispatch($uri, $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = strtoupper($method);

        if (!isset($this->routes[$method])) {
            return $this->notFound();
        }

        foreach ($this->routes[$method] as $route => $action) {
            $routeRegex = preg_replace('/\{([a-zA-Z0-9_]+)}/', '([^/]+)', $route);
            if (preg_match('#^' . $routeRegex . '$#', $uri, $matches)) {
                array_shift($matches);

                if (is_array($action)) {
                    $controller = new $action[0];
                    $method = $action[1];

                    return call_user_func_array([$controller, $method], $matches);
                }

                return call_user_func_array($action, $matches);
            }
        }

        return $this->notFound();
    }

    private function notFound()
    {
        header("HTTP/1.0 404 Not Found");
        echo "404 - Page not found";
        exit;
    }
}

