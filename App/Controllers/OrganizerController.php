<?php
namespace App\Controllers;
use App\core\views;
use App\Models\Organizer;

class OrganizerController
{
    public function index()
    {
        views::render('organizerdash');
        $model = new Organizer();
        $data = $model->stat();
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