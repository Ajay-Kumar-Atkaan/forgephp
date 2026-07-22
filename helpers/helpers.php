<?php
declare(strict_types=1);
use Core\Config;

function env(string $key,?string $default = null): ?string
{
    return $_ENV[$key] ?? $default;
}

function cleanInput(string $input): string
{
    return htmlspecialchars($input);
}

function basePath(): string
{
    return env('BASE_PATH') ?? null;
}

function asset(?string $path)
{
    return rtrim(env('APP_URL'),'/') .'/'. ltrim($path,'/');
}

function config(string $key)
{
    $config = Config($key);
    return $config::get($key);
}