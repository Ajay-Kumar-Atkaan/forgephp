<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

use Core\Route;
require_once __DIR__ . '/../vendor/autoload.php';


use Dotenv\Dotenv;
use Core\Request;


$request = new Request();
$route = new Route($request);
require_once __DIR__ . '/../router/web.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $path);

$route->resolveRoute($_SERVER['REQUEST_METHOD'], $path, $segments,);
