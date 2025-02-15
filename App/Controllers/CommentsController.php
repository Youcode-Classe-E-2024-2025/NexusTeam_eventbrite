<?php
namespace App\Controllers;

use App\Core\Views;
use App\Core\Request;
use App\Core\Session;


class CommentsController{

    public function index(){
        
        Views::render("Admin/commentsIndex", ['message' => 'hey there its working']);     
        
    }
}