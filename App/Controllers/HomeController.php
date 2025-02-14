<?php

namespace App\Controllers;

use App\Core\Views;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventTag;

class HomeController {
    public function index(){

        $event = (new Event())->getAll(4);

        $categories = (new Category())->getAll();


        Views::render('home', ['events' => $event,'categories' => $categories]);
    }
}