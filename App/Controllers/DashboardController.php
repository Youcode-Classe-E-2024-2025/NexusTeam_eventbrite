<?php
namespace App\Controllers;

use App\Models\Dashboard;
use App\Core\Views;

class DashboardController {
    
    public function index(){
      $model = new Dashboard();
      $data = $model->Display();
      Views::render('users_management', ['users' => $data]);
    }


    public function deleteUser(){
       if($_SERVER['REQUEST_METHOD'] = 'POST' && isset($_POST['id'])){
            $user_id = intval($_POST['id']);
            $model = new Dashboard();
            
            if($model->deleteUser($user_id)){
              header ("Location: users_management");
            }else{
              echo 'Erreur : utilisateur introuvable ou suppression impossible';
            }
       }
    }
} 