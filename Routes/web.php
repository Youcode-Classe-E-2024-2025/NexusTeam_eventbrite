<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "HomeController@index");
$router->add("GET", "/organizer", "OrganizerController@index");
$router->add("GET", "/organizer/sales", "OrganizerController@sales");
$router->add("GET", "/organizer/events", "OrganizerController@events");

$router->dispatch();