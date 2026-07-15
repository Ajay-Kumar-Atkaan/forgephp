<?php

declare(strict_types=1);

namespace Core;

use \Exception;
use Core\Request;

class Route
{
    protected Request $request;

    private array $routes = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(string $path, callable|array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable|array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolveRoute(string $method, string $path, array $segments)
    {

        $callback = $this->routes[$method][$path] ?? null;
        $parameters = [];

        if ($callback === null) {
            [$callback, $parameters] = $this->handleDynamicRoute($method, $path, $segments);
        }

        if (is_callable($callback)) {
            $resolvedParameters = $this->resolveMethodParameters(null, $callback, $parameters);
            return $callback(...array_values($resolvedParameters ?? []));
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

        $resolvedParameters = $this->resolveMethodParameters($controller, $action, $parameters);


        return $controller->{$action}(...array_values($resolvedParameters ?? []));
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
                    break;
                } else if ($routeSegment !== $segments[$index]) {
                    $match = false;
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

    private function resolveMethodParameters(?object $controller, string|callable $action,?array $parameters = null): array
    {
        $functionParameters = [];

        if($controller){
            $reflection = new \ReflectionMethod($controller, $action);
        } else {
            $reflection = new \ReflectionFunction($action);
        }
    
        
        foreach ($reflection->getParameters() as $param) {
            $paramName = $param->getName();
            $paramType = $param->getType();
            if($paramType?->getName() == 'Core\Request') {
                $functionParameters[] = $this->request;
            } elseif (!$paramType?->isBuiltin() ) {
                $instance = new \ReflectionClass($paramType?->getName());
                $functionParameters[] = $instance->newInstance();
            } else {
                $functionParameters[] = $this->handleParameterType($paramType,$paramName,$parameters);
            }
        }
        return $functionParameters;
    }

    private function handleParameterType(\ReflectionNamedType $type, string $name,array $parameters)
    {
        if($type?->getName() === 'int' && array_key_exists($name,$parameters))
            return (int) $parameters[$name];

        else if($type?->getName() === 'float' && array_key_exists($name,$parameters))
            return (float) $parameters[$name];

        return $parameters[$name] ?? null;
    }
}
