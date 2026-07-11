<?php

use Core\Route;
use App\controllers\TestController;

$route = new Route();


$route->get('test/{slug}/post', function ($slug) {
    echo 'Hello from anonymous function! Slug: ' . $slug;
});

$route->get('test2', [TestController::class, 'index']);
$route->get('test/{id}', [TestController::class, 'getById']);
$route->get('user/{id}', [TestController::class, 'getUserById']);
