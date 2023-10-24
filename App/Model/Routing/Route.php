<?php

declare(strict_types = 1);

namespace App\Model\Routing;

class Route
{
    private string $path;
    private string $method;
    private array $callable;

    public function __construct(string $path, string $method, array $callable)
    {
        $this->path = $path;
        $this->method = $method;
        $this->callable = $callable;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getCallable(): array
    {
        return $this->callable;
    }

    public function call(): void
    {
        call_user_func([new $this->callable[0], $this->callable[1]]);
    }

    public function match(string $path): bool
    {
        return $path === $this->path;
    }
}
