<?php

namespace App\Core\Validator;

use App\Core\Contracts\IValidator;
use App\Helpers\AndataExeption;

class MaxValidator implements IValidator
{


    public function validate(string $value, string $rule, array $params, array $messages ): void
    {
        if (count($rules = explode(':', $rule)) > 1){
            $max = intval($rules[1]);
            if ( mb_strlen($value) > $max ){
                $key = array_search($value, $params);
                $name = "$key.$rules[0]";
                $error = sprintf($messages[$name] ?? '', $key, $max);
                throw new AndataExeption($error,417 );
            }
        }
    }


}