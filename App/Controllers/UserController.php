<?php
namespace App\Controllers;

class UserController{

    public function index(){
        require_once __DIR__ . "/../Views/home.twig";
    }


}