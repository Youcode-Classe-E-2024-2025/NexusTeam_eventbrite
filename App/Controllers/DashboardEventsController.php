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



    public function approveEvent(Request $request){
        $event_id = $request->get("id");
        $model = new DashboardEvent();

        if($model->approveEvent($event_id , 'approved')){
            Session::set('message', 'Approved successfully');

        }else{
            Session::set('message', 'failed' );
        }

        Views::redirect('/event/admin');

    }



    public function suspendEvent(Request $request){
        $event_id = $request->get("id");
        $model = new DashboardEvent();

        if($model->suspendEvent($event_id , 'pending')){
            Session::set('message', 'suspended successfully');
        }else{
            Session::set('message', 'failed' );
        }

        Views::redirect('/event/admin');

    }
    
}