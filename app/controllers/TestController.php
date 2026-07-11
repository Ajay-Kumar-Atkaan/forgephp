<?php

namespace App\controllers;

class TestController
{
    public function index()
    {
        echo "Hello from TestController index method!";
    }

    public function getById($id)
    {
        echo "Hello from TestController getById method! ID: " . $id;
    }

    public function getUserById($id)
    {
        echo "user by id : " . $id;
    }
}
