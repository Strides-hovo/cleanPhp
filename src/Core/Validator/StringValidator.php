<?php

namespace App\Core\Validator;

use App\Core\Contracts\IValidator;
use App\Helpers\AndataExeption;

class StringValidator implements IValidator
{

    public function validate(?string $value, string $rule, array $params, array $messages ): void
    {
        if (!is_string($value)){
            $key = array_search($value, $params);
            $name = "$key.$rule";
            $error = sprintf($messages[$name] ?? '', $key);
            throw new AndataExeption($error,417 );
        }
    }
}