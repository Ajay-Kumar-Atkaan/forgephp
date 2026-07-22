<?php

namespace App\controllers;
use Core\BaseController;
use Core\Request;
use Core\Response;
use Core\View;


class TestController extends BaseController
{
    public function index(Request $request)
    {
        return Response::redirect('user/1');
    }

    public function getById(Request $request,int $id, \Exception $exception)
    {
        // return Response::json([
        //     'message' => 'Hello from TestController getById method!',
        //     'id' => $id,
        //     'request_method' => $request->method(),
        //     'request_url' => $request->url(),
        //     'request_headers' => $request->headers(),
        //     'request_get' => $request->get()
        // ]);
        // print_r($exception);
        return View::htmlView('test',['id' => $id,]);
    }

    public function getUserById($id)
    {
        echo "user by id : " . $id;
    }
}
