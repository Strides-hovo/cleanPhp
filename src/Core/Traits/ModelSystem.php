<?php

namespace App\Core\Traits;

use App\Helpers\AndataExeption;
use RedBeanPHP\R;

trait ModelSystem
{

    private static string $fields = '';


    private function setTable(): self
    {
        $class = explode('\\', get_called_class());
        $this->table = strtolower(end($class)) . 's';
        return $this;
    }


    private function setModelByTable(): self
    {
        $this->model = R::dispense($this->table);
        return $this;
    }


    /**
     * @throws AndataExeption
     */
    public function getFieldsToSql(): string
    {
        $path = MIGRATIONS . '/' . $this->table . '.php';
        $sql = '';
        if (!file_exists($path)) {
            throw new AndataExeption("File migration $this->table dont exists", 500);
        }
        if (!self::$fields) {
            $fields = require_once $path;
            foreach ($fields as $key => $field) {
                $sql .= $key . ' ' . $field . ',';
            }
            return self::$fields = rtrim($sql, ',');
        } else {
            return self::$fields;
        }
    }


    /**
     * @throws AndataExeption
     */
    private function createTable(): self
    {
        $fields = $this->getFieldsToSql();
        $sql = "CREATE TABLE IF NOT EXISTS $this->table ( $fields )";
        try {
            R::freeze(false);
            // выполняем операции с библиотекой RedBeanPHP
            R::exec($sql);
            R::freeze(true);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $this;
    }


}