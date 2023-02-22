<?php

namespace App\Core;

use App\View\View;

abstract class BaseController
{
    public function view(string $partialName, array $params = [])
    {
        $view = new View();
        echo $view->view($partialName, $params);
    }
}