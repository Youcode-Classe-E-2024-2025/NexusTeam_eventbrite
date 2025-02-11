<?php
namespace App\Controllers;

use App\Core\Views;


class TagsController{

    public function index(){
        echo 'hey its working';
        Views::render('tags_management');
    }   
}