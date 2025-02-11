<?php
namespace App\Controllers;

use App\Core\Views;


class UserController {

    public function index(){
        echo 'hey its working';
        Views::render('users_management');
    }   
}