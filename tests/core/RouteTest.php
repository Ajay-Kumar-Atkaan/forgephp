<?php
declare(strict_type=1);
use PHPUnit\Framework\TestCase;
use Core\Route;
use Core\Request;

final class RouteTest extends TestCase
{
    private Request $request;
    private Route $route;

    public function __construct()
    {
        $this->request = new Request();
        $this->route = new Route($this->request);
    }

    public function getTest()
    {
        
        // $this->route->get('user',function(){
        //     echo 'test';
        // });

        // $this->assertArrayHasKey('user',$this->route->routes);
    }
}