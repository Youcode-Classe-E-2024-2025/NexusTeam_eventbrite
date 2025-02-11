<?php
namespace App\Controllers;
use App\core\views;

class HomeController{

    public function index(){
        views::render('home');
    }
}