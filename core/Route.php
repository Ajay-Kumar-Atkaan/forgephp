<?php

declare(strict_types=1);

namespace Core;

use \Exception;

class Route
{

    private array $routes = [];

    public function get(string $path, callable|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function resolveRoute(string $method, string $path, array $segments)
    {

        $callback = $this->routes[$method][$path] ?? null;
        $parameters = [];

        if ($callback === null) {
            [$callback, $parameters] = $this->handleDynamicRoute($method, $path, $segments);
        }
        // var_dump($callback,$parameters);

        if (is_callable($callback)) {
            return $callback(...array_values($parameters));
        }

        if (is_array($callback)) {
            return $this->invokeController($callback, $parameters);
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    public function getRoutesList(): array
    {
        return $this->routes;
    }

    private function invokeController(array $callback, ?array $parameters = null)
    {

        [$controllerClass, $action] = $callback;

        if (!class_exists($controllerClass)) {
            throw new Exception("Controller {$controllerClass} not found");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $action)) {
            throw new Exception("Method {$action} not found in controller {$controllerClass}");
        }

        return $controller->{$action}(...array_values($parameters ?? []));
    }

    private function handleDynamicRoute(string $method, string $path, array $segments): ?array
    {
        $parameters = [];
        $match = true;
        $callback = null;

        foreach ($this->routes[$method] as $key => $value) {

            $routeSegments = explode('/', trim($key, '/'));

            if (count($routeSegments) !== count($segments)) {
                continue;
            }

            foreach ($routeSegments as $index => $routeSegment) {
                if (str_starts_with($routeSegment, '{') && str_ends_with($routeSegment, '}')) {
                    $parameterName = trim($routeSegment, '{}');
                    $parameters[$parameterName] = $segments[$index];
                    continue;
                } else if (empty($segments[$index])) {
                    $match = false;
                    // echo "Segment mismatch: expected '$path''\n";
                    break;
                } else if ($routeSegment !== $segments[$index]) {
                    $match = false;
                    // echo "Segment mismatch: expected '$path''\n";
                    break;
                }
            }

            if ($match) {
                return [$value, $parameters];
            }
            $match = true;
            $parameters = [];
        }

        return null;
    }
}
