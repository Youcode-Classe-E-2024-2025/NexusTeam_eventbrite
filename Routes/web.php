<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "UserController@index");
$router->add("GET", "/sign-up", "SignUpController@signUp");
$router->add("POST", "/sign-up", "SignUpController@register");
$router->add("GET", "/login", "LoginController@login");
$router->add("POST", "/login", "LoginController@authenticate");
$router->add("GET", "/google-signin", "GoogleSignInController@handlegoogle");
// $router->add("GET", "/facebook-signin", "FacebookSignInController@handle");

$router->dispatch();
