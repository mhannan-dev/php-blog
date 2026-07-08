<?php

namespace App\Core;

use Psr\Container\ContainerInterface;
use RuntimeException;

class Container
{
    private array $bindings = [];
    private array $instances = [];
    private array $aliases = [];

    public function set(string $abstract, callable|string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function get(string $id): mixed
    {
        $id = $this->aliases[$id] ?? $id;

        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->bindings[$id])) {
            return $this->resolve($id);
        }

        $concrete = $this->bindings[$id];

        if (is_callable($concrete)) {
            $instance = $concrete($this);
        } else {
            $instance = $this->resolve($concrete);
        }

        $this->instances[$id] = $instance;
        return $instance;
    }

    public function has(string $id): bool
    {
        $id = $this->aliases[$id] ?? $id;
        return isset($this->bindings[$id]) || isset($this->instances[$id]) || class_exists($id);
    }

    public function singleton(string $abstract, callable|string $concrete = null): void
    {
        $this->bindings[$abstract] = $concrete ?? $abstract;
    }

    public function alias(string $alias, string $abstract): void
    {
        $this->aliases[$alias] = $abstract;
    }

    private function resolve(string $class): mixed
    {
        if (!class_exists($class)) {
            throw new RuntimeException("Unable to resolve: $class");
        }

        $reflection = new \ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException("Class $class is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            if ($type === null || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new RuntimeException("Cannot resolve parameter: {$parameter->getName()} for $class");
                }
            } else {
                $dependencies[] = $this->get((string) $type);
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
