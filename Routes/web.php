<?php
use App\Controllers\EventController;
use App\Core\Router;

$router = new Router();

$router->add('GET', '/event/admin','DashboardEventsController@index');
$router->add('POST', "/event/approve/{id}" ,'DashboardEventsController@approveEvent');
$router->add('POST', "/event/suspend/{id}" ,'DashboardEventsController@suspendEvent');

//nav routes
$router->add("GET", "/", "HomeController@index");
$router->add("GET", "/home", "HomeController@index");

//organizer routes
$router->add("GET", "/organizer", "OrganizerController@index");
$router->add("GET", "/organizer/sales", "OrganizerController@sales");
$router->add("GET", "/organizer/MyEvents", "OrganizerController@MyEvents");
$router->add("GET", "/organizer/MyEvents/eventStats/{id}", "OrganizerController@eventstats");
$router->add("GET", "/organizer/MyEvents/eventStats/{id}/participant", "OrganizerController@Participant");
$router->add("GET", "/organizer/MyEvents/eventStats/{id}/participant/csv", "OrganizerController@csv");
$router->add("GET", "/organizer/MyEvents/eventStats/{id}/participant/excel", "OrganizerController@excel");

//participant routes
$router->add("GET", "/participant", "ParticipantController@index");

//profile routes
$router->add("GET", "/profile", "ProfileController@index");
$router->add("POST", "/avatar", "ProfileController@updateProfileImage");


//auth routes
$router->add("GET", "/signup", "SignUpController@signUp");
$router->add("GET", "/login", "LoginController@login");
$router->add("GET", "/logout", "LoginController@logout");
$router->add("POST", "/signup", "SignUpController@register");
$router->add("POST", "/login", "LoginController@authenticate");
$router->add("GET", "/googlesignin", "GoogleSignInController@handlegoogle");
$router->add("GET", "/facebooksignin", "FacebookSignInController@handle");

//event routes
$router->add("GET", "/event", "EventController@index");
$router->add("GET", '/event/add', 'EventController@showAdd');
$router->add("GET", '/event/{id}', 'EventController@show');
$router->add("GET", '/event/update/{id}', 'EventController@showEdit');
$router->add("POST", '/event', 'EventController@store');
$router->add("POST", '/event/delete/{id}', 'EventController@destroy');
$router->add("POST", '/event/update/{id}', 'EventController@edit');

//API EVENT
$router->add('POST', '/api/event/search', 'EventController@search');

//category routes
$router->add("GET", '/category', 'CategoryController@index');
$router->add("GET", '/category/update/{id}', 'CategoryController@show');
$router->add("POST", '/category/create', 'CategoryController@store');
$router->add("POST", '/category/update/{id}', 'CategoryController@update');
$router->add("POST", '/category/delete/{id}', 'CategoryController@destroy');

//PromoCode routes
$router->add("GET", '/organizer/PromoCode', 'PromoCodeController@index');
$router->add("GET", '/organizer/PromoCode/update/{id}', 'PromoCodeController@show');
$router->add("POST", '/organizer/PromoCode/create', 'PromoCodeController@store');
$router->add("POST", '/organizer/PromoCode/update/{id}', 'PromoCodeController@update');
$router->add("POST", '/organizer/PromoCode/delete/{id}', 'PromoCodeController@destroy');

//tag routes
$router->add("GET", '/tags', 'TagController@index');
$router->add("GET", '/tags/update/{id}', 'TagController@show');
$router->add("POST", '/tags/create', 'TagController@store');
$router->add("POST", '/tags/update/{id}', 'TagController@update');
$router->add("POST", '/tags/delete/{id}', 'TagController@destroy');


// Serving upload files
$router->add("GET", "/uploads/{filename}", function ($filename) {
    $filePath = __DIR__ . "/../app/uploads/" . $filename;

    if (file_exists($filePath)) {
        header("Content-Type: " . mime_content_type($filePath));
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo "Fichier introuvable : " . $filePath; // Debug
        exit;
    }
});
$router->add("GET", "/dashboard/admin", "DashboardUserController@index");
$router->add("POST", "/user/delete/{id}", "DashboardUserController@DeleteUser");
$router->add("Post","/user/ban/{id}", "DashboardUserController@BanUser");
$router->add('POST', '/user/unban/{id}','DashboardUserController@unbanUser');

$router->dispatch();
