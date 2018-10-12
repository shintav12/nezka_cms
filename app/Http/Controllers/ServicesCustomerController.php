<?php

namespace App\Http\Controllers;

use App\Models\ServicesCustomer;
use App\Utils\imageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class ServicesCustomerController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "services_customer",
            "smenu_active" => "",
            "page_title" => "Servicios para Cotizacion",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('services_customer.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $services = ServicesCustomer::find($id);
            $services->status = $status;
            $services->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $services = DB::select(DB::raw("select m.id, m.name, m.created_at, m.updated_at, m.status 
                                      from services_customer m
                                      order by m.id ASC"));
        return DataTables::of($services)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "services",
            "smenu_active" => "",
            "page_title" => "Servicios",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        if($id != 0){
            $services = ServicesCustomer::find($id);
            $template['item'] = $services;
        }

        return view('services_customer.detail',$template);
    }

    public function save(Request $request)
    {
        try {
            $id = Input::get('id');
            $name = Input::get('name');
            $description = Input::get('description');
            $image = $request->file('image');


            if ($id != 0) {
                $services = ServicesCustomer::find($id);
                $services->updated_at = date('Y-m-d H:i:s');
            } else {
                $services = new ServicesCustomer();
                $services->status = 1;
                $services->created_at = date('Y-m-d H:i:s');
            }
            $services->name = $name;
            $services->description = $description;
            $services->image = "";
            $services->save();

            if (!is_null($image)) {
                $path = imageUploader::upload($services, $image, "services_customer");
                $services->image = $path;
                $services->save();
            }

            return response(json_encode(array("error" => 0, "id" => $services->id)), 200);

        } catch (Exception $exception) {
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
