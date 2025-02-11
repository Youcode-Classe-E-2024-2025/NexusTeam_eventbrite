<?php
use App\Core\Router;

$router = new Router();

$router->add("GET", "/", "UserController@index");

$router->dispatch();
<?php

$router->get('/', 'TicketController@index');
$router->get('/ticket/{id}', 'TicketController@show');
$router->post('/ticket/buy', 'TicketController@buy');
$router->post('/payment/checkout', 'PaymentController@checkout');
$router->get('/payment/success', 'PaymentController@success');
$router->get('/payment/cancel', 'PaymentController@cancel');
$router->get('/ticket/download/{id}', 'TicketController@downloadPDF');