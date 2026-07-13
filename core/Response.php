<?php
declare(strict_types=1);
namespace Core;

class Response
{

    public static function json(array $data, $statusCode = 200, array $headers = [])
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        self::setHeaders($headers);
        echo json_encode($data);
    }

    public static function redirect(string $url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header('location: ' . $url);
        return;
    }

    public static function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            header($key . ':' . $value);
        }
    }
}