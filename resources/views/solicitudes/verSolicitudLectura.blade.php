<?php
  $permisos = App\UserOptions::getAutUserOptions();
  $estadoPart = App\Models\Solicitud\Participantes::getEstado($info->idSolicitud);
  $casoPart = App\Models\Solicitud\Participantes::getCaso($info->idSolicitud);
  $permisoPart = App\Models\Solicitud\Participantes::getPermiso($info->idSolicitud);
  $comentariosDestino =  App\Models\Solicitud\ComentarioDestino::getComentariosDest($info->idSolicitud);
?>

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

<div class="col-sm-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                <h3 class="panel-title">Información general</h3>
                   @include('solicitudes.paneles.aprobarSolicitud')
                </div>
                <div class="panel-body">
                  @include('solicitudes.paneles.verInfoGeneral')
                  <br>
                   @include('solicitudes.paneles.verArchivos')
                   <br>
                     @include('solicitudes.paneles.verTitularesRegistrados')
                </div><!-- /.panel-body -->
                <div class="panel-footer"></div>
              </div><!-- /.panel panel-success -->
  </div><!-- /.col-sm-4 -->
 @if(in_array(477, $permisos, true))
  <div class="col-sm-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                <h3 class="panel-title">Detalles de solicitud</h3>
                </div>
                <div class="panel-body">
                  @include('solicitudes.guardarDetalle')
                </div><!-- /.panel-body -->
                <div class="panel-footer"></div>
              </div><!-- /.panel panel-success -->
  </div><!-- /.col-sm-4 -->
  @else
  <div class="col-sm-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                <h3 class="panel-title">Detalles de solicitud</h3>
                </div>
                <div class="panel-body">
                  @include('solicitudes.verDetalle')
                </div><!-- /.panel-body -->
                <div class="panel-footer"></div>
              </div><!-- /.panel panel-success -->
  </div><!-- /.col-sm-4 -->

  @endif
  @if(!empty($usuarioEntregado))
    <div class="col-sm-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                <h3 class="panel-title">Usuario de entrega</h3>
                </div>
                <div class="panel-body">
                    @include('solicitudes.paneles.usuarioEntrega')
                </div><!-- /.panel-body -->
                <div class="panel-footer"></div>
              </div><!-- /.panel panel-success -->
  </div><!-- /.col-sm-4 -->
  @endif
  <div class="col-sm-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                <h3 class="panel-title">Archivos / Anexos</h3>
                </div>
                <div class="panel-body">
                     @include('solicitudes.paneles.archivosAnexos')
                </div><!-- /.panel-body -->
                <div class="panel-footer"></div>
              </div><!-- /.panel panel-success -->
  </div><!-- /.col-sm-4 -->



  @if($info->idClasificacion==1)

  <div class="col-sm-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                <h3 class="panel-title">Comentario</h3>
                </div>
                <div class="panel-body">
                     @include('solicitudes.paneles.verComentariosAsistente')
                </div><!-- /.panel-body -->
                <div class="panel-footer"></div>
              </div><!-- /.panel panel-success -->
  </div><!-- /.col-sm-4 -->

  @endif

<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" class="form-control"/>
@include('solicitudes.paneles.notificarSolicitud')
@include('solicitudes.paneles.ingresarParticipantes')
@include('solicitudes.paneles.modificarFecha')
@include('solicitudes.paneles.busquedaPersona')
@endsection

{{-- JS ESPECIFICOS --}}
@section('js')

{!! Html::script('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/fileinput.min.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/fileinput_locale_es.js') !!}
 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}
{{-- Bootstrap Modal --}}
   @if(Session::has('msnError1'))
         <script type="text/javascript">
           alertify.error('Problemas al registrar comentario');
           {{Session::forget('msnError1')}}
         </script>
    @endif
   @if(Session::has('msnExito1'))
         <script type="text/javascript">
           alertify.success('¡Comentario registrado con exito!');
           {{Session::forget('msnExito1')}}
         </script>
    @endif

