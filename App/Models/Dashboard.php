<?php

namespace App\Models;

use App\Core\Model;

class Dashboard extends Model{
     
    public function Display(): array{
       
        $this->primaryKey = "id";
        $this->table = "users";
        return $this->findAll();
    
    }


    // public function BanUser($id){
    //       $this->
    // }

}