<?php

namespace App\Model;

error_reporting(E_ALL);


use App\Core\Contracts\IModel;
use App\Helpers\AndataExeption;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;

class Model implements IModel
{

    private const METHODS = [
        'all' => 'getAll',
        'set' => 'store',
        'findOne' => 'find',
    ];

    private string $table;
    private static ?Model $instance = null;
    private \RedBeanPHP\OODBBean $model;
    public \RedBeanPHP\ToolBox $db;


    public static function getInstance(): ?Model
    {

        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    private function __construct()
    {
        $this->db = app()->getDb();
            $this
            ->setTable()
            ->setModel($this->table);
        R::freeze(true);
    }


    /**
     * @throws \ErrorException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (in_array($name, array_keys(self::METHODS))) {
            $model = new static();
            $method = self::METHODS[$name];
            return $model->$method(...$arguments);
        }

        throw new \ErrorException('Method noting');
    }


    public function find(int $id): array
    {
        return R::load($this->table, $id)->export();
    }


    public function getAll(): array|R
    {
        return R::getAll("SELECT * FROM $this->table");
    }


    /**
     * @throws AndataExeption
     */
    public function store($data)
    {
        $this->model->import($data);
        try {
            return R::store($this->model);
        } catch (SQL $e) {
            throw new AndataExeption($e->getMessage());
        }
    }


    public function setTable(): self
    {
        $class = explode('\\', get_called_class());
        $this->table = strtolower(end($class)) . 's';
        return $this;
    }


    public function setModel(string $model): self
    {
        $this->model = R::dispense($model);
        return $this;
    }

}