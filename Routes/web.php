<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "HomeController@index");

$router->dispatch();