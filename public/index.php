<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('RESOURCES', dirname(__DIR__) . '/resource');
define('MIGRATIONS', dirname(__DIR__) . '/migrations');

session_start();

// Создаем экземпляр приложения
$app = App\Core\App::getInstance();

// Запускаем приложение
$app->run();






















