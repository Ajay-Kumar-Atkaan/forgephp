<?php

namespace App\controllers;
use Core\BaseController;
use Core\Request;
use Core\Response;

class TestController extends BaseController
{
    

    public function getById(Request $request, $id)
    {

        return Response::json([
            'message' => 'Hello from TestController getById method!',
            'id' => $id,
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'request_headers' => $request->headers(),
            'request_get' => $request->get()
        ]);
    }

    public function getUserById($id)
    {
        echo "user by id : " . $id;
    }
}
