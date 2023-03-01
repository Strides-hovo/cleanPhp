<?php


return [
    'db' => [
        'host' => getenv('DB_HOST') ? : 'localhost',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: 'root',
        'dbname' => getenv('DB_NAME') ?: 'doctrine',
    ],
    'layout' => 'default',
];