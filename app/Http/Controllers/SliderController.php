<?php

namespace App\Http\Controllers;

use App\Models\SliderBanner;
use App\Utils\imageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;


class SliderController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "slider",
            "smenu_active" => "",
            "page_title" => "Slider",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('slider_banner.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $slider = SliderBanner::find($id);
            $slider->status = $status;
            $slider->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $slider = DB::select(DB::raw("select id, title, created_at, updated_at, status 
                                      from slider_banner
                                      order by id ASC"));
        return DataTables::of($slider)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "slider",
            "smenu_active" => "",
            "page_title" => "Slider",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        if($id != 0){
            $user = SliderBanner::find($id);
            $template['item'] = $user;
        }

        return view('slider_banner.detail',$template);
    }

    public function save(Request $request){
        try{
            $id = Input::get('id');
            $title = Input::get('title');
            $subtitle = Input::get('subtitle');
            $image = $request->file('image');


            if($id != 0) {
                $slider = SliderBanner::find($id);
                $slider->updated_at = date('Y-m-d H:i:s');
            }
            else{
                $slider  = new SliderBanner();
                $slider->status = 1;
                $slider->created_at = date('Y-m-d H:i:s');
                $slider->image = "";
            }
            $slider->title = $title;
            $slider->subtitle = $subtitle;
            $slider->save();

            if(!is_null($image)){
                $path = imageUploader::upload($slider,$image,"slider");
                $slider->image = $path;
                $slider->save();
            }

            return response(json_encode(array("error" => 0,"id" => $slider->id)), 200);

        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
