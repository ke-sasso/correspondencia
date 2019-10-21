
@extends('master')
{{-- CSS ESPECIFICOS --}}
@section('css')
{!! Html::style('plugins/bootstrap-fileinput/css/fileinput.min.css') !!}


{{-- Bootstrap Modal --}}

<style>
</style>
@endsection

{{-- CONTENIDO PRINCIPAL --}}
@section('contenido')
{{-- ERRORES DE VALIDACIÓN --}}
@if($errors->any())
	<div class="alert alert-warning square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<strong>Oops!</strong>
		Debes corregir los siguientes errores para poder continuar		
		<ul class="inline-popups">
			@foreach ($errors->all() as $error)
				<li  class="alert-link">{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
{{-- MENSAJE DE EXITO --}}
@if(Session::has('msnExito'))
	<div class="alert alert-success square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<strong>Enhorabuena!</strong>
		{{ Session::get('msnExito') }}
	</div>
@endif
{{-- MENSAJE DE ERROR --}}
@if(Session::has('msnError'))
	<div class="alert alert-danger square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
		<strong>Auchh!</strong>
		Algo ha salido mal.	{{ Session::get('msnError') }}
	</div>
@endif

	
		<form  role="form" id="infoGeneral" method="post"  action="{{route('guardar.asistencia.part')}}" autocomplete="off" enctype="multipart/form-data">
	
				<div class="panel-body">
        
				
			             <div class="form-group">
                     <div class="row">
                          <div class="col-sm-12 col-md-12">
                           <div class="input-group ">
                            <div class="input-group-addon"><b>Persona a enviar</b></div>
              <select name="emple[]" id="emple" data-placeholder="Seleccione  uno o varios colaboradores..." class="form-control chosen-select" multiple tabindex="-1">
                   <option value=""></option>
                    @foreach($empleados as $e)
                       
                       @if($e->idEmp==7 || $e->idEmp==Auth::user()->idEmpleado)
                       @else
                     <option value="{{$e->idEmp}}">{{$e->nombresEmpleado}}&nbsp;{{$e->apellidosEmpleado}}  ({{$e->prefijo}})</option>
                     
                       @endif
                    @endforeach
              </select>
                          </div>
                          </div>             
                    </div>
                </div>

                  <div class="form-group">
                     <div class="row">
                         <div class="col-sm-12 col-md-12">
                 <div class="input-group ">
                <div class="input-group-addon"><b>Mensaje</b></div></div>
                    <textarea name="comentario" id="comentario" class="summernote-sm"></textarea>
                    </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                     <div class="input-group">
                       <div class="input-group-addon"><b>Documento</b></div>
                       <input id="fileAsistencia" name="fileAsistencia" type="file" multiple class="file-loading"> 
                      
                       </div>
                     <div id="errorBlockNew" class="help-block"></div>
              </div>
        

				<div class="panel-footer text-center">
				 <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <input type="hidden" name="idSoliAsistencia" value="{{$idSolicitud}}" />
          <input type="hidden" name="tipo" value="{{$tipo}}" />
        <button type="submit" class="btn btn-primary btn-perspective">ENVIAR<i class="fa fa-check"></i></button>
         <a href="{{route('verSolicitud',['idSolicitud'=>Crypt::encrypt($idSolicitud)])}}" style="" type="button" id="cancelar" class="btn btn-warning btn-perspective">Regresar<i class="fa fa-reply" aria-hidden="true"></i></a>
				</div>
			</div>
			</form>


@include('solicitudes.paneles.busquedaPersona')
@include('solicitudes.paneles.ingresarPersona')
	
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')

{!! Html::script('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/fileinput.min.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/fileinput_locale_es.js') !!}
 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
{{-- Bootstrap Modal --}}

<script>

$(document).ready(function(){

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

  $("#fileAsistencia" ).fileinput({
      language: "es",
      overwriteInitial: true,
      browseLabel: 'Subir archivos',
      removeLabel: '',
      browseIcon: '<i class="fa fa-folder-open"></i>',
      removeIcon: '<i class="fa fa-times"></i>',
      removeTitle: 'Cancelar o resetear cambios',
      showUpload: false,
      showRemove: true,
      dropZoneEnabled: true,
       layoutTemplates: {main2: '{preview} {remove} {browse}'},
       msgErrorClass: 'alert alert-block alert-danger',
    });

 $('#infoGeneral').submit(function(e){
        var formObj = $(this);
        var formURL = formObj.attr("action");
      var formData = new FormData(this);
    $.ajax({
      data: formData,
      url: formURL,
      type: 'post',
      mimeType:"multipart/form-data",
        contentType: false,
          cache: false,
          processData:false,
      beforeSend: function() {
        $('body').modalmanager('loading');
      },
      success:  function (response){
            $('body').modalmanager('loading');
            if(isJson(response)){
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La información se ingreso con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                $('#notificarSoli').modal('hide'); 
                location.reload();
              });
              
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>¡ERROR: No se pudo registrar la informaci&oacute;n!</p></strong>");
              console.log("Error en peticion AJAX!");  
          }
    });
    e.preventDefault(); //Prevent Default action. 

    }); 
   function isJson(str) {
      try {
          JSON.parse(str);
      } catch (e) {
          return false;
      }
      return true;
  }
  });



  

</script>
@endsection
