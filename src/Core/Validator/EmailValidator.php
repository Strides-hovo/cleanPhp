<?php

namespace App\Core\Validator;

use App\Core\Contracts\IValidator;
use App\Helpers\AndataExeption;

class EmailValidator implements IValidator
{

    public function validate(string $value, string $rule, array $params, array $messages): void
    {

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false){
            $key = array_search($value, $params);
            $name = "$key.$rule";
            $error = sprintf($messages[$name] ?? '', $key);
            throw new AndataExeption($error,417 );
        }

    }
}