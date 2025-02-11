<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Core\View;
use App\Models\Event;

class EventController
{

    public function index(): void
    {
        $event = new Event();
        $data = $event->getAll();

        View::render("Events/addEvent", ['events' => $data]);
    }

    public function show(Request $request): void {

        $event = new Event();
        $event->setId($request->get('id'));
        $data = $event->getById();

        View::render("Events/evv", ['event' => $data]);
    }

    public function store(Request $request): void {
        if ($request->isPost()){
            $event = new Event();
            $event->fill($request->all());

            if ($event->create()){
                Session::set('message', 'Event created successfully');
            } else {
                Session::set('message', 'Event not created, try again');
            }
            View::redirect('/events');
        }
    }

}