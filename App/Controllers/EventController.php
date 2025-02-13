<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Core\Upload;
use App\Core\Validator;
use App\Core\Views;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventTag;
use App\Models\Tag;

class EventController
{

    public function index(): void
    {
        $event = new Event();
        $data = $event->getAll();
        $tags = [];

        foreach ($data as $event) {
            $imagePath = $event->getPromotionalImage();
            if (!file_exists($imagePath) || empty($imagePath)) {
                $event->imageUrl = '/Assets/default_event.webp';
            } else {
                $event->imageUrl = $imagePath;
            }

            $tagEvent = (new EventTag())->setEvent($event);
            $tags[$event->getId()] = $tagEvent->getTagsByEvent();
        }

        Views::render("Events/index", ['events' => $data, 'tags' => $tags]);
    }

    public function show(Request $request): void
    {
        $event = new Event();
        $event->setId($request->get('id'));
        $data = $event->getById();

        Views::render("Events/editEvent", ['event' => $data]);
    }

    public function showAdd(): void
    {
        $categories = (new Category())->getAll();
        $tags = (new Tag())->getAll();

        Views::render('Events/add', ['categories' => $categories, 'tags' => $tags]);
    }

    public function store(Request $request): void
    {
        if ($request->isPost()) {

            $tags = $request->get('tags');

            Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'max_capacity' => 'required|numeric',
                'files' => 'required',
                'price' => 'required|numeric',
                'location' => 'required',
            ]);

            if (!empty(Validator::errors())){
                Session::set('message', Validator::errors()[0]);
                Views::redirect('/event/add');
                return;
            }

            if (count($tags) > 3) {
                Session::set('message', 'You can only select up to 3 tags');
                Views::redirect('/event/add');
                return;
            }


            $event = new Event();
            $event->fill($request->all());
            $event->setStartDate($request->get('start_date'));
            $event->setEndDate($request->get('end_date'));
            $event->setMaxCapacity($request->get('max_capacity'));
            $event->getCategory()->setId($request->get('category_id'));

            $upload = new Upload($request->get('files'));
            $upload = $upload->save();
            if (!$upload) {
                Session::set('message', 'Media not uploaded');
                Views::redirect('/event/add');
                return;
            }

            $event->setPromotionalImage($upload);

            if ($event->create()) {
                Session::set('message', 'Event created successfully');
            } else {
                Session::set('message', 'Event not created, try again');
            }

            $eventTag = new EventTag();
            $eventTag->setEvent($event);

            foreach ($tags as $tag) {
                $tag = new Tag($tag);
                $eventTag->setTag($tag);
                $eventTag->save();
            }


            Views::redirect('/event');
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
        Views::render('Events/addEvent');
    }

    public function edit(Request $request): void {
        $event = new Event();
        $event->setId($request->get('id'));

        $update = $event->getById(); //event to be updated
        $update->fill($request->all());
        $update->setStartDate($request->get('start_date'));
        $update->setEndDate($request->get('end_date'));
        $update->setMaxCapacity($request->get('max_capacity'));
        $update->getCategory()->setId($request->get('category_id'));

        if (!empty($request->get('files')['tmp_name'])){
            $upload = new Upload($request->get('files'));
            $upload = $upload->save();

            if (!$upload) {
                Session::set('message', 'Image not uploaded');
                Views::render('Events/editEvent');
            }
            $update->setPromotionalImage($upload);
        }

        if ($update->update()) {
            Session::set('message', 'Event updated successfully');
        } else {
            Session::set('message', 'Event not updated, try again');
        }

        Views::redirect('/event');
    }

    public function search(Request $request) {
        $data = Event::search($request->get('search'));

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

}