<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "UserController@index");

$router->dispatch();