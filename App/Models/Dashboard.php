<?php

namespace App\Models;

use App\Core\Model;

class Dashboard extends Model{
     
    public function Display(): array{
       
        $this->primaryKey = "id";
        $this->table = "users";
        return $this->findAll();
    
    }


    public function deleteUser($userId){
        $this->primaryKey ="id";
        $this->table = 'users';
        $user =$this->find(['id' => $userId]);

        if(!empty($user)){
            return $this->delete($userId);
        }else{
            return print_r("failed"); 
        }
    }
    

    // public function BanUser($id){
    //       $this->
    // }

}