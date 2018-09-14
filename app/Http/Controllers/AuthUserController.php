<?php

namespace App\Http\Controllers;

use App\Models\SocialNetwork;
use App\Utils\imageUploader;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\Datatables\Datatables;
use App\Models\AuthUser;
use App\Models\AuthUserObject;
use App\Models\AuthRole;

class AuthUserController extends BaseController {

    public function index(){
        $template = array(
            "menu_active" => "auth_users",
            "smenu_active" => "",
            "page_title" => "Usuarios",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('AuthUsers.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $menu = AuthUser::find($id);
            $menu->status = $status;
            $menu->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $users = DB::select(DB::raw("select m.id, concat(m.first_name,' ', m.last_name) as name, ar.name as role, m.created_at, m.updated_at, m.status 
                                      from auth_user m
                                      left join auth_role as ar on m.role_id = ar.id
                                      where m.role_id != 6
                                      order by m.id ASC"));
        return Datatables::of($users)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "auth_users",
            "smenu_active" => "",
            "page_title" => "Usuarios",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user'),
            "role"=> AuthRole::whereRaw("id NOT IN (2,3)")->get(["id","name"])
        );

        if($id != 0){
            $user = AuthUser::find($id);
            $template['item'] = $user;
            $template['select_social_network'] = AuthUser::leftJoin('auth_user_social as aus','aus.auth_user_id','=','auth_user.id')
                                                            ->where('auth_user.id','=',$id)
                                                            ->select([
                                                                'aus.auth_user_id',
                                                                'aus.social_network_id',
                                                                'aus.url'
                                                            ])->get()->keyBy('social_network_id');
        }

        $template['social_network'] = SocialNetwork::all();

        return view('AuthUsers.detail',$template);
    }

    public function save(Request $request){
        try{
            $user = session('user');
            $id = Input::get('id');
            $name = Input::get('name');
            $last_name = Input::get('last_name');
            $email = Input::get('email');
            $username = Input::get('user');
            $password = Input::get('password');
            $samepassword = Input::get('password_other');
            $role_id = Input::get("role_id");

            $bio = Input::get('bio');
            $path = $request->file('path');

            if($id != 0) {
                $auth_user = AuthUser::find($id);
                $auth_user->updated_by = $user->id;
                $auth_user->updated_at = date('Y-m-d H:i:s');
            }
            else{
                $auth_user  = new AuthUser();
                $auth_user->status = 1;
                $auth_user->created_by = $user->id;
                $auth_user->created_at = date('Y-m-d H:i:s');
            }

            $auth_user->first_name = $name;
            $auth_user->last_name = $last_name;
            $auth_user->user = $username;
            if($password != "" || !is_null($password))
                $auth_user->password = $password;
            $auth_user->email = $email;
            $auth_user->role_id = $role_id;

            if($samepassword != $password){
                return response(json_encode(array("error" => 2)), 200);
            }

            if($role_id != 6){
                $auth_user->bio = $bio;
                if(!is_null($path)){
                    $path = imageUploader::upload_s3($auth_user,$path,"auth_user");
                    $auth_user->path = $path;
                }
            }

            $auth_user->save();

            $social_network = SocialNetwork::all();

            foreach($social_network as $key => $value){
                if($request->has("social_network_".$value->id)){
                    if($auth_user->social_network->contains($value->id)){
                        $auth_user->social_network()->sync([$value->id => [ 'url' => $request['social_network_'.$value->id] ]],false);
                    }else{
                        $auth_user->social_network()->attach([$value->id => [ 'url' => $request['social_network_'.$value->id] ]]);
                    }
                }
            }

            return response(json_encode(array("error" => 0,"id" => $auth_user->id)), 200);

        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

   
}
