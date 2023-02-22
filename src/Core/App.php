<?php

namespace App\Core;

use App\Core\Contracts\IModel;
use App\Helpers\AndataExeption;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class App
{

    private static ?App $instance = null;

    public \RedBeanPHP\ToolBox $model;
    private array $config;

    private \RedBeanPHP\ToolBox $db;

    private function __construct()
    {
//        $this->model = $model->db;
        $configFile = __DIR__ . '/../../config.php';
        if (file_exists($configFile)){
            $this->config = require_once $configFile;
        }
        $this->setDb();
    }


    public static function getInstance(): ?App
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function run(): void
    {
        try {
            Router::dispatch(__DIR__ . '/../../routes/web.php');
        } catch (AndataExeption $e) {
            if ($e->getParams()) {
                echo json_encode($e->getParams(), JSON_UNESCAPED_UNICODE );
            } else {
                echo $e->getMessage();
            }
        }
        catch (\ReflectionException $e) {
            echo $e->getMessage();
        }
        catch (SQL $e){
            echo($e->getCode());
        }
    }


    public function config(string $key)
    {
        return $this->config[$key] ?? null;
    }


    public function setDb()
    {
        $db = $this->config('db');
        if ($db) {
            $this->db = R::setup("mysql:host={$db['host']};dbname={$db['dbname']}", $db['username'], $db['password']);
        }
    }

    /**
     * @return \RedBeanPHP\ToolBox
     */
    public function getDb(): \RedBeanPHP\ToolBox
    {
        return $this->db;
    }

}