<script>
 $('[data-toggle="tooltip"]').tooltip();
var table;
var dataAddProds = [];
var dtPersona;

$(document).ready(function(){


var val=document.getElementById("idFechaResp").value;
  if(val==3){
 document.getElementById('divDias').style.display = 'block';
  }else{
   document.getElementById('divDias').style.display = 'none';
  }


var valCla=document.getElementById("idClasificacion").value;
if(valCla==1){
 document.getElementById('valClasificacion').style.display = 'block';
}else{
   document.getElementById('valClasificacion').style.display = 'none';
}




$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

  $("#fileAnexos" ).fileinput({
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
     $("#fileRespuesta").fileinput({
    language: "es",
     overwriteInitial: true,
      showClose: false,
      showCaption: false,
        showPreview: true,
        elErrorContainer: "#errorBlock",
        showUpload: false,
        layoutTemplates: {main2: '{preview} {remove} {browse}'},
        browseLabel: '',
      removeLabel: '',
       browseIcon: '<i class="fa fa fa-paperclip"></i>',
      removeIcon: '<i class="fa fa-ban"></i>',
      removeTitle: 'Cancelar o resetear cambios'
    });
     $("#file").fileinput({
    language: "es",
     overwriteInitial: true,
      showClose: false,
      showCaption: false,
        showPreview: true,
        elErrorContainer: "#errorBlock",
        showUpload: false,
        layoutTemplates: {main2: '{preview} {remove} {browse}'},
        browseLabel: '',
      removeLabel: '',
       browseIcon: '<i class="fa fa fa-paperclip"></i>',
      removeIcon: '<i class="fa fa-ban"></i>',
      removeTitle: 'Cancelar o resetear cambios'
    });
         $("#fileAsesor").fileinput({
    language: "es",
     overwriteInitial: true,
      showClose: false,
      showCaption: false,
        showPreview: true,
        elErrorContainer: "#errorBlock",
        showUpload: false,
        layoutTemplates: {main2: '{preview} {remove} {browse}'},
        browseLabel: '',
      removeLabel: '',
       browseIcon: '<i class="fa fa fa-paperclip"></i>',
      removeIcon: '<i class="fa fa-ban"></i>',
      removeTitle: 'Cancelar o resetear cambios'
    });

        $("#fileA").fileinput({
    language: "es",
     overwriteInitial: true,
      showClose: false,
      showCaption: false,
        showPreview: true,
        elErrorContainer: "#errorBlock2",
        showUpload: false,
        layoutTemplates: {main2: '{preview} {remove} {browse}'},
        browseLabel: '',
      removeLabel: '',
       browseIcon: '<i class="fa fa fa-paperclip"></i>',
      removeIcon: '<i class="fa fa-ban"></i>',
      removeTitle: 'Cancelar o resetear cambios'
    });

             $("#fileAprobar").fileinput({
    language: "es",
     overwriteInitial: true,
      showClose: false,
      showCaption: false,
        showPreview: true,
        elErrorContainer: "#errorBlock2",
        showUpload: false,
        layoutTemplates: {main2: '{preview} {remove} {browse}'},
        browseLabel: '',
      removeLabel: '',
       browseIcon: '<i class="fa fa fa-paperclip"></i>',
      removeIcon: '<i class="fa fa-ban"></i>',
      removeTitle: 'Cancelar o resetear cambios'
    });


$('#formDetalle').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Informaci&oacute;n registrada de forma exitosa!</p></strong>",function(){
                var obj =  JSON.parse(response);
                 location.reload();
              });

            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo registrar la informaci&oacute;n de las solicitud!</p></strong>");
              console.log("Error en peticion AJAX!");
          }
    });
    e.preventDefault(); //Prevent Default action.

    });

  $('#infoGeneral').submit(function(e){

    var form = new FormData($("#infoGeneral")[0]);
    event.preventDefault();
    $('#infoGeneral :input').each(function(index, el) {
        $(this).parent().removeClass('has-error');
    });
    $.ajax({
      url: '{{route('guardar.comentario.solicitud')}}',
      type: 'POST',
      dataType: 'JSON',
      data: form,
      processData: false,
      contentType: false
    })
    .done(function(response) {
      try {

          if(response.status == 200)
          {
            alertify.alert('Mensaje del Sistema',response.message,function(){
            location.reload();
            //window.open("{{route('pdf.boleta.presentacion')}}");
            });

          }
          else {
            alertify.alert('Mensaje del Sistema',response.message);
          }

      } catch(e) {
        // statements
        console.log(e);
      }
    })
    .fail(function(r) {
      if(r.status == 422)
      {
        var texto = '';
        $.each(r.responseJSON, function(index, val) {
           texto += val[0]+'<br>';
           $('#'+index).parent().addClass('has-error');
        });
        alertify.alert('Mensaje del Sistema',texto);
      }
      else
      {
        var mensajes = ''
        $.each(r, function(index, val) {
          mensajes += val+'<br>';
        });

        alertify.alert('Mensaje del Sistema',mensajes);
      }
    })
    .always(function() {
      console.log("complete");
    });

  });
   $('#comentarioColaborador').submit(function(e){

    var form = new FormData($("#comentarioColaborador")[0]);
    event.preventDefault();
    $('#comentarioColaborador :input').each(function(index, el) {
        $(this).parent().removeClass('has-error');
    });
    $.ajax({
      url: '{{route('guardar.comentario.colaborador')}}',
      type: 'POST',
      dataType: 'JSON',
      data: form,
      processData: false,
      contentType: false
    })
    .done(function(response) {
      try {

          if(response.status == 200)
          {
            alertify.alert('Mensaje del Sistema',response.message,function(){
            location.reload();
            });

          }
          else {
            alertify.alert('Mensaje del Sistema',response.message);
          }

      } catch(e) {
        // statements
        console.log(e);
      }
    })
    .fail(function(r) {
      if(r.status == 422)
      {
        var texto = '';
        $.each(r.responseJSON, function(index, val) {
           texto += val[0]+'<br>';
           $('#'+index).parent().addClass('has-error');
        });
        alertify.alert('Mensaje del Sistema',texto);
      }
      else
      {
        var mensajes = ''
        $.each(r, function(index, val) {
          mensajes += val+'<br>';
        });

        alertify.alert('Mensaje del Sistema',mensajes);
      }
    })
    .always(function() {
      console.log("complete");
    });

  });

   $('#comentarioAsesor').submit(function(e){

    var form = new FormData($("#comentarioAsesor")[0]);
    event.preventDefault();
    $('#comentarioAsesor :input').each(function(index, el) {
        $(this).parent().removeClass('has-error');
    });
    $.ajax({
      url: '{{route('guardar.comentario.asesor')}}',
      type: 'POST',
      dataType: 'JSON',
      data: form,
      processData: false,
      contentType: false
    })
    .done(function(response) {
      try {

          if(response.status == 200)
          {
            alertify.alert('Mensaje del Sistema',response.message,function(){
            location.reload();
            });

          }
          else {
            alertify.alert('Mensaje del Sistema',response.message);
          }

      } catch(e) {
        // statements
        console.log(e);
      }
    })
    .fail(function(r) {
      if(r.status == 422)
      {
        var texto = '';
        $.each(r.responseJSON, function(index, val) {
           texto += val[0]+'<br>';
           $('#'+index).parent().addClass('has-error');
        });
        alertify.alert('Mensaje del Sistema',texto);
      }
      else
      {
        var mensajes = ''
        $.each(r, function(index, val) {
          mensajes += val+'<br>';
        });

        alertify.alert('Mensaje del Sistema',mensajes);
      }
    })
    .always(function() {
      console.log("complete");
    });

  });

  $('#comentarioResponsable').submit(function(e){

    var form = new FormData($("#comentarioResponsable")[0]);
    event.preventDefault();
    $('#comentarioResponsable :input').each(function(index, el) {
        $(this).parent().removeClass('has-error');
    });
    $.ajax({
      url: '{{route('guardar.comentario.responsable')}}',
      type: 'POST',
      dataType: 'JSON',
      data: form,
      processData: false,
      contentType: false
    })
    .done(function(response) {
      try {

          if(response.status == 200)
          {
            alertify.alert('Mensaje del Sistema',response.message,function(){
            location.reload();
            });

          }
          else {
            alertify.alert('Mensaje del Sistema',response.message);
          }

      } catch(e) {
        // statements
        console.log(e);
      }
    })
    .fail(function(r) {
      if(r.status == 422)
      {
        var texto = '';
        $.each(r.responseJSON, function(index, val) {
           texto += val[0]+'<br>';
           $('#'+index).parent().addClass('has-error');
        });
        alertify.alert('Mensaje del Sistema',texto);
      }
      else
      {
        var mensajes = ''
        $.each(r, function(index, val) {
          mensajes += val+'<br>';
        });

        alertify.alert('Mensaje del Sistema',mensajes);
      }
    })
    .always(function() {
      console.log("complete");
    });

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


function listarComentatios(){
            $.get("{{route('get.comentarios.solicitud')}}?id="+{{$info->idSolicitud}}, function(data) {

                    try{

                          $.each(data, function(i, value) {

                      var a = value.avatar;
                      var b = value.tipoImagen.trim();
                       if(value.avatar=='' || value.avatar==null){
                          $('#list').append('<li class="media"><a class="pull-right"><img class="media-object img-circle" src="{{ asset('img/avatar/default_avatar_male.jpg') }}" alt="Avatar"></a><div class="media-body me"><p class="name"><small>'+value.nombresEmpleado+' '+value.apellidosEmpleado+'</small></p> <p class="small">"'+value.comentario+'"</p><p class="comment-action"><a data-toggle="tooltip" title="25 likes" class="btn btn-xs btn-default btn-square"><i class="fa fa-thumbs-up"></i> 25</a></p><p class="text-right" >'+value.fechaCreacion+'</p> </div></li>');
                        }else{
                             $('#list').append('<li class="media"><a class="pull-right"><img class="media-object img-circle" src="data:'+b+';base64,'+a+'"  alt="Avatar"></a><div class="media-body me"><p class="name"><small>'+value.nombresEmpleado+' '+value.apellidosEmpleado+'</small></p> <p class="small">"'+value.comentario+'"</p><p class="comment-action"><a data-toggle="tooltip" title="Descargar Archivo" class="btn btn-xs btn-default btn-square" onclick="abrirArchivo(\''+value.avatar+'\',\''+value.tipoImagen+'\');" ><i class="fa fa-download"></i></a></p><p class="text-right" >'+value.fechaCreacion+'</p> </div></li>');
                        }

                                });

                        }
                    catch(e)
                    {
                      console.log(e);
                    }

                  });


}

function abrirArchivo(archivo,tipoA){

   window.open("{{url('solicitud/verDocumento')}}/"+url+"/"+tipo);
}

function aprobarSolicitud(idSolicitud){
    var token =$('#_token').val();
    var idSolicitud = idSolicitud;
    $.ajax({

            url:   "{{route('aprobar.solicitud')}}",
            type:  'post',
            data:'idSolicitud='+idSolicitud+'&_token='+token,
            beforeSend: function() {
               $('body').modalmanager('loading');
            },
            success:  function (r){
                $('body').modalmanager('loading');
                if(r.status == 200){

                   notificarSolicitud(idSolicitud);
                  // table.ajax.reload(null,false);
                }
                else if (r.status == 400){
                    alertify.alert("Mensaje de sistema - Error",r.message);
                }else if(r.status == 401){
                    alertify.alert("Mensaje de sistema",r.message
                    );
                }else{
                    //Unknown
                    //alertify.alert("Mensaje de sistema","Este mandamiento no ha sido pagado o ya ha sido utilizado");
                }
            },
            error: function(data){
                // Error...
                var errors = $.parseJSON(data.responseText);
                console.log(errors);
                $.each(errors, function(index, value) {
                    $.gritter.add({
                        title: 'Error',
                        text: value
                    });
                });
            }
        });
}
$(document).on('click','.ckb-check',function(e) {
   if (this.checked) {
        dataAddProds.push([$(this).data("idtit"),$(this).data("nomtit"),$(this).data("apetit")]);
        console.info(dataAddProds);

      } else {

      }
    data=dataAddProds;
});
function notificarSolicitud(idSolicitud){

$('#idSoliNotificar').val(idSolicitud);
$('#notificarSoli').modal('toggle');


$('#formModalNoti').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La observación se ingreso correctamente!</p></strong>",function(){
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

}
function asignarParticipantes(id){
 dataAddProds.length=0;
             $('#dt-empleados').DataTable({
                               filter:true,
                              destroy: true,
                              pageLength: 5,
                              ajax: {
                                url: "{{route('get.empleados.jefes')}}"

                              },
                              columns:[
                                      {data: 'idEmp',name:'idEmp'},
                                      {data: 'nombresEmpleado',name:'nombresEmpleado'},
                                       {data: 'apellidosEmpleado',name:'apellidosEmpleado'},
                                       {data: 'nombreUnidad',name:'nombreUnidad'},
                                       {data: 'prefijo',name:'prefijo'},

                            {searchable: false,
                              "mData": null,
                              "bSortable": false,
                              "mRender": function (data,type,full) {
                                if(data.alerta==1){
                                    return '';
                                }
                                else{
                                     return '<input type=checkbox value="test your look ;)" name="idrepres"  data-idtit="'+data.idEmp+'" data-nomtit="'+data.nombresEmpleado+'"  data-apetit="'+data.apellidosEmpleado+'" class="ckb-check">'
                                  /*return '<input type="checkbox"  name="idParticipante[]"  id="idParticipante" value="'+data.idEmp+'"  class="ckb-check">'*/
                                }
                              }
                            }
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"

                          },
                           "columnDefs": [ {
                         "searchable": false,
                         "orderable": false,
                         "targets": [3]
                          } ],
                          "order": [[ 1, 'asc' ]]
          });

          $('#panelEmpleados').modal('toggle');

          $('#odParticipantes').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Se ingreso correctamente los nuevos participantes!</p></strong>",function(){
                var obj =  JSON.parse(response);
                $('#panelEmpleados').modal('hide');
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

     }
     function entregadaSolicitud(idSolicitud){

    var token =$('#_token').val();
    $.ajax({

            url:   "{{route('entregada.solicitud')}}",
            type:  'post',
            data:'idSolicitud='+idSolicitud+'&_token='+token,
            beforeSend: function() {
               $('body').modalmanager('loading');
            },
            success:  function (r){
                $('body').modalmanager('loading');
                if(r.status == 200){
                  alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La solicitud cambio de estado correctamente!</p></strong>",function(){
                location.reload();
              });

                }
                else if (r.status == 400){
                    alertify.alert("Mensaje de sistema - Error",r.message);
                }else if(r.status == 401){
                    alertify.alert("Mensaje de sistema",r.message
                    );
                }else{
                    //Unknown
                    //alertify.alert("Mensaje de sistema","Este mandamiento no ha sido pagado o ya ha sido utilizado");
                }
            },
            error: function(data){
                // Error...
                var errors = $.parseJSON(data.responseText);
                console.log(errors);
                $.each(errors, function(index, value) {
                    $.gritter.add({
                        title: 'Error',
                        text: value
                    });
                });
            }
        });
}

