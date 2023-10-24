<?php

declare(strict_types = 1);

namespace App\Model\Routing;

use App\Model\Exception\HTTPException;

class Router
{
    private array $routes = [];
    private string $path;
    private string $method;

    public function __construct()
    {
        $this->path = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function addRoute(string $path, string $method, array $callable): self
    {
        $this->routes[$method][] = new Route($path, $method, $callable);

        return $this;
    }

    public function run(): void
    {
        if (!isset($this->routes[$this->method])) {
            throw new HTTPException(405);
            return;
        }

        foreach ($this->routes[$this->method] as $route) {
            if ($route->match($this->path)) {
                if ($route->getMethod() === $this->method) {
                    $route->call();
                    return;
                } else {
                    throw new HTTPException(405);
                    return;
                }
            }
        }

        throw new HTTPException(404);
    }
}
