<?php

namespace App\Core;

use App\Core\Contracts\IRouter;
use App\Helpers\AndataExeption;

class Router implements IRouter
{

    private static array $routes = [];


    public static function get(string $uri, string $handler): void
    {
        self::$routes[] = [
            'method' => 'GET',
            'uri' => $uri,
            'handler' => $handler,
        ];
    }

    public static function post(string $uri, string $handler): void
    {
        if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $post = json_decode(file_get_contents('php://input'), true);
        } else {
            $post = $_POST;
        }

        self::$routes[] = [
            'method' => 'POST',
            'uri' => $uri,
            'handler' => $handler,
            'params' => $post
        ];
    }


    /**
     * @throws AndataExeption
     * @throws \ReflectionException
     */
    public static function dispatch(string $routesPath): void
    {
        require_once $routesPath;

        $dispatch = new RouterDispatcher(self::$routes);

        $dispatch->start();

    }

}


