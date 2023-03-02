<?php 

namespace App\Core\Contracts;

interface IRouter {

    public static function get(string $uri, string $handler): void;
    public static function post(string $uri, string $handler): void;
    public static function dispatch(string $routesPath): void;
    
}