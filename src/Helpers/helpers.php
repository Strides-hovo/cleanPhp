<?php


function config(string $key)
{
    $file = __DIR__ . '/../../config.php';
    if (file_exists($file)){
        $data = require_once $file;

        if (is_array($data)){
//
            return $data[$key] ?? null;
        }
//        return $key;
    }
    else{
        return '';
    }
}


function concat(array $data, string $key): array
{
    $result = [];
    foreach ($data as $val) {
        if (is_string($val)) {
            $result[] = "{$key}.{$val}";
        }
    }
    return $result;
}


function app(): \App\Core\App
{
    //$model = \App\Model\Model::getInstance();
    return App\Core\App::getInstance();
}

