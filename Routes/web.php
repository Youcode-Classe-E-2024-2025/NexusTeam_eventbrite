<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "EventController@index");

$router->dispatch();