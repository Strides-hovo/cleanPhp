<?php

namespace App\Core;


use JetBrains\PhpStorm\ArrayShape;

class JsonResource implements \JsonSerializable
{

    private array $data = [];

    public function __construct(array $data)
    {
        header('Content-Type: application/json');
        $this->data = $data;
    }


    public function arrayJsonSerialize(): string|bool
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function jsonSerialize(): string|bool
    {
        return json_encode($this->serialize($this->data), JSON_PRETTY_PRINT);
    }

    public function toArray(): array
    {
        return array_map(function ($item) {
            return $this->serialize($item);
        }, $this->data);

    }


    #[ArrayShape(['name' => "mixed", 'email' => "mixed", 'comment' => "mixed", 'created_at' => "mixed"])]
    public function serialize($data): array
    {
        return [
            'name' => $data['name'],
            'email' => $data['email'],
            'comment' => $data['comment'],
            'created_at' => $data['created_at'],
        ];
    }

}