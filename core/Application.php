<?php
declare(strict_types=1);
namespace Core;
use Core\Route;
use Dotenv\Dotenv;
use Core\Request;

class Application
{
    public function __construct(private string $basePath)
    {

    }
    public function run()
    {
        $this->loadEnv();
        $this->setBasePathInEnv();
        $route = $this->bootRoutes();
        $this->handleRequestCycle($route);        
    }

    private function loadEnv()
    {
        $dotenv = Dotenv::createImmutable($this->basePath);
        $dotenv->load();
    }

    private function bootRoutes(): Route
    {
        $request = new Request();
        $route = new Route($request);
        require_once  $this->basePath . '/router/web.php';
        return $route;
    }

    private function handleRequestCycle(Route $route): void
    {
        $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $path);
        $route->resolveRoute($_SERVER['REQUEST_METHOD'], $path, $segments,);
    }

    private function setBasePathInEnv()
    {
        $_ENV['BASE_PATH'] = $this->basePath;
    }
}

