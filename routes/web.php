<?php

use App\controllers\TestController;

// $route = new Route();


$route->get('test/{slug}/post', function (Core\Request $request,$slug) {
    echo 'Hello from anonymous function! Slug: ' . $slug;
    echo '<br>' . print_r($request->all(), true);

    $user = new \App\Models\User();

    $dataSelect = $user
        ->select('*')
        ->from()
        // ->whereIn('id',  [1,2,3,4,5,6,7])
        ->orderBy('id', 'DESC')
        // ->limit(3,1)
        ->get();

    $user->where('id', '=', 1)->whereIn('email', ['john@example.com'],'OR')->update(['first_name' => 'Jane', 'last_name' => 'Smith updated2']);

    $data = $user
        ->select('*')
        ->from()
        ->where('id', '=', '1')
        // ->limit(3,1)
        ->first();


    echo '<pre>';
    print_r($dataSelect);
    print_r($data);
});

$route->get('test2', [TestController::class, 'index']);
$route->get('test/{id}', [TestController::class, 'getById']);
$route->get('user/{id}', [TestController::class, 'getUserById']);
