<?php

namespace App\Core;

use App\Core\Contracts\IRequest;
use App\Core\Validator\Validator;
use App\Helpers\AndataExeption;

class Request implements IRequest
{

    private array|string $params;
    private Validator $validator;

    public function __construct(array|string $params)
    {
        $this->params = $params;
        $rules = $this->rules();
        $messages = $this->messages();
        $this->validator = new Validator($params, $rules, $messages);
    }


    public function input($key): mixed
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


    /**
     * @throws AndataExeption
     */
    public function validated(): array
    {
        return $this->validator->validated();
    }


}
