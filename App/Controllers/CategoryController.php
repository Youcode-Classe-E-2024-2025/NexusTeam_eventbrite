<?php
namespace App\Controllers;

use App\Core\Views;


class CategoryController{

    public function index(){
        echo 'hey its working';
        Views::render('categories');
    }   
}