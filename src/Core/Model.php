<?php

namespace App\Core;

error_reporting(E_ALL);


use App\Core\Contracts\IModel;
use App\Core\Traits\BaseInstance;
use App\Core\Traits\ModelSystem;
use App\Helpers\AndataExeption;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;
use function app;

class Model implements IModel
{
    use ModelSystem, BaseInstance;

    private const METHODS = [
        'all' => 'getAll',
        'set' => 'store',
        'findOne' => 'find',
    ];


    private string $table;
    private \RedBeanPHP\OODBBean $model;
    public \RedBeanPHP\ToolBox $db;


    /**
     * @throws AndataExeption
     */
    private function __construct()
    {

        $this->db = app()->getDb();
        $this
            ->setTable()
            ->setModelByTable()
            ->createTable();

        R::freeze(true);
    }


    /**
     * @throws AndataExeption
     */

    public static function __callStatic(string $name, array $arguments): mixed
    {
        if (in_array($name, array_keys(self::METHODS))) {
            $model = new static();
            $method = self::METHODS[$name];
            return $model->$method(...$arguments);
        }

        throw new AndataExeption('Method noting');
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
    public function store($data): int|string
    {
        $this->model->import($data);
        try {
            return R::store($this->model);
        } catch (SQL $e) {

            throw new AndataExeption($e->getMessage());
        }
    }


    private function getAllColumnNames(): array
    {
        return R::inspect($this->table);
    }


    private function dropAllTables(): void
    {
        R::nuke();
    }

}