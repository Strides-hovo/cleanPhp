<?php

namespace App\Helpers;


use JetBrains\PhpStorm\NoReturn;


class AndataExeption extends \Exception
{

    private array $_params = [];

    #[NoReturn] public function __construct(string $message = "", int $code = 0, array $params = [], ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        http_response_code($this->getCode());
        $this->_params = $params;

    }

    public function getParams(): array
    {
        return $this->_params;
    }


}