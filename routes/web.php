<?php

use App\Core\Router;

Router::get('/', 'HomeController@index');
Router::post('/', 'CommentController@store');

