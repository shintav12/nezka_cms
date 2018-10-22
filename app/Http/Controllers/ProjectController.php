<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Clients;
use App\Models\Work;
use Illuminate\Http\Request;
use App\Utils\imageUploader;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class ProjectController extends BaseController
{
    public function index(){
        $template = array(
            "menu_active" => "projects",
            "smenu_active" => "",
            "page_title" => "Proyectos",
            "page_subtitle" => "",
            "user" => session('user')
        );
        return view('projects.index',$template);
    }

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $project = Project::find($id);
            $project->status = $status;
            $project->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function load(){
        $project = DB::select(DB::raw("select m.id, m.title, m.created_at, m.updated_at, m.status 
                                      from project m
                                      order by m.id ASC"));
        return DataTables::of($project)
            ->make(true);
    }

    public function detail($id = 0){
        $template = array(
            "menu_active" => "projects",
            "smenu_active" => "",
            "page_title" => "Proyectos",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        $template['clients'] = Clients::get(['id','name']);
        if($id != 0){
            $project = Project::find($id);
            $template['item'] = $project;
            $works = Work::where('project_id',$id)->get(['id','name','status','created_at','updated_at']);
            $template['works'] = $works;
        }

        return view('projects.detail',$template);
    }

    public function save(Request $request)
    {
        try {
            $id = Input::get('id');
            $title = Input::get('title');
            $image = $request->file('image');
            $client_id = Input::get('client_id');


            if ($id != 0) {
                $project = Project::find($id);
                $project->updated_at = date('Y-m-d H:i:s');
            } else {
                $project = new Project();
                $project->status = 1;
                $project->created_at = date('Y-m-d H:i:s');
                $slug = Project::get_slug($title, $project->getTable());
                $project->image = "";
                $project->slug = $slug;
            }
            $project->title = $title;
            
            $project->client_id = $client_id;
            $project->save();

            if (!is_null($image)) {
                
                $path = imageUploader::upload($project, $image, "projects");
                $project->image = $path;
                $project->save();
            }

            return response(json_encode(array("error" => 0, "id" => $project->id)), 200);

        } catch (Exception $exception) {
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
