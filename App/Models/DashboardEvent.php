<?php

namespace App\Models;

use App\Core\Model;

class DashboardEvent extends Model {
    
    public function __construct(){
        parent:: __construct();
        $this->table = 'events';
    }


    public function DisplayEvent(){
        $this->primaryKey ='id';
        $this->table = 'events';
        return $this->findAll();
    }



    public function approveEvent($event_id , $status){
        $event = $this->find(['id'=> $event_id]);

        if(!empty($event)){
            return $this->update($event_id , ['state'=> $status]);
        }else {
            return false ;
        }
    }
}
