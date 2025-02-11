<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Session;
use App\Core\Upload;
use App\Core\Validator;
use App\Core\Views;
use App\Models\Event;

class EventController
{

    public function index(): void
    {
        $event = new Event();
        $data = $event->getAll();

        Views::render("Events/addEvent", ['events' => $data]);
    }

    public function show(Request $request): void
    {

        $event = new Event();
        $event->setId($request->get('id'));
        $data = $event->getById();

        Views::render("Events/editEvent", ['event' => $data]);
    }

    public function store(Request $request): void
    {
        if ($request->isPost()) {

            Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'max_capacity' => 'required|numeric',
                'files' => 'required',
                'price' => 'required|numeric',
                'location' => 'required|string',
            ]);

            if (!empty(Validator::errors())){
                Session::set('message', Validator::errors()[0]);
                Views::render('Events/addEvent', ['message' => Session::get('message')]);
            }


            $event = new Event();
            $event->fill($request->all());
            $event->setStartDate($request->get('start_date'));
            $event->setEndDate($request->get('end_date'));
            $event->setMaxCapacity($request->get('max_capacity'));


            $upload = new Upload($request->get('files'));
            $upload = $upload->save();
            if (!$upload) {
                Session::set('message', 'Image not uploaded');
                Views::render('Events/addEvent', ['message' => Session::get('message')]);
                return;
            }

            $event->setPromotionalImage($upload);

            if ($event->create()) {
                Session::set('message', 'Event created successfully');
            } else {
                Session::set('message', 'Event not created, try again');
            }
            Views::render('Events/addEvent', ['message' => Session::get('message')]);
        }
    }

    public function delete(Request $request): void {
        $event = new Event();
        $event->setId($request->get('id'));

        if ($event->delete()) {
            Session::set('message', 'Event deleted successfully');
        } else {
            Session::set('message', 'Event not deleted, try again');
        }
        Views::render('Events/addEvent', ['message' => Session::get('message')]);
    }

    public function edit(Request $request): void {
        $event = new Event();
        $event->setId($request->get('id'));
        $update = $event->getById(); //event to be updated
        $update->fill($request->all());
        $update->setStartDate($request->get('start_date'));
        $update->setEndDate($request->get('end_date'));
        $update->setMaxCapacity($request->get('max_capacity'));

        if (!empty($request->get('files')['tmp_name'])){
            $upload = new Upload($request->get('files'));
            $upload = $upload->save();

            if (!$upload) {
                Session::set('message', 'Image not uploaded');
                Views::render('Events/editEvent', ['message' => Session::get('message')]);
            }
            $update->setPromotionalImage($upload);
        }

        if ($update->update()) {
            Session::set('message', 'Event updated successfully');
        } else {
            Session::set('message', 'Event not updated, try again');
        }

    }

}