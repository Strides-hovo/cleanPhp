<?php

namespace App\Core\Contracts;

use App\Helpers\AndataExeption;

interface IValidator
{
    /**
     * @throws AndataExeption
     */
    public function validate(string $value, string $rule, array $params, array $messages ): void;

}