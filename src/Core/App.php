<?php

namespace App\Core;

use App\Core\Traits\BaseInstance;
use App\Helpers\AndataExeption;
use Exception;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;
use RedBeanPHP\ToolBox;

class App
{
    use BaseInstance;

    public ToolBox $model;
    private array $config;

    private ToolBox $db;

    /**
     * @throws AndataExeption
     */
    private function __construct()
    {
        $configFile = __DIR__ . '/../../config.php';
        if (file_exists($configFile)) {
            $this->config = require_once $configFile;
        }
        $this->setDb();
    }


    public function run(): void
    {
        try {
            Router::dispatch(__DIR__ . '/../../routes/web.php');
        } catch (AndataExeption $e) {
            if ($e->getParams()) {
                echo json_encode($e->getParams(), JSON_UNESCAPED_UNICODE);
            } else {
                echo $e->getMessage();
            }
        } catch (\ReflectionException $e) {
            echo $e->getMessage();
        } catch (SQL $e) {
            echo($e->getCode());
        }
    }


    public function config(string $key)
    {
        return $this->config[$key] ?? null;
    }


    /**
     * @throws AndataExeption
     */
    public function setDb(): void
    {

        try {
            $db = $this->config('db');
            $this->db = R::setup("mysql:host={$db['host']};dbname={$db['dbname']}", $db['username'], $db['password']);
        } catch (Exception $e) {
            throw new AndataExeption("Error Connection database", 500);
        }
    }


    public function getDb(): ToolBox
    {
        return $this->db;
    }

}


