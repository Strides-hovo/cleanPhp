<?php


require_once __DIR__ . '/../vendor/autoload.php';

define('RESOURCES', dirname(__DIR__) . '/resource');

//dd( $_SERVER, json_decode(file_get_contents('php://input'))  );

//$model = \App\Model\Model::getInstance();
// Создаем экземпляр приложения
$app = App\Core\App::getInstance( );

// Запускаем приложение
$app->run();



















