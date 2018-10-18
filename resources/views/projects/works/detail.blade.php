@extends('layout.master')

@section('css')
<link href="{{asset("assets/global/plugins/file-input/css/fileinput.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/global/plugins/select2/css/select2.css")}}" rel="stylesheet" type="text/css" />
<link href="{{asset("assets/global/plugins/select2/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
 <link href="{{asset("assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css")}}" rel="stylesheet" type="text/css" />
@endsection

@section('scripts')
    <script src="{{asset("assets/global/plugins/jquery-validation/js/jquery.validate.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/jquery-validation/js/additional-methods.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/file-input/js/fileinput.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/select2/js/select2.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js")}}" type="text/javascript"></script>
    <script class="text/javascript">
        $(document).ready(function(){
            $('.switch').bootstrapSwitch(
                {
                    'size': 'mini'
                }
            );
            $("#input-24").fileinput({
                allowedFileExtensions: ["png","jpg"],
                uploadAsync: false,
                showUpload: false, // hide upload button
                showRemove: false,
                initialPreviewAsData: true,
                language: 'es',
                <?php if(isset($item)){?>
                initialPreview: [
                   "<?php echo config('app.path_url').'/'.$item->image.'?v='.strtotime($item->updated_at) ?>",
                ]
                <?php }?>
            });
            $('body').on('click','.delete',function(){
                $(this).parent().parent().remove();
            });

            <?php if(isset($gallery_images)){
            foreach($gallery_images as $gallery_image){?>
            $("#input-24{{$gallery_image->id}}").fileinput({
                allowedFileExtensions: ["jpg"],
                uploadAsync: false,
                showUpload: false, // hide upload button
                showRemove: false,
                initialPreviewAsData: true,
                maxFileSize: 300,
                language: 'es',
                initialPreview: [
                    "<?php echo config('app.path_url').'/'.$gallery_image->image.'?v='.strtotime($gallery_image->updated_at) ?>",
                ]
            });
            <?php }}?>

            $("#add_image").click(function(){
                $("#gallery_container").append('<div class="form-group new" style="padding-bottom:25px">'+
                    '<input hidden value="0" name="image_id[]"/>'+
                    '<div class="col-xs-12" style="padding-top:15px">'+
                    '<input class="input-fixed gallery_images" name="gallery_images[]" type="file" data-dirrty-initial-value="">'+
                    '</div>'+
                    '<div clasS="col-xs-12" style="padding-top:15px">' +
                    '<a class="btn btn-primary delete"><i class="fa fa-remove"></i> Eliminar</a>'+
                    '</div>'+
                    '</div>');
                $(".gallery_images").fileinput({
                    allowedFileExtensions: ["jpg"],
                    uploadAsync: false,
                    showUpload: false,
                    showRemove: false,
                    maxFileSize: 300,
                    initialPreviewAsData: true,
                    language: 'es'
                });
            });
            $(".select2").select2();
            $("#form-user").validate({
                errorPlacement: function errorPlacement(error, element) {
                    element.after(error);
                },
                rules: {
                    name: "required"
                },
                messages: {
                    name: "Campo requerido"
                },
                submitHandler: function (form) {
                        if($(".kv-fileinput-error").css("display") == "block"){
                            swal.close();
                            swal(
                                'Oops...',
                                'La imagen que intenta subir no cumple con la medida indicada o tiene un formato inválido',
                                'error'
                            );
                            return;
                        }
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: "{{ route('work_save') }}",
                            data: new FormData($("#form-user")[0]),
                            contentType: false,
                            processData: false,
                            beforeSend: function () {
                                swal({
                                    title: 'Cargando...',
                                    timer: 10000,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    onOpen: function () {
                                        swal.showLoading()
                                    }
                                });
                            },
                            success: function (data) {
                                var error = data.error;
                                if (error == 0) {
                                    window.location = "{{ url('projects/detail')}}/{{$project_id}}";
                                } else {
                                    swal.close();
                                    swal(
                                        'Oops...',
                                        'Algo ocurrió!',
                                        'error'
                                    );
                                }
                            }, error: function () {
                                swal.close();
                                swal(
                                    'Oops...',
                                    'Algo ocurrió!',
                                    'error'
                                );
                            }
                        });

                }
            });
        });
    </script>
@endsection

@section('body')
    <h1 class="page-title"> {{$page_title}}
        <small>{{$page_subtitle}}</small>
    </h1>
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-body form">
                    <form class="form-horizontal" role="form" id="form-user">
                        {{csrf_field()}}
                        <ul class="nav nav-tabs">
                            <li id="tab_li_1" class="tab-trigger active">
                                <a id="tab_1" href="#tab_1_1" data-toggle="tab"> General </a>
                            </li>
                            <li id="tab_li_2" class="tab-trigger">
                                <a id="tab_2" href="#tab_1_2" data-toggle="tab"> Galer&iacute;a </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1_1">
                                <input hidden name="id" value="<?php if( isset($item) )  echo $item->id; else echo 0;?>" />
                                <input hidden name="project_id" value="{{$project_id}}" />
                                <div class="row form-body">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Titulo</label>
                                                    <input type="text" class="form-control" name="name" value="<?php if( isset($item) )  echo $item->name;?>" placeholder="Ingrese el nombre del blog">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Descripcion</label>
                                                    <textarea type="text" class="form-control" name="description" placeholder="Ingrese el nombre del blog"><?php if( isset($item) )  echo $item->description;?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Tipo de Proyecto</label>
                                                    <select class="form-control" name="project_type_id">
                                                        @foreach($types as $type)
                                                            <option value="{{$type->id}}" <?php if(isset($item)) if($item->id == $type->id) echo "selected"?> >{{$type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Imagen</label>
                                                    <input id="input-24" class="input-fixed" name="image" type="file">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_1_2">
                                <div class="form-body">
                                    <div class="form-group">
                                        <div clas="col-xs-12">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <a id="add_image" class="btn btn-primary">Agregar Imágen</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div id="gallery_container">
                                            <?php if(isset($gallery_images)){
                                            foreach($gallery_images as $gallery_image){?>
                                            <div class="form-group" style="padding-bottom:25px">
                                                <input hidden value="{{$gallery_image->id}}" name="image_id[]"/>
                                                <div class="col-xs-12" style="padding-top:15px">
                                                    <input id="input-24{{$gallery_image->id}}" class="input-fixed gallery_images" name="gallery_images[]" type="file">
                                                </div>
                                                <div clasS="col-xs-12" style="padding-top:15px">
                                                    <a class="btn btn-primary delete"><i class="fa fa-remove"></i> Eliminar</a>
                                                </div>
                                            </div>
                                            <?php }}?>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-md-3 col-md-9">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <a type="button" href="{{url('projects/detail')}}/{{$project_id}}" class="btn default">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

