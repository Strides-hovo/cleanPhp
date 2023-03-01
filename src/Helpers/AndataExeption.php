<?php

namespace App\Helpers;


use JetBrains\PhpStorm\Pure;


class AndataExeption extends \Exception
{

    private array $_params = [];

    #[Pure]
    public function __construct(string $message = "", int $code = 400, array $params = [], ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->_params = $params;

    }

    public function getParams(): array
    {
        return $this->_params;
    }


}