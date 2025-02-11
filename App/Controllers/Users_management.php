<?php
namespace App\Controllers;

use App\Core\Views;


class Users_management{

    public function index(){
        echo 'hey its working';
        Views::render('users_management');
    }   
}