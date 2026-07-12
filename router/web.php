<?php

use App\controllers\TestController;

// $route = new Route();


$route->get('test/{slug}/post', function (Core\Request $request,$slug) {
    echo 'Hello from anonymous function! Slug: ' . $slug;
    echo '<br>' . print_r($request->all(), true);
});

$route->get('test2', [TestController::class, 'index']);
$route->get('test/{id}', [TestController::class, 'getById']);
$route->get('user/{id}', [TestController::class, 'getUserById']);
