<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Utils\imageUploader;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class NewsController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "news",
            "smenu_active" => "",
            "page_title" => "Noticias",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('news.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $news = News::find($id);
            $news->status = $status;
            $news->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $news = DB::select(DB::raw("select m.id, m.title, m.created_at, m.updated_at, m.status 
                                      from blog m
                                      order by m.id ASC"));
        return DataTables::of($news)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "news",
            "smenu_active" => "",
            "page_title" => "Noticias",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        if($id != 0){
            $news = News::find($id);
            $template['item'] = $news;
        }

        return view('news.detail',$template);
    }

    public function save(Request $request)
    {
        try {
            $id = Input::get('id');
            $title = Input::get('title');
            $subtitle = Input::get('subtitle');
            $link = Input::get('link');
            $image = $request->file('image');


            if ($id != 0) {
                $news = News::find($id);
                $news->updated_at = date('Y-m-d H:i:s');
            } else {
                $news = new News();
                $news->status = 1;
                $news->created_at = date('Y-m-d H:i:s');
                $news->image = "";
            }

            $news->title = $title;
            $news->subtitle = $subtitle;
            $news->url = $link;

            $news->save();

            if (!is_null($image)) {
                $path = imageUploader::upload($news, $image, "news");
                $news->image = $path;
                $news->save();
            }

            return response(json_encode(array("error" => 0, "id" => $news->id)), 200);

        } catch (Exception $exception) {
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
