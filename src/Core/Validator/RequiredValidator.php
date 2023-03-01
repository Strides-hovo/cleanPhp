<?php

namespace App\Core\Validator;

use App\Core\Contracts\IValidator;
use App\Helpers\AndataExeption;

class RequiredValidator implements IValidator
{

    public function validate(string $value, string $rule, array $params, array $messages ): void
    {
        $key = array_search($value, $params);
        if (!$key || empty($params[trim($key)])){
            $name = "$key.$rule";
            $error = sprintf($messages[$name] ?? '', $key);
            throw new AndataExeption($error,417 );
        }
    }
}