<?php 
declare(strict_types=1);
namespace Core;

class Request
{
    protected string $method;
    protected string $url;

    protected array $headers = [];

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = $_SERVER['REQUEST_URI'];
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$headerName] = $value;
            }
        }
        $this->headers = $headers;
    }

    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }

    public function get(?string $key= null): array|string|null
    {
        if ($key === null) {
            return $_GET;
        }

        return $_GET[$key] ?? null;
    }

    public function post(?string $key= null): array|string|null
    {
        if ($key === null) {
            return $_POST;
        }

        return $_POST[$key] ?? null;
    }

    public function headers($key= null): array|string|null
    {
        if ($key === null) {
            return $this->headers;
        }

        return $this->headers[$key] ?? null;
    }

    public function getSession(?string $key=null): array|string|null
    {
        
        if ($key === null) {
            return $_SESSION;
        }
        return $_SESSION[$key] ?? null;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function url(): string
    {
        return $this->url;
    }

}

