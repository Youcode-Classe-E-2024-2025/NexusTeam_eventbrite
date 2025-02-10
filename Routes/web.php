<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "UserController@index");
$router->add("GET", "/sign-up", "signUpController@signUp");
$router->add("GET", "/login", "LoginController@login");
$router->dispatch();