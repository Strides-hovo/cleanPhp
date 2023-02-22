<?php

namespace App\View;

class View
{
    public static string $LAYOUT = '';

    public function view(string $partialName, array $params = [])
    {
        if (!self::$LAYOUT) {
            self::$LAYOUT = app()->config('layout');
        }
        $layout = RESOURCES . '/layouts/' . self::$LAYOUT . '.php';
        $partialName = RESOURCES . '/views/' . $partialName . '.php';
        extract($params);

        ob_start();
        require_once $partialName;

        $content = ob_get_clean();
        return require_once $layout;

    }

}