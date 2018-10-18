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
                allowedFileExtensions: ["jpg","png"],
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
                            url: "{{ route('slider_save') }}",
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
                                    window.location = "{{ url(route('slider'))}}";
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
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1_1">
                                <input hidden name="id" value="<?php if( isset($item) )  echo $item->id; else echo 0;?>" />
                                <div class="row form-body">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Titulo</label>
                                                    <input type="text" class="form-control" name="title" value="<?php if( isset($item) )  echo $item->title;?>" placeholder="Ingrese el nombre del blog">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <label>Subtitulo</label>
                                                    <input type="text" class="form-control" name="subtitle" value="<?php if( isset($item) )  echo $item->subtitle;?>" placeholder="Ingrese el nombre del blog">
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
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-md-3 col-md-9">
                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                        <a type="button" href="{{route('slider')}}" class="btn default">Cancel</a>
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

