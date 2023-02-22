<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('RESOURCES', dirname(__DIR__) . '/resource');


// Создаем экземпляр приложения
$app = App\Core\App::getInstance();

// Запускаем приложение
$app->run();



















