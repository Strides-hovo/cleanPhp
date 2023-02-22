<?php

namespace App\Core;

use App\Core\Contracts\IRequest;
use RedBeanPHP\R;

class Request implements IRequest
{

    private array|string $params;
    private array $errors = [];

    public function __construct($params)
    {
        $this->params = $params;
    }


    public function input($key)
    {
        return $this->params[$key] ?? null;
    }


    public function all(): array
    {
        return $this->params;
    }


    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }


    public function validateRules(): array
    {
        $rules = $this->rules() ?: [];

        foreach ($rules as $key => $rule) {
            $rule_methods = preg_split("/[|]+/", $rule);
            $value = $this->getParam($key);

            foreach ($rule_methods as $method) {

                // if validate maximum
                if (count($max = explode(':', $method)) > 1) {
                    if (!$this->max($value, $max[1])) {
                        $this->setErrorMessageFromArray($key, $max);
                    }
                } elseif (method_exists($this, $method)) {
                    if (!$this->$method($value)) {
                        $this->setErrorMessage($key, $method);
                    }
                }
            }
        }
        return $this->errors;
    }


    public function setErrorMessageFromArray(string $key, array $rule)
    {
        $messages = $this->messages() ?: [];
        $name = "$key.$rule[0]";
        $error = sprintf($messages[$name] ?? '', $key, $rule[1]);

        $this->setErrors([$key => $error]);
    }


    public function setErrorMessage(string $key, string $rule)
    {
        $messages = $this->messages() ?: [];
        $name = "$key.$rule";
        $error = sprintf($messages[$name] ?? '', $key);
        $this->setErrors([$key => $error]);

    }


    public function getParam(string $key)
    {
        return $this->params[$key] ?? false;
    }


    public function required(string|bool $value): bool
    {
        $key = array_search($value, $this->params);
        return $key && !empty($this->params[trim($key)]);
    }


    public function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }


    public function unique(string $value): bool
    {
        return is_null(R::findOne('comments', 'WHERE email = ?', [$value]));
    }


    public function string(string $value): bool
    {
        return true;
    }


    public function max(string $str, int $max): bool
    {
        return mb_strlen($str) <= $max;
    }


    public function __toString(): string
    {
        return $this->params[0] ?? '';
    }

    /**
     * @param array $error
     */
    public function setErrors(array $error): void
    {
        $this->errors = array_replace($this->errors, $error);
    }
}