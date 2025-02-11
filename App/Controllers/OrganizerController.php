<?php
namespace App\Controllers;
use App\core\views;
use App\Models\Organizer;

class OrganizerController
{
    public function index()
    {
        $model = new Organizer();
        $data = $model->eventStat();
        views::render('organizerdash', ["event_stats" => $data]);
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