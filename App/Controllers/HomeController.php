<?php

namespace App\Controllers;

use App\Core\Views;

class HomeController {
    public function index(){
        Views::render('home');
    }
}