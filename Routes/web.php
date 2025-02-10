<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "EventController@index");
$router->add("GET", "/{id}", "EventController@show");

$router->dispatch();