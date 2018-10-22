<?php

namespace App\Http\Controllers;

use App\Models\ClientType;
use App\Utils\imageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class ClientTypeController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "client_types",
            "smenu_active" => "",
            "page_title" => "Tipos de Cliente",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('client_types.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $services = ClientType::find($id);
            $services->status = $status;
            $services->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $client_types = DB::select(DB::raw("select m.id, m.name, m.created_at, m.updated_at, m.status 
                                      from customer_type m
                                      order by m.id ASC"));
        return DataTables::of($client_types)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "client_types",
            "smenu_active" => "",
            "page_title" => "Tipos de Cliente",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        if($id != 0){
            $services = ClientType::find($id);
            $template['item'] = $services;
        }

        return view('client_types.detail',$template);
    }

    public function save(Request $request)
    {
        try {
            $id = Input::get('id');
            $name = Input::get('name');
            $description = Input::get('description');
            $image = $request->file('image');


            if ($id != 0) {
                $client_types = ClientType::find($id);
                $client_types->updated_at = date('Y-m-d H:i:s');
            } else {
                $client_types = new ClientType();
                $client_types->status = 1;
                $client_types->created_at = date('Y-m-d H:i:s');
                $client_types->image = "";
            }
            $client_types->name = $name;
            $client_types->description = $description;
            $client_types->save();

            if (!is_null($image)) {
                $path = imageUploader::upload($client_types, $image, "client_types");
                $client_types->image = $path;
                $client_types->save();
            }

            return response(json_encode(array("error" => 0, "id" => $client_types->id)), 200);

        } catch (Exception $exception) {
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
