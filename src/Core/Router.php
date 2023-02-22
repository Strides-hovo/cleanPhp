<?php

namespace App\Core;


use App\Core\Contracts\IRequest;
use App\Helpers\AndataExeption;


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
     * @throws \ReflectionException
     */
    public static function dispatch(string $routesPath): void
    {
        require_once $routesPath;
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $handler = null;
        $params = [];

        self::setParams($method, $handler, $params, $uri, $matches);

        if ($handler === null) {
            self::handleNotFound();
        }
        self::setControllerAction($handler);
        $controller = new self::$controllerName();
        $actionName = self::$actionName;
        $params = self::setDependence($controller, $params);

        $controller->$actionName($params);
        exit;
    }


    public static function setControllerAction(string $handler): void
    {
        $handlerSegments = explode('@', $handler);
        self::$controllerName = 'App\\Controller\\' . $handlerSegments[0];
        self::$actionName = $handlerSegments[1];

    }


    /**
     * @param string $className
     * @param array $params
     * @return mixed
     */
    public static function getExtends(string $className, array $params = []): mixed
    {
        return new $className($params);
    }


    /**
     * @throws \ReflectionException
     */
    public static function resolveClass(BaseController $controller, string $actionName): array
    {
        $ref = new \ReflectionClass($controller);
        $params = $ref->getMethod($actionName)->getParameters();
        $deps = [];
        if ($params) {
            foreach ($params as $param) {
                $name = $param->getType()->getName();
                $deps[] = self::getExtends($name, $params);
            }
        }
        return $deps;

    }


    /**
     * @throws AndataExeption
     */
    private static function handleNotFound(): void
    {
        throw new AndataExeption('Page not found', 404);
    }

    /**
     * @param mixed $method
     * @param mixed $handler
     * @param mixed $params
     * @param bool|array|int|string|null $uri
     * @param $matches
     * @return void
     */
    private static function setParams(mixed $method, mixed &$handler, mixed &$params, bool|array|int|string|null $uri, &$matches): void
    {
        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (isset($route['params']) && !empty($route['params'])) {
                $handler = $route['handler'];
                $params = $route['params'];
                break;
            }

            $routePattern = str_replace('/', '\/', $route['uri']);
            $routePattern = '/^' . $routePattern . '$/';

            if (preg_match($routePattern, $uri, $matches)) {
                $handler = $route['handler'];
                array_shift($matches);
                $params = $matches;
                break;
            }
        }
    }

    /**
     * @param mixed $controller
     * @param mixed $params
     * @return array|IRequest
     * @throws AndataExeption
     * @throws \ReflectionException
     */
    private static function setDependence(mixed $controller, mixed $params): IRequest|array
    {
        $deps = self::resolveClass($controller, self::$actionName);

        if (!empty($deps)) {
            foreach ($deps as $dep) {
                $params = new $dep($params);
            }
            $errors = $params->validateRules();

            if (!empty($errors)) {
                throw new AndataExeption(reset($errors), 429, $errors);
            }
        }
        return $params;
    }


}


