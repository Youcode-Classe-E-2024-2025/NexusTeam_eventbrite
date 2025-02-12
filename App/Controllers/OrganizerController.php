<?php
namespace App\Controllers;
use App\core\views;
use App\Models\Organizer;
use App\Core\Request;


class OrganizerController
{
    private Organizer $model;

    public function __construct()
    {
        $this->model = new Organizer();
    }
    public function index()
    {
        $data = 3;
        views::render('organizer/organizerdash', ["event_stats" => $data]);
    }
    public function eventstats(Request $request)
    {
        $data = $this->model->eventStats($request->get('id'));
        views::render('organizer/organizerdash', ["eventstats" => $data]);
    }
    public function MyEvents()
    {
        $data = $this->model->eventList();
        views::render('organizer/organizerdash', ["events" => $data]);
    }
    public function participant(Request $request)
    {
        $data = $this->model->participantList($request->get('id'));
        views::render('organizer/organizerdash', ["participants" => $data]);
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