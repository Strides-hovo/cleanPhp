<?php

namespace App\Core;


use App\Core\Contracts\IRequest;
use App\Helpers\AndataExeption;
use ReflectionException;


class Router
{

    private static array $routes = [];
    private static string $controllerName;
    private static string $actionName;


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
     * @throws ReflectionException
     */
    public static function dispatch(string $routesPath): void
    {
        require_once $routesPath;
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $handler = null;
        $params = [];

        self::setParams($method, $handler, $params, $uri, $matches);

        $validateMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];
        if (in_array($_SERVER['REQUEST_METHOD'], $validateMethods)) {
            validateCsrf($params['_token'] ?? null);
        }

        if ($handler === null) {
            self::handleNotFound();
        }
        self::setControllerAction($handler);
        $controller = new self::$controllerName();
        $actionName = self::$actionName;

        $deps = self::setDependencies($controller, $actionName, $params);
        //$params = self::setDependence($controller, $params);

        $controller->$actionName(...$deps);
        exit;
    }










    private static function setParams(mixed $method, mixed &$handler, mixed &$params, int|string|null $uri, &$matches): void
    {
        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (isset($route['params']) && !empty($route['params'])) {
                $handler = $route['handler'];
                $params = validateXss($route['params']);
                break;
            }

            $routePattern = str_replace('/', '\/', $route['uri']);
            $routePattern = '/^' . $routePattern . '$/';

            if (preg_match($routePattern, $uri, $matches)) {
                $handler = $route['handler'];
                array_shift($matches);
                $params = validateXss($matches);
                break;
            }
        }
    }







    private static function setControllerAction(string $handler): void
    {
        $handlerSegments = explode('@', $handler);
        self::$controllerName = 'App\\Controller\\' . $handlerSegments[0];
        self::$actionName = $handlerSegments[1];

    }






    /**
     * @throws ReflectionException
     * @return IRequest[]
     */
    private static function setDependencies(BaseController $controller, string $actionName, array $params = []): array
    {
        $ref = new \ReflectionClass($controller);
        $deps_params = $ref->getMethod($actionName)->getParameters();

        $deps = [];
        if ($deps_params) {
            foreach ($deps_params as $param) {
                $name = $param->getType()->getName();
                //dump( $name, $params );
                $deps[] = self::getExtends($name, $params);
            }
        }
        return $deps;

    }

    public static function resolveClass( string $className )
    {
        $ref = new \ReflectionClass($className);
        $constructor = $ref->getConstructor();
        $params = $constructor->getParameters();
        $deps = [];
        if ($params) {
            foreach ($params as $param) {
                $name = $param->getType()->getName();
                $deps[] = self::resolveClass($name);
            }
        }

        return $deps;

    }




    private static function getExtends(string $className, array $params = []): mixed
    {

        return new $className($params);
    }





    /**
     * @throws AndataExeption
     */
    private static function handleNotFound(): void
    {
        throw new AndataExeption('Page not found', 404);
    }





}


