<?php
namespace App\Controllers;
use App\core\views;
use App\Models\Organizer;

class OrganizerController
{
    public function index()
    {
        $model = new Organizer();
        $data = $model->stat();
        // print_r($data);die;
        views::render('organizerdash');
    }
    public function sales()
    {
        views::render('home');
    }
    public function events()
    {
        views::render('home');
    }
    public function participant()
    {
        
    }
    public function promo()
    {
        
    }
    public function announcements()
    {
        views::render('home');
    }
    public function stats()
    {
        views::render('home');
    }
}