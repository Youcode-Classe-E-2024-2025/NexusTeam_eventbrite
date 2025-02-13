<?php
namespace App\Controllers;

use App\Models\DashboardUser;
use App\Core\Views;
use App\Core\Request;
use App\Core\Session;

class DashboardUserController {
    
    public function index(){
      $model = new DashboardUser();
      $data = $model->Display();
      Views::render('Admin/usersIndex', ['users' => $data]);
      // Views::render('Admin/eventIndex',['events'=>$data]);

    }


    public function DeleteUser(Request $request){
            if (empty($request->get('id'))){
              echo 'err';
            }

            $user_id = $request->get("id");
            $model = new DashboardUser();
            
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
        $model = new DashboardUser();

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
      $model = new DashboardUser();

      if($model->unbanUser($user_id,'active')){

        Session::set('message', 'actived successfully');

      }else{
        Session::set('message', 'err successfully');
      }
      
      
      Views::redirect('/dashboard/admin');

      
    }



    // public function displayEvent(){

    // }





    
















} 