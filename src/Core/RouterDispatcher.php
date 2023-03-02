<?php

namespace App\Core;

use App\Core\Contracts\IRequest;
use App\Helpers\AndataExeption;
use JetBrains\PhpStorm\ArrayShape;


class RouterDispatcher
{


    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }


    /**
     * @throws AndataExeption
     * @throws \ReflectionException
     */
    public function start()
    {
        ['handler' => $handler, 'params' => $params] = $this->getRequestParamsHandler();
        if ($handler === null) {
            $this->handleNotFound();
        }
        $this->validateCsrf($params);
        ['actionName' => $actionName, 'controller' => $controller] = $this->getControllerAction($handler);
        $deps = $this->setDependencies($controller, $actionName, $params);
        $controller->$actionName(...$deps);
    }


    #[ArrayShape(['handler' => "string|null", 'params' => "array"])]
    private function getRequestParamsHandler(): array
    {

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $result = [
            'handler' => null,
            'params' => []
        ];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (isset($route['params']) && !empty($route['params'])) {
                $result['handler'] = $route['handler'];
                $result['params'] = validateXss($route['params']);
                break;
            }

            $routePattern = str_replace('/', '\/', $route['uri']);
            $routePattern = '/^' . $routePattern . '$/';

            if (preg_match($routePattern, $uri, $matches)) {
                $result['handler'] = $route['handler'];
                array_shift($matches);
                $result['params'] = validateXss($matches);
                break;
            }
        }

        return $result;
    }


    /**
     * @throws AndataExeption
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        throw new AndataExeption('Page not found', 404);
    }


    /**
     * @throws AndataExeption
     */
    private function validateCsrf(array $params): void
    {
        $validateMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

        if (in_array($_SERVER['REQUEST_METHOD'], $validateMethods)) {
            validateCsrf($params['_token'] ?? null);
        }
    }


    /**
     * @throws AndataExeption
     */
    #[ArrayShape(['actionName' => "string", 'controller' => "mixed"])]
    private function getControllerAction(string $handler): array
    {
        $handlerSegments = explode('@', $handler);
        if (count($handlerSegments) === 2) {
            $controllerName = 'App\\Controller\\' . $handlerSegments[0];
            $actionName = $handlerSegments[1];
            $controller = new $controllerName();
            return ['actionName' => $actionName, 'controller' => $controller];
        }
        throw new AndataExeption('Routing not valid', 500);
    }


    /**
     * @return IRequest[]
     * @throws \ReflectionException
     */
    public function setDependencies(BaseController $controller, string $actionName, array $params = []): array
    {
        $ref = new \ReflectionClass($controller);
        $deps_params = $ref->getMethod($actionName)->getParameters();

        $deps = [];
        if ($deps_params) {
            foreach ($deps_params as $param) {
                $name = $param->getType()->getName();
                $deps[] = $this->getExtends($name, $params);
            }
        }
        return $deps;
    }


    private function getExtends(string $className, array $params = []): mixed
    {
        return new $className($params);
    }

}