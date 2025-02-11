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
}