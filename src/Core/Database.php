<?php

namespace App\Core;

use App\Helpers\AndataExeption;
use RedBeanPHP\R;

class Database
{

    private string|array $config;

    private mixed $db;
    /**
     * @var mixed|string
     */
    private string $driver = 'mysql';

    /**
     * @throws AndataExeption
     */
    public function __construct()
    {
        $this->config = config();
        $this->db = $this->config['db'];
        if (isset($this->config['db_driver'])) {
            $this->driver = $this->config['db_driver'];
        } else {
            throw new AndataExeption('Not Connect to database');
        }

    }


    /**
     * @throws AndataExeption
     */
    public function connect(): ?\RedBeanPHP\ToolBox
    {
        return match ($this->driver) {
            'mysql' => $this->mysql(),
            'postgresql' => $this->postgresql(),
            'sqlite' => $this->sqlite(),
            default => throw new AndataExeption('Not Connect to database'),
        };
    }

    public function mysql(): \RedBeanPHP\ToolBox
    {
        $db = $this->db;
        return R::setup("mysql:host={$db['host']};dbname={$db['dbname']}",
            $db['username'], $db['password']);
    }


    public function postgresql(): \RedBeanPHP\ToolBox
    {
        $db = $this->db;
        return R::setup("pgsql:host={$db['host']};dbname=postgres",
            'postgres', '');
    }

    public function sqlite(): \RedBeanPHP\ToolBox
    {
        return R::setup('sqlite:' . getcwd() . "/../sqlite.db");

    }


}