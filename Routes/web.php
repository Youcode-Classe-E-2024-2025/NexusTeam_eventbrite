<?php
use App\Core\Router;

$router = new Router();

//nav routes
$router->add("GET", "/", "HomeController@index");
$router->add("GET", "/home", "HomeController@index");

//auth routes
$router->add("GET", "/signup", "SignUpController@signUp");
$router->add("GET", "/login", "LoginController@login");
$router->add("GET", "/logout", "LoginController@logout");
$router->add("POST", "/signup", "SignUpController@register");
$router->add("POST", "/login", "LoginController@authenticate");

//event routes
$router->add("GET", "/event", "EventController@index");
$router->add("GET", '/event/{id}', 'EventController@show');
$router->add("POST", '/event', 'EventController@store');
$router->add("POST", '/event/delete/{id}', 'EventController@destroy');
$router->add("POST", '/event/update/{id}', 'EventController@edit');

//category routes
$router->add("GET", '/category', 'CategoryController@index');
$router->add("GET", '/category/update/{id}', 'CategoryController@show');
$router->add("POST", '/category/update/{id}', 'CategoryController@update');
$router->add("POST", '/category/delete/{id}', 'CategoryController@destroy');



//admin dashboard
$router->add("GET", '/DashboardController','DashboardController@index');
$router->add("POST" , '/deleteUser' , "DashboardController@index" ) ; 



$router->dispatch();
