<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "HomeController@index");
$router->add("GET", "/organizer", "OrganizerController@index");
$router->add("GET", "/organizer/sales", "OrganizerController@sales");
$router->add("GET", "/organizer/events", "OrganizerController@events");
$router->add("GET", "/home", "HomeController@index");

$router->add("GET", "/sign-up", "SignUpController@signUp");
$router->add("POST", "/sign-up", "SignUpController@register");
$router->add("GET", "/login", "LoginController@login");
$router->add("POST", "/login", "LoginController@authenticate");

$router->add("GET", "/event", "EventController@index");
$router->add("GET", '/event/{id}', 'EventController@show');
$router->add("POST", '/event', 'EventController@store');
$router->add("DELETE", '/event/{id}', 'EventController@destroy');
$router->add("POST", '/event/{id}', 'EventController@edit');

$router->add("GET", '/category', 'CategoryController@index');

$router->dispatch();
