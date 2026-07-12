<?php

namespace App\controllers;
use Core\BaseController;
use Core\Request;

class TestController extends BaseController
{
    public function index(Request $request)
    {
        echo "Hello from TestController index method! <br>";
        echo "Request Method: " . $request->method() . "<br>";
        echo "Request URL: " . $request->url() . "<br>";
        echo "Request Headers: <pre>" . print_r($request->headers(), true) . "</pre><br>";
        echo "Request GET: <pre>" . print_r($request->get(), true) . "</pre><br>";
        echo "Request POST: <pre>" . print_r($request->post(), true) . "</pre><br>";
        echo "Request Session: <pre>" . print_r($request->getSession(), true) . "</pre><br>";   
        echo "Request All: <pre>" . print_r($request->all(), true) . "</pre><br>";
    }

    public function getById(Request $request, $id)
    {
        echo "Hello from TestController getById method! ID: " . $id;
        echo "<br>Request Method: " . $request->method();
        echo "<br>Request URL: " . $request->url();
        echo "<br>Request Headers: <pre>" . print_r($request->headers(), true) . "</pre>";
        echo "<br>Request GET: <pre>" . print_r($request->get(), true) . "</pre>";
    }

    public function getUserById($id)
    {
        echo "user by id : " . $id;
    }
}
