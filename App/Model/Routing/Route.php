<?php

declare(strict_types = 1);

namespace App\Model\Routing;

class Route
{
    private string $resource;
    private array $methods;
    private array $callable;

    public function __construct(string $path, array $method, array $callable)
    {
        $this->resource = $path;
        $this->methods = $method;
        $this->callable = $callable;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getCallable(): array
    {
        return $this->callable;
    }
}
