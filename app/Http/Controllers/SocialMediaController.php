<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use App\Utils\imageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class SocialMediaController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "social_media",
            "smenu_active" => "",
            "page_title" => "Redes",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('social_media.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $social_media = SocialMedia::find($id);
            $social_media->status = $status;
            $social_media->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $social_media = DB::select(DB::raw("select m.id, m.name, m.created_at, m.updated_at, m.status 
                                      from social_media m
                                      order by m.id ASC"));
        return DataTables::of($social_media)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "social_media",
            "smenu_active" => "",
            "page_title" => "Redes",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        if($id != 0){
            $user = SocialMedia::find($id);
            $template['item'] = $user;
        }

        return view('social_media.detail',$template);
    }

    public function save(Request $request){
        try{
            $id = Input::get('id');
            $name = Input::get('name');
            $url = Input::get('url');
            $image = Input::get('image');


            if($id != 0) {
                $socialmedia = SocialMedia::find($id);
                $socialmedia->updated_at = date('Y-m-d H:i:s');
            }
            else{
                $slider  = new SliderBanner();
                $slider->status = 1;
                $slider->created_at = date('Y-m-d H:i:s');
                $socialmedia->image = "";
            }
            $socialmedia->name = $name;
            $socialmedia->url = $url;
            $socialmedia->save();

            if(!is_null($image)){
                $path = imageUploader::upload($socialmedia,$image,"social_media");
                $socialmedia->image = $path;
                $socialmedia->save();
            }

            return response(json_encode(array("error" => 0,"id" => $socialmedia->id)), 200);

        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