function modificarFecha(idParticipante,fecha){
var idParticipante = idParticipante;
$('#idmodificarPart').val(idParticipante);
$.get("{{route('get.fecha.Titular')}}?id="+idParticipante, function(data) {
  try{
    document.getElementById("fechaParticipante").value = data.fechaRespuesta;
  }catch(e){console.log(e);}

                  });

$('#fechaPar').modal('toggle');


$('#formFechaParte').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Se modifico la fecha de respuesta!</p></strong>",function(){
                var obj =  JSON.parse(response);
                $('#fechaPar').modal('hide');
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

}
function estadoComentario(id,idSoli){

  alertify.confirm('Mensaje de sistema','¿Esta seguro que desea aprobar esa respuesta?',function(){
    $.ajax({
      data : {_token:'{{ csrf_token() }}',txt: id,idSolicitud: idSoli},
      url: "{{ route('estado.comentario') }}",
      type: "post",
      cache: false,
      mimeType:"multipart/form-data",
       beforeSend: function() {
                $('body').modalmanager('loading');
              },
          success:  function (response){
            $('body').modalmanager('loading');
                    if(isJson(response)){
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La respuesta esta aprobado!</p></strong>",function(){
                        var obj =  JSON.parse(response);
                       location.reload();
                      });

                    }else{
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
                    }
                  },
          error: function(jqXHR, textStatus, errorThrown) {
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: al actualizar el comentario!</p></strong>");
              console.log("Error en peticion AJAX!");
          }
    });
      },null).set('labels', {ok:'SI', cancel:'NO'});

}

  function enviarTitular(){

  if(dataAddProds.length==0){
   //document.getElementById('cont-tit').style.display = 'none';
   // document.getElementById('enviarParte').style.display = 'none';
  }else{
  $('#panelEmpleados').modal('hide');
  document.getElementById('cont-tit').style.display = 'block';
   //document.getElementById('enviarParte').style.display = 'block';

  for (var i =0; i< dataAddProds.length;i++) {
    $('#dt-Tit').append('<tr><td><input type="hidden" name="idParticipante[]" value="'+dataAddProds[i][0]+'" >'+dataAddProds[i][1]+' '+dataAddProds[i][2]+'</td></td><td><a class="btn btn-danger btn-perspective btnEliminar"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td></tr>');

      }
    }

  }
  $("#dt-Tit").on('click', '.btnEliminar', function () {
      $(this).closest('tr').remove();

  })

  function elminarParticipantes(id,idSolicitud){
  alertify.confirm('Mensaje de sistema','¿Esta seguro que desea elimiar este participante?',function(){
    $.ajax({
      data : {_token:'{{ csrf_token() }}',txtParticipante: id,idSol:idSolicitud},
      url: "{{ route('eliminar.participante') }}",
      type: "post",
      cache: false,
      mimeType:"multipart/form-data",
       beforeSend: function() {
                $('body').modalmanager('loading');
              },
          success:  function (response){
            $('body').modalmanager('loading');
                    if(isJson(response)){
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Registro eliminado exitosamente!</p></strong>",function(){
                        var obj =  JSON.parse(response);
                        location.reload();
                      });

                    }else{
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
                    }
                  },
          error: function(jqXHR, textStatus, errorThrown) {
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo eliminar el participante!</p></strong>");
              console.log("Error en peticion AJAX!");
          }
         });
      },null).set('labels', {ok:'SI', cancel:'NO'});
}

