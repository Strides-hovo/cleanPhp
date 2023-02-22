<?php

namespace App\Core\Contracts;

use RedBeanPHP\R;

interface IModel
{
    public function getAll(): array|R;
}