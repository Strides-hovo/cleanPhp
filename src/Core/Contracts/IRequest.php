<?php

namespace App\Core\Contracts;

interface IRequest
{
    public function rules(): array;

    public function messages(): array;

}