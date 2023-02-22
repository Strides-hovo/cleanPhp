<?php

namespace App\Core\Contracts;

interface IRequest
{
    public function rules(): array;
    public function validateRules(): array;
    public function messages(): array;
    public function required(string|bool $value): bool;
    public function email(string $value): bool;
    public function unique(string $value): bool;
    public function string(string $value): bool;
    public function max(string $str, int $max ): bool;

}