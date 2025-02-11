<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Core\Upload;
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

    public function show(Request $request): void
    {

        $event = new Event();
        $event->setId($request->get('id'));
        $data = $event->getById();

        View::render("Events/evv", ['event' => $data]);
    }

    public function store(Request $request): void
    {
        if ($request->isPost()) {
            $event = new Event();
            $event->fill($request->all());
            $event->setStartDate($request->get('start_date'));
            $event->setEndDate($request->get('end_date'));
            $event->setMaxCapacity($request->get('max_capacity'));

            dd($request);

            $upload = new Upload($_FILES['image']);
            $upload = $upload->save();
            if ($upload) {
                $event->setPromotionalImage($upload);
            }

            if ($event->create()) {
                Session::set('message', 'Event created successfully');
            } else {
                Session::set('message', 'Event not created, try again');
            }
            View::render('Events/addEvent', ['message' => Session::get('message')]);
        }
    }

}