<?php


namespace App\Core\Validator;

use App\Core\Contracts\IValidator;
use App\Helpers\AndataExeption;


class Validator
{

    private array|string $params;

    private array $errors = [];
    private array $rules;
    private array $messages;


    public function __construct(array|string $params, array $rules, array $messages)
    {
        $this->params = $params;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->setValidators();
    }


    public function setValidators(): void
    {
        if (!empty($this->rules)) {
            foreach ($this->rules as $key => $rule) {
                $rule_methods = preg_split("/[|]+/", $rule);
                $value = $this->params[$key] ?? null;
                $this->ValidatorsInitialization($rule_methods, $value);
            }
        }
    }


    public function ValidatorsInitialization(array $rule_methods, ?string $value): void
    {
        foreach ($rule_methods as $validate) {
            try {
                $validator = $this->getValidate($validate);
                $validator->validate($value, $validate, $this->params, $this->messages);
            } catch (AndataExeption $e) {
                $key = array_search($value, $this->params);
                $this->errors[$key] = $e->getMessage();
            }
        }

    }


    public function getValidate(string $rule): IValidator
    {
        if (count($exp = explode(':', $rule)) > 1) {
            return new(__NAMESPACE__ . '\\' . ucfirst($exp[0]) . 'Validator');
        } else {
            return new(__NAMESPACE__ . '\\' . ucfirst($rule) . 'Validator');
        }
    }


    /**
     * @throws AndataExeption
     */
    public function validated(): array
    {

        if (!$this->fail()) {
            return array_intersect_key($this->params, $this->rules);
        }

        http_response_code(417);
        throw new AndataExeption(current($this->errors), 417, $this->errors);

    }


    public function fail(): bool
    {
        return !empty($this->errors);
    }


}