function habilitarCaso(id,idSolicitud){
  alertify.confirm('Mensaje de sistema','¿Esta seguro que desea abrir caso al participante?',function(){
    $.ajax({
      data : {_token:'{{ csrf_token() }}',txtParticipante: id,idSol:idSolicitud},
      url: "{{ route('abrircaso.participante') }}",
      type: "post",
      cache: false,
      mimeType:"multipart/form-data",
       beforeSend: function() {
                $('body').modalmanager('loading');
              },
          success:  function (response){
            $('body').modalmanager('loading');
                    if(isJson(response)){
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Se habilitó con exito al participante!</p></strong>",function(){
                        var obj =  JSON.parse(response);
                        location.reload();
                      });

                    }else{
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
                    }
                  },
          error: function(jqXHR, textStatus, errorThrown) {
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: con la información!</p></strong>");
              console.log("Error en peticion AJAX!");
          }
         });
      },null).set('labels', {ok:'SI', cancel:'NO'});
}

function isJson(str) {
      try {
          JSON.parse(str);
      } catch (e) {
          return false;
      }
      return true;
  }

function SelectChanged()  {
    var porId=document.getElementById("idFechaResp").value;
    if(porId==3){
        document.getElementById('divDias').style.display = 'block';
         document.getElementById('dias').value = '';
    }else{
      document.getElementById('divDias').style.display = 'none';
    }
 }
 function SelectChanged2()  {
    var porId2=document.getElementById("idClasificacion").value;
    if(porId2==1){
        document.getElementById('valClasificacion').style.display = 'block';
    }else{
      document.getElementById('valClasificacion').style.display = 'none';
    }
 }

 function ObservarSolicitud(){
   document.getElementById('txtComen').style.display = 'block';
   document.getElementById('btnArchivo').style.display = 'block';
   document.getElementById('btnFormulario').style.display = 'block';
   document.getElementById('btnPrincipales').style.display = 'none';
   $('#idTipo').val(1);
}
 function resComentar(idbutton){
   if(idbutton==1){
      $('#tipoComen').val(1);
   }else{
     $('#tipoComen').val(2);
   }
   document.getElementById('responsableComen').style.display = 'block';
   document.getElementById('buttonResponsable').style.display = 'none';

}
 function favorableSolicitud(){
    document.getElementById('txtComen').style.display = 'block';
   document.getElementById('btnArchivo').style.display = 'block';
   document.getElementById('btnFormulario').style.display = 'block';
   document.getElementById('btnPrincipales').style.display = 'none';
   $('#idTipo').val(2);
   /* alertify.confirm('Mensaje de sistema','¿Esta seguro que está solicitud es favorable?',function(){
    $.ajax({
      data : {_token:'{{ csrf_token() }}',txtFavorable: id},
      url: "{{ route('comentario.favorable.asistente') }}",
      type: "post",
      cache: false,
      mimeType:"multipart/form-data",
       beforeSend: function() {
                $('body').modalmanager('loading');
              },
          success:  function (response){
            $('body').modalmanager('loading');
                    if(isJson(response)){
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La información se guardo con exito !</p></strong>",function(){
                        var obj =  JSON.parse(response);
                        location.reload();
                      });

                    }else{
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
                    }
                  },
          error: function(jqXHR, textStatus, errorThrown) {
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: problemas al registrar la información!</p></strong>");
              console.log("Error en peticion AJAX!");
          }
         });
      },null).set('labels', {ok:'SI', cancel:'NO'}); */
}
 function NoSolicitud(){

   document.getElementById('txtComen').style.display = 'block';
   document.getElementById('btnArchivo').style.display = 'block';
   document.getElementById('btnFormulario').style.display = 'block';
   document.getElementById('btnPrincipales').style.display = 'none';
   $('#idTipo').val(3);
  /*alertify.confirm('Mensaje de sistema','¿Esta seguro que está solicitud no aplica?',function(){
    $.ajax({
      data : {_token:'{{ csrf_token() }}',txtmoAplica: id},
      url: "{{ route('comentario.noaplica.asistente') }}",
      type: "post",
      cache: false,
      mimeType:"multipart/form-data",
       beforeSend: function() {
                $('body').modalmanager('loading');
              },
          success:  function (response){
            $('body').modalmanager('loading');
                    if(isJson(response)){
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La información se guardo con exito !</p></strong>",function(){
                        var obj =  JSON.parse(response);
                        location.reload();
                      });

                    }else{
                      alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
                    }
                  },
          error: function(jqXHR, textStatus, errorThrown) {
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: problemas al registrar la información!</p></strong>");
              console.log("Error en peticion AJAX!");
          }
         });
      },null).set('labels', {ok:'SI', cancel:'NO'}); */
}

    $('#archivosAnexos').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Archivos guardados con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                   location.reload();

              });
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo registrar los archivos!</p></strong>");
              console.log("Error en peticion AJAX!");

          }
    });
    e.preventDefault(); //Prevent Default action.

    });

    $('#archivosUsuarioFinal').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Respuesta final de usuario enviada con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                   location.reload();

              });
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo registrar los archivos!</p></strong>");
              console.log("Error en peticion AJAX!");

          }
    });
    e.preventDefault(); //Prevent Default action.

    });


  $('#dt-anexos-arch').DataTable({
        filter:false,
        paging:   false,
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}"


        },
    });
    $('#dt-archivo-final').DataTable({
        filter:false,
        paging:   false,
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}"


        },
    });

     $('#FirmarRevision').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Datos enviados con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                   location.reload();

              });
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: Problemas para procesar la información</p></strong>");
              console.log("Error en peticion AJAX!");

          }
    });
    e.preventDefault(); //Prevent Default action.

    });

     function revisionFir(tipo){
      $('#tipoRevision').val(tipo);
      document.getElementById('aprobarFirmar').style.display='block';
      document.getElementById('buttnAfirmar').style.display='none';

       }

   $('#btnBuscarSolicitante').click(function(event) {

          $('#panelVisitante').modal('toggle');

            dtPersona = $('#dt-visitante').DataTable({

                              filter:true,
                              destroy: true,
                              serverSide: true,
                              pageLength: 6,
                              searching: false,
                              lengthChange: false,
                              ajax: {
                                url: "{{route('get.persona.natural')}}",
                                data: function(d)
                                {
                                  d.busqueda = $('#txtBuscar').val();
                                }
                              },
                              columns:[

                                      {data: 'nitNatural',name:'nitNatural'},
                                      {data: 'numeroDocumento',name:'numeroDocumento'},
                                      {data: 'nombres',name:'nombres'},
                                      {data: 'apellidos',name:'apellidos'},
                                      {data: 'detalle', name:'detalle',ordenable:false,searchable:false}
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"

                          },
                          "columnDefs": [ {
                         "width": "50%",
                         "searchable": false,
                         "orderable": false,
                         "targets": [3]
                          } ],
                          "order": [[ 2, 'asc' ]]
          });


    });

   function asignarVisitante(id,numDocu,email,tel1,tel2,conocido, nombres, apellidos){
  document.getElementById("notdui").value = id;
  document.getElementById("notapellidos").value= apellidos;
 document.getElementById("notnombres").value= nombres;
  document.getElementById("notcorreo").value = email;
 // document.getElementById("conocidoPN").value = conocido;
 //

  document.getElementById("notnit").value = numDocu;
  //document.getElementById("tel1PN").value = tel1;
 //  document.getElementById("tel2PN").value = tel2;
   //  document.getElementById('datosPN').style.display = 'block';

  $.get("{{route('pn.busqueda')}}?param="+id, function(data) {
            try{
                console.log(data.telefonosContacto.length);
                      if(data.telefonosContacto.length!=8){
                        var obj = JSON.parse(data.telefonosContacto);
                      //$('#nottel').val(obj[0]);
                     // $('#nottel').val(obj[1]);
                }
                    }
                    catch(e)
                    {console.log(e);}
     });
$('#panelVisitante').modal('hide');

}



</script>
@endsection
