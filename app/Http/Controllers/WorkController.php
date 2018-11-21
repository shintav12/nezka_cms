<?php

namespace App\Http\Controllers;

use App\Models\ProjectTypes;
use App\Models\Work;
use App\Models\WorkImages;
use Illuminate\Http\Request;
use App\Utils\imageUploader;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class WorkController extends BaseController
{

    public function change_status(){
        try{
            $id = Input::get('id');
            $status = intval(Input::get('status'));
            $work = Work::find($id);
            $work->status = $status;
            $work->save();

            return response(json_encode(array("error" => 0)), 200);
        }catch(Exception $exception){
            return response(json_encode(array("error" => 1)), 200);
        }
    }

    public function detail($project_id, $id = 0){
        $template = array(
            "menu_active" => "projects",
            "smenu_active" => "",
            "page_title" => "Trabajos",
            "page_subtitle" => ($id == 0 ? "Nuevo" : "Editar" ),
            "user" => session('user')
        );

        $template['types'] = ProjectTypes::get(['id','name']);
        $template['project_id'] = $project_id;

        if($id != 0){
            $project = Work::find($id);
            $template['item'] = $project;
            $images = WorkImages::where('project_description_id',$id)->get();
            $template['gallery_images'] = $images;

        }

        return view('projects.works.detail',$template);
    }

    public function save(Request $request)
    {
        try {
            $id = Input::get('id');
            $name = Input::get('name');
            $description = Input::get('description');
            $project_id = Input::get('project_id');
            $detail = Input::get('detail');
            $categories = Input::get('categories');
            $project_type_id = Input::get('project_type_id');
            $image = $request->file('image');
            $gallery_images_id = Input::get('image_id');
            $media_type = Input::get('media_type');
            $gallery_images = array_key_exists('gallery_images', $_FILES) ? $_FILES['gallery_images'] : array();
            $gallery_text = Input::get('gallery_videos');
            
            if ($id != 0) {
                $work = Work::find($id);
                $work->updated_at = date('Y-m-d H:i:s');
            } else {
                $work = new Work();
                $work->status = 1;
                $work->created_at = date('Y-m-d H:i:s');
                $slug = work::get_slug($name, $work->getTable());
                $work->slug = $slug;
                $work->image = "";
            }
            $work->name = $name;
            $work->categories = $categories;
            $work->detail = $detail;
            $work->description = $description;
            $work->project_id = $project_id;
            $work->project_type_id = $project_type_id;
            
            $work->save();

            if (!is_null($image)) {
                
                $path = imageUploader::upload($work, $image, "work");
                $work->image = $path;
                $work->save();
            }

            WorkImages::where('project_description_id',$id)->update(["status" => 0]);
            if(is_array($gallery_images_id) && count($gallery_images_id)>0)
            {
                
                $j=0;
                for($i = 0 ; $i < count($gallery_images["tmp_name"]); $i++){
                    $id = intval($gallery_images_id[$i]);
                    if($id === 0){
                        $image_aux = new WorkImages();
                        $image_aux->image = "";
                    }else{
                        $image_aux = WorkImages::find($id);
                    }
                    $image_aux->project_description_id = $work->id;
                    $image_aux->status = 1;
                    $image_aux->save();
                    if($media_type[$i] == 1){
                        if($gallery_images["tmp_name"][$i] !== ""){
                            $path = imageUploader::upload($image_aux,$gallery_images["tmp_name"][$i],"gallery","slider");
                            $image_aux->image = $path;
                        }
                    }else{
                        $image_aux->image = $gallery_text[$j];
                        $image_aux->type ="video";
                        $j++;
                    }
                    $image_aux->save();
                }
            }
            WorkImages::where('project_description_id',$id)->where('status',0)->delete();

            

            return response(json_encode(array("error" => 0, "id" => $work->id)), 200);

        } catch (Exception $exception) {
            return response(json_encode(array("error" => 1)), 200);
        }
    }
}
