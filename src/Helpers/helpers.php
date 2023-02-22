<?php

use App\Helpers\AndataExeption;

function app(): \App\Core\App
{
    return App\Core\App::getInstance();
}


function csrf(): string
{
    return setSession('_token', bin2hex(openssl_random_pseudo_bytes(32)));
}


/**
 * @throws AndataExeption
 */
function validateCsrf(?string $_token): bool
{

    if (!$_token || !hash_equals($_SESSION['_token'], $_token)) {
        throw new AndataExeption("Error Processing not isset csrf token", 419);
    }

    return true;
}


function validateXss(mixed &$params): mixed
{
    if (is_array($params)) {
        foreach ($params as &$param) {
            $param = htmlspecialchars($param, ENT_QUOTES, "UTF-8");
        }
    } else {
        $params = htmlspecialchars($params, ENT_QUOTES, "UTF-8");
    }

    return $params;
}


function setSession(string $key, mixed $value): string
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION[$key] = bin2hex(openssl_random_pseudo_bytes(32));
}


