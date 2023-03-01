<?php

namespace App\Core\Validator;

use App\Core\Contracts\IValidator;
use App\Helpers\AndataExeption;
use RedBeanPHP\R;

class UniqueValidator implements IValidator
{

    public function validate(string $value, string $rule, array $params, array $messages): void
    {
        if (count($rules = explode(':', $rule)) > 1){
            $dbName = $rules[1];
            $email = R::findOne($dbName,'WHERE email = ?', [$value]);
            if ($email){
                $key = array_search($value, $params);
                $name = "$key.$rules[0]";
                $error = sprintf($messages[$name] ?? '', $key);
                throw new AndataExeption($error,417 );
            }
        }
    }
}