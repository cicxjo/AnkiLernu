<?php

declare(strict_types = 1);

namespace App\Model\Routing;

use App\Model\Exception\HTTPException;

class Router
{
    private string $resource;
    private string $method;
    private array $routes = [];

    public function __construct()
    {
        $this->resource = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function addRoute(string $resource, array $methods, array $callable): self
    {
        $this->routes[$resource][] = new Route($resource, $methods, $callable);

        return $this;
    }

    public function run(): void
    {
        if (array_key_exists($this->resource, $this->routes)) {
            foreach ($this->routes[$this->resource] as $route) {
                if (in_array($this->method, $route->getMethods())) {
                    $callable = $route->getCallable();
                    call_user_func([new $callable[0], $callable[1]]);
                    return;
                }

                throw new HTTPException(405);
                return;
            }
        }

        throw new HTTPException(404);
    }
}
