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
$router->add("POST", '/category/create', 'CategoryController@store');
$router->add("POST", '/category/update/{id}', 'CategoryController@update');
$router->add("POST", '/category/delete/{id}', 'CategoryController@destroy');

//tag routes

$router->add("GET", '/tags', 'TagController@index');
$router->add("GET", '/tags/update/{id}', 'TagController@show');
$router->add("POST", '/tags/create', 'TagController@store');
$router->add("POST", '/tags/update/{id}', 'TagController@update');
$router->add("POST", '/tags/delete/{id}', 'TagController@destroy');


$router->add("GET", "/dashboard/admin", "DashboardController@index");
$router->add("POST", "/user/delete/{id}", "DashboardController@DeleteUser");
$router->add("Post","/user/ban/{id}", "DashboardController@BanUser");
$router->add('POST', '/user/unban/{id}','DashboardController@unbanUser');

$router->dispatch();
