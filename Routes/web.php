<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "EventController@index");
$router->add("POST", '/event', 'EventController@store');


$router->dispatch();