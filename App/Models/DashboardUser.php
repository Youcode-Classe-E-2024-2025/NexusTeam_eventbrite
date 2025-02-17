<?php

namespace App\Models;

use App\Core\Model;

class DashboardUser extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }

    public function Display(): array
    {

        $this->primaryKey = "id";
        $this->table = "users";
        return $this->findAll();
        
    }


    public function deleteUser($userId)
    {
        $this->primaryKey = "id";
        $this->table = 'users';
        $user = $this->find(conditions: ['id' => $userId]);

        if (!empty($user)) {
            return $this->delete($userId);
        } else {
            return print_r("failed");
        }
    }


    public function BanUser($user_id, $NewStatus)
    {
        $user = $this->find(['id' => $user_id]);

        if (!empty($user)) {
            return $this->update($user_id, ['status' => $NewStatus]);
        } else {
            return false;
        }
    }


    public function unbanUser($user_id , $NewStatus){
        $user = $this->find(['id' => $user_id]);
        
        if(!empty($user)){
            return $this->update($user_id, ['status'=> $NewStatus]);
        }else{
            return false; 
        }
    
    }


}