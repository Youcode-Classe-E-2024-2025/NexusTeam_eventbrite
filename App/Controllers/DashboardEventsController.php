<?php
namespace App\Controllers;

use App\Models\DashboardEvent;
use App\Core\Views;
use App\Core\Request;
use App\Core\Session;

class DashboardEventsController{

    public function index(){
        $model = new DashboardEvent();
        $data = $model->DisplayEvent() ;
        Views::render("Admin/eventsIndex", ['events' => $data]);     
    }
}