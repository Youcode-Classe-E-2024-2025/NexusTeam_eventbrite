<?php
namespace App\Controllers;
use App\core\views;
use App\Models\Organizer;
use App\Core\Request;


class OrganizerController
{
    public function index()
    {
        $model = new Organizer();
        $data = 3;
        views::render('organizer/organizerdash', ["event_stats" => $data]);
    }
    public function eventstats(Request $request)
    {
        $model = new Organizer();
        $data = $model->eventStats($request->get('id'));
        views::render('organizer/organizerdash', ["eventstats" => $data]);
    }

    public function sales()
    {
        views::render('home');
    }
    public function MyEvents()
    {
        $model = new Organizer();
        $data = $model->eventList();
        views::render('organizer/organizerdash', ["events" => $data]);
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