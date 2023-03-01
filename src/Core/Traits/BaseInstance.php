<?php

namespace App\Core\Traits;


use App\Helpers\AndataExeption;

trait BaseInstance
{

    private static ?self $instance = null;

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Одиночки не должны быть клонируемыми.
     */
    protected function __clone()
    {
    }

    /**
     * Одиночки не должны быть восстанавливаемыми из строк.
     * @throws AndataExeption
     */
    public function __wakeup()
    {
        throw new AndataExeption("Cannot unserialize a singleton.", 500);
    }
}