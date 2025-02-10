<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Event;

class EventController
{

    public function index(): void
    {
        $event = new Event();
        $data = $event->getAll();

        View::render("Events/event", ['events' => $data]);
    }

    public function show($id): void {

        $event = new Event();
        $event->setId($id);
        $data = $event->getById();

        View::render("Events/evv", ['event' => $data]);
    }

}