<?php
namespace App\Controllers;

use App\Core\Views;


class UserController {

    public function index(){
        Views::render('users_management');
    }   
}