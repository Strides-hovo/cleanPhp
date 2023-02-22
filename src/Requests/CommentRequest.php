<?php

namespace App\Requests;

use App\Core\Contracts\IRequest;
use App\Core\Request;

class CommentRequest extends Request implements IRequest
{


    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|max:100|email|unique',
            'comment' => 'required|string',
        ];


    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поля %s Обезателно',
            'name.string' => 'Поля %s должен быть строкой',
            'name.max' => 'Поля %s должен быть менше %d строкой.',
            'email.required' => 'Поля  %s Обезателно',
            'email.string' => 'Поля  %s должен быть строкой',
            'email.max' => 'Поля %s должен быть менше строкой.',
            'email.email' => 'Поля  %s должен быть почтай.',
            'email.unique' => 'Такое %s уже есть.',
            'comment.required' => 'Поля  %s Обезателно',
            'comment.string' => 'Поля  %s должен быть строкой',
        ];
    }


}