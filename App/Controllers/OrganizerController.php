<?php
namespace App\Controllers;
use App\core\views;

class OrganizerController{

    public function index(){
        views::render('organizerdash');
    }
    public function sales(){
        views::render('home');
    }
    public function events($participant="",$promo=""){
        views::render('home');
    }
    public function announcements(){
        views::render('home');
    }
    public function stats(){
        views::render('home');
    }
}