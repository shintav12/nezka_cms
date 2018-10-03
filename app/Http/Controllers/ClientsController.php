<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use App\Utils\imageUploader;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class ClientsController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "clients",
            "smenu_active" => "",
            "page_title" => "Clientes",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('clients.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $clients = Clients::find($id);
            $clients->status = $status;
            $clients->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $clients = DB::select(DB::raw("select m.id, m.name, m.created_at, m.updated_at, m.status 
                                      from client m
                                      order by m.id ASC"));
        return DataTables::of($clients  )
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "clients",
            "smenu_active" => "",
            "page_title" => "Clientes",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        if($id != 0){
            $clients = Clients::find($id);
            $template['item'] = $clients;
        }

        return view('clients.detail',$template);
    }

    public function save(Request $request)
    {
        try {
            $id = Input::get('id');
            $name = Input::get('name');
            $image = Input::get('image');


            if ($id != 0) {
                $clients = Clients::find($id);
                $clients->updated_at = date('Y-m-d H:i:s');
            } else {
                $clients = new Clients();
                $clients->status = 1;
                $clients->created_at = date('Y-m-d H:i:s');
            }
            $clients->name = $name;
            $clients->image = "";
            $clients->save();

            if (!is_null($image)) {
                $path = imageUploader::upload($clients, $image, "clients");
                $clients->image = $path;
                $clients->save();
            }

            return response(json_encode(array("error" => 0, "id" => $clients->id)), 200);

        } catch (Exception $exception) {
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
