<?php
namespace App\Controllers;

use App\Models\Dashboard;
use App\Core\Views;
use App\Core\Request;
use App\Core\Session;

class DashboardController {
    
    public function index(){
      $model = new Dashboard();
      $data = $model->Display();
      Views::render('Admin/usersIndex', ['users' => $data]);
    }


    public function DeleteUser(Request $request){
            if (empty($request->get('id'))){
              echo 'err';
            }

            $user_id = $request->get("id");
            $model = new Dashboard();
            
            if($model->deleteUser($user_id)){
              Session::set('message','deleted successfully');
            }else{
              Session::set('message', 'nn');
            }

            Views::redirect('/dashboard/admin');
    }



    public function BanUser(Request $request){
        if(empty($request->get('id'))){
          Session::set('message', 'error successfully');
        }
        $user_id = $request->get("id");
        $model = new Dashboard();

        if($model->BanUser($user_id,'banned')){

          Session::set('message', 'banned successfully');

        }else{
          Session::set('message', 'err successfully');
        }
        
        
        Views::redirect('/dashboard/admin');
    }


    public function unbanUser(Request $request){
      if(empty($request->get('id'))){
        Session::set('message', 'error successfully');
      }
      $user_id = $request->get("id");
      $model = new Dashboard();

      if($model->unbanUser($user_id,'active')){

        Session::set('message', 'actived successfully');

      }else{
        Session::set('message', 'err successfully');
      }
      
      
      Views::redirect('/dashboard/admin');

      
    }

    
















} 