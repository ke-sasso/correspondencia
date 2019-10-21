<?php
  $permisos = App\UserOptions::getAutUserOptions();
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
    Algo ha salido mal. {{ Session::get('msnError') }}
  </div>
@endif


<div class="panel-body">
      @include('denuncia.panel.verDetalle') 
      <br>
      @include('denuncia.panel.infoCiudadana')   
      <br>
      @if(in_array(471, $permisos, true))
          @include('denuncia.panel.verComentarios')
      @elseif(in_array(483, $permisos, true))
          @include('denuncia.panel.verComentariosAsistente')

      @else
           @include('denuncia.panel.comentariosSEIPS')
      @endif

      @if(in_array(490, $permisos, true))
       @include('denuncia.panel.comentariosSEIPS')
      @endif

     
<br>
 @if(in_array(490, $permisos, true))
 @include('denuncia.panel.info3')
 @if($info->idEstado==12 || $info->idEstado==14 || $info->idEstado==16 )
 <div align="center">
<a onclick="imprimirActa({{$info->idSolicitud}});" class="btn btn-success btn-perspective" ><i class="fa fa-check-square-o"></i>Imprimir Acta de Cierre</a>
</div>
@endif
 @if($info->idEstado==1)
 <div align="center">
<a onclick="enviarJunta({{$info->idSolicitud}});" class="btn btn-success btn-perspective" ><i class="fa fa-check-square-o"></i>Enviar a dirección ejecutiva</a>
</div>
@endif
@endif

@include('denuncia.panel.comentarioActaPDF')
@include('denuncia.panel.enviarDireccion')
</div>


  
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
 
    

    $("#fileAsesor" ).fileinput({
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
        $("#fileFinalizar" ).fileinput({
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
              var obj =  JSON.parse(response);
              var idSoli=obj.idSolicitud;
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>"+obj.msj+"</p></strong>",function(){
                   location.reload();                
              });
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo registrar la información general!</p></strong>");
              console.log("Error en peticion AJAX!");  

          }
    });
    e.preventDefault(); //Prevent Default action. 

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
   function finalizarCola(){
   document.getElementById('txtComen2').style.display = 'block';
   document.getElementById('btnArchivo2').style.display = 'block';
   document.getElementById('btnFormulario2').style.display = 'block';
   document.getElementById('btnPrincipales2').style.display = 'none';


}

   function ObservarSolicitud(){
   document.getElementById('txtComen').style.display = 'block';
   document.getElementById('btnArchivo').style.display = 'block';
   document.getElementById('btnFormulario').style.display = 'block';
   document.getElementById('btnPrincipales').style.display = 'none';
   $('#idTipo').val(1);

}
  function ObservarSEIPS(){
   document.getElementById('txtComen').style.display = 'block';
   document.getElementById('btnArchivo').style.display = 'block';
   document.getElementById('btnFormulario').style.display = 'block';
   document.getElementById('btnPrincipales').style.display = 'none';
   $('#idTipo').val(2);
 }
 function NoSolicitud(){

   document.getElementById('txtComen').style.display = 'block';
   document.getElementById('btnArchivo').style.display = 'block';
   document.getElementById('btnFormulario').style.display = 'block';
   document.getElementById('btnPrincipales').style.display = 'none';
   $('#idTipo').val(3);

}

 function favorableSolicitud(){
    document.getElementById('txtComen').style.display = 'block';
   document.getElementById('btnArchivo').style.display = 'block';
   document.getElementById('btnFormulario').style.display = 'block';
   document.getElementById('btnPrincipales').style.display = 'none';
   $('#idTipo').val(2);
 
}
 $('#comentarioAsesor').submit(function(e){

    var form = new FormData($("#comentarioAsesor")[0]);
    event.preventDefault();
    $('#comentarioAsesor :input').each(function(index, el) {
        $(this).parent().removeClass('has-error');
    });
    $.ajax({
      url: '{{route('guardar.comentario.denuncia')}}',
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

 $('#finalizarColaborador').submit(function(e){

    var form = new FormData($("#finalizarColaborador")[0]);
    event.preventDefault();
    $('#comentarioAsesor :input').each(function(index, el) {
        $(this).parent().removeClass('has-error');
    });
    $.ajax({
      url: '{{route('finalizar.colaborador')}}',
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

  function enviarJunta(idSolicitud){
     $('#idd').val(idSolicitud);
     $('#formEnviarDireccion').modal('toggle');
}

 $('#formenviarJunta').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La Denuncia se envio a dirección ejecutiva con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                   //  $('#formEnviarDireccion').modal('hide');
                    //  document.getElementById("formenviarJunta").reset();
                      location.reload(); 
                 
              });
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>¡Problemas al ingresar la información!</p></strong>");
              console.log("Error en peticion AJAX!");  

          }
    });
    e.preventDefault(); //Prevent Default action. 
  });

function imprimirActa(idSolicitud){
     $('#idd2').val(idSolicitud);
     $('#formComentarioActa').modal('toggle');
}
 $('#formComentarioPDFACTA').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡La información se envío  con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                  
                      location.reload(); 
                      window.open("{{route('pdf.boleta.acta',['idDenuncia'=>Crypt::encrypt($info->idSolicitud) ])}}");
                 
              });
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>¡Problemas al ingresar la información!</p></strong>");
              console.log("Error en peticion AJAX!");  

          }
    });
    e.preventDefault(); //Prevent Default action. 
  });
</script>
@endsection
