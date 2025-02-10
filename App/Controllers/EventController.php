<?php

namespace App\Controllers;

use App\Core\Request;
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

}