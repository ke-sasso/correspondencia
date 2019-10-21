
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

	
		<form id="infoGeneral" method="post" enctype="multipart/form-data">
	
				<div class="panel-body">
        
				
			    <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Persona que presenta</b></div>
                        <input type="text" class="form-control" id="nombreSolicitante" name="nombreSolicitante" autocomplete="off" value="{{$persona->nitNatural}}" readonly>
                      <input type="hidden" class="form-control" id="nitSolicitante" value="{{$info->nitSolicitante}}" name="nitSolicitante" autocomplete="off" readonly>
                      <span class="input-group-btn">
	                  <button type="button" class="btn btn-primary" id="btnBuscarSolicitante"><i class="fa fa-search" ></i></button></span>  
                    </div>
                    </div>
                     
                </div>
                  <div class="form-group">
                     <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombres</b></div>
                      <input type="text" class="form-control" id="nombresSolicitante" name="nombresSolicitante" autocomplete="off" value="{{$persona->nombres}}" >
                    </div>
                    </div>
                       <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Apellidos</b></div>
                      <input type="text" value="{{$persona->apellidos}}" class="form-control" id="apellidosSolicitante" name="apellidosSolicitante" autocomplete="off">
                    </div>
                    </div>
                    
                    </div>
                       <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Correo:</b></div>
                        <input type="email" class="form-control" id="correoPN" value="{{$persona->emailsContacto}}" name="correoPN" autocomplete="off">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b># Documento</b></div>
                        <input type="text" class="form-control" value="{{$persona->numeroDocumento}}" id="documento" name="documento" autocomplete="off" disabled="">
                    </div>
                    </div>   
                    </div>
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Teléfono 1:</b></div>
                        <input type="text" class="form-control cel_masking" minlength="9" value="{{json_decode($persona->telefonosContacto)[0]}}" id="tel1PN" name="tel1PN" autocomplete="off">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Teléfono 2:</b></div>
                        <input type="text" class="form-control cel_masking" id="tel2PN" minlength="9" value="{{json_decode($persona->telefonosContacto)[1]}}" name="tel2PN" autocomplete="off">
                    </div>
                    </div>    
                    </div>
                    
                </div>
               

                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Asunto</b></div>
                      <input type="text" class="form-control" required id="asunto" name="asunto" autocomplete="off" value="{{$info->asunto}}" >
                    </div>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Descripci&oacute;n</b></div>
                      <textarea class="form-control"  required rows="2" id="descripcion" name="descripcion" autocomplete="off">{{$info->descripcion}}</textarea>
                    </div>
                    </div>
                    </div>
                </div>

                 <div class="panel panel-primary" id="cont-tit">
                      <div class="panel-heading">
                          <div class="right-content">
                        <button type="button" class="btn btn-primary" id="btnBuscarTitular"><i class="fa fa-plus" ></i>Nuevo titular</button>
                          </div>
                          <h3 class="panel-title">LISTA DE TITULARES</h3>
                        </div>
                        <div class="panel-body">
                          <div class="table-responsive">
                                 <table class="table table-hover" id="dt-Tit">
                                  <thead>
                                    <tr>
                                    <th>Nombre titular</th>
                                    <th>- </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($titulares as $tit)
                                    <tr>
                                       <td>{{$tit->nombreTitular}}</td>
                                       <td><a class="btn btn-danger btn-perspective" onclick="eliminarTitular({{$tit->idSolicitudTit}},{{$info->idSolicitud}});"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                                    </tr>
                                    @endforeach
                                      </tbody>
                                </table>
                                </div><!-- /.table-responsive -->
                        </div>
                      </div>
                      <br>
                      <div class="table-responsive">
                          <table class="table table-th-block table-success table-hover" id="dt-solicitudes" style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Tipo de documento</th>
                              <th>Nombre del archivo</th>
                              <th>-</th>
                            </tr>
                          </thead>
                          <tbody>
                                @foreach($archivos as $ar)
                            <tr>
                               <td> 
                              @if(trim($ar->tipoArchivo)==='application/pdf')
                               <center><i class="fa fa-file-text" style="font-size:25px;"></i></center>
                              @elseif(trim($ar->tipoArchivo)==='image/jpeg' || trim($ar->tipoArchivo)==='image/png' || trim($ar->tipoArchivo)==='image/gif')
                               <center><i class="fa fa-picture-o" style="font-size:25px;"></i></center>
                               @else
                               <center><i class="fa-file-text" style="font-size:25px;"></i></center>
                              @endif
                              </td>  
                              <td>{{$ar->nombreArchivo}}</td>
                              <td>
                                  <a href="{{route('ver.documento',['urlDocumento' => Crypt::encrypt($ar->urlArchivo),'tipoArchivo'=>  Crypt::encrypt($ar->tipoArchivo)])}}" class="btn btn-xs btn btn-primary btn-perspective" target="_blank"><i class="fa  fa-location-arrow"></i> Ver documento 
                                    </a>&nbsp;&nbsp;<a class="btn btn-xs btn btn-primary btn-danger btn-perspective" onclick="eliminarArchivo({{$ar->idAdjunto}},{{$info->idSolicitud}});"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                </td>
                                 </tr>
                                @endforeach
                          </tbody>
                          </table>
                          </div><!-- /.table-responsive -->
                          <br>
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group">
                       <div class="input-group-addon"><b>Documentos...</b></div>
                       <input id="fileA" name="fileA[]" type="file" multiple class="file-loading"> 
                      
                       </div>
                     <div id="errorBlockNew" class="help-block"></div>
                    </div>
                    </div>
                </div>
                

				<div class="panel-footer text-center">
         <input type="hidden" name="idSolicitudEditar" id="idSolicitudEditar" value="{{$info->idSolicitud}}" />
				 <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-primary btn-perspective">EDITAR<i class="fa fa-check"></i></button>
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
var dataAddProds = [];
var data = [];
var dtVisitante;
var dtPersona;
$(document).ready(function(){

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
        
var listTt=  $('#dt-Listitulares').DataTable({
      "language": {
            "url": "{{ asset('plugins/datatable/lang/es.json') }}"
        }

    });
         
      $("#departamento").on('change',function(){
      $.ajax({
        data: {_token:'{{ csrf_token() }}',deparamento:this.value},
        url: "{{ url('/solicitud/getMunicipiosAjax') }}",
        type: 'post',
        beforeSend: function() {
        $("#municipio").prop("disabled",true);
      },
            success:  function (response){
              $("#municipio").html(response);
              $("#municipio").prop("disabled",false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
              $("#municipio").prop("disabled",false);
                console.log("Error en peticion AJAX!");  
            }
      });
        });

		$("#fileA" ).fileinput({
      language: "es",
      overwriteInitial: true,
      browseLabel: 'Subir archivos',
      removeLabel: '',
	    browseIcon: '<i class="fa fa-folder-open"></i>',
      removeIcon: '<i class="fa fa-times"></i>',
      removeTitle: 'Cancelar o resetear cambios',
      showUpload: false,
      showRemove: true,
      MaxFileCount : 10,
      dropZoneEnabled: true,
       layoutTemplates: {main2: '{preview} {remove} {browse}'},
       msgErrorClass: 'alert alert-block alert-danger',
    });



      $('#btnBuscarSolicitante').click(function(event) {
        
            dtPersona = $('#dt-visitante').DataTable({
                             
                              filter:true,
                              destroy: true,
                              serverSide: false,
                              pageLength: 6,
                              ajax: {
                                url: "{{route('get.persona.natural')}}"

                              },
                              columns:[                        
                                    
                                      {data: 'nitNatural',name:'nitNatural'}, 
                                      {data: 'numeroDocumento',name:'numeroDocumento'},  
                                      {data: 'nombres',name:'nombres'},  
                                      {data: 'apellidos',name:'apellidos'},                                  
                                      {data: 'detalle', name:'detalle'}
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          },  
                          "columnDefs": [ {
                         "width": "10%",
                         "searchable": false,
                         "orderable": false,
                         "targets": [3]
                          } ],   
                          "order": [[ 0, 'desc' ]]                       
          });

          $('#panelVisitante').modal('toggle');

    });
    $('#btnBuscarTitular').click(function(event) {
      dataAddProds.length=0;
       
             dtVisitante= $('#dt-titular').DataTable({
                               filter:true,
                              destroy: true,
                              pageLength: 6,
                              ajax: {
                                url: "{{route('get.titular')}}"

                              },
                              columns:[ 
                                     {data: 'nombreTitular',name:'nombreTitular'},                        
                                      {searchable: false,
                              "mData": null,
                              "bSortable": false,
                              "mRender": function (data,type,full) { 
                                if(data.alerta==1){
                                    return '';
                                }
                                else{

                                  return '<input type=checkbox value="test your look ;)" name="idrepres"  data-idtit="'+data.idTitular+'" data-nomtit="'+data.nombreTitular+'"  class="ckb-check">'
                                }
                              }
                            }
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          }, 
                           "columnDefs": [ {
                         "width": "10%",
                         "searchable": false,
                         "orderable": false,
                         "targets": [1]
                          } ],   
                          "order": [[ 0, 'desc' ]]                           
          });

          $('#panelTitular').modal('toggle');

    });

   

     $('#infoGeneral').submit(function(e){
                var form = new FormData($("#infoGeneral")[0]);
                event.preventDefault();
                $('#infoGeneral :input').each(function(index, el) {
                    $(this).parent().removeClass('has-error');
                });
               $.ajax({
                  url: '{{route('editar.info.solicitud')}}',
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
                      window.open("{{route('pdf.boleta.presentacion')}}");
                       });
                      
                    }else{
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
                }else{
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
   $('#nuevoTitular').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Titular registrado de forma exitosa!</p></strong>",function(){
                var obj =  JSON.parse(response);
                     $('#formNuevoTitular').modal('hide');
                      dtVisitante.ajax.reload();
                      $('#nombreTitular').val("");
                      $('#telefono1').val("");
                      $('#telefono2').val("");
                      $('#emailTitular').val("");
                      document.getElementById("nuevoTitular").reset();
                 
              });
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo registrar la información general de esta persona!</p></strong>");
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

  $(document).on('click','.ckb-check',function(e) {
   if (this.checked) {
        dataAddProds.push([$(this).data("idtit"),$(this).data("nomtit")]);
        console.info(dataAddProds);

      } else {
        
      }
    data=dataAddProds;
});
});

function modalNuevoVisitante(){
//  document.getElementById("tipoDoc").length=0;
//  document.getElementById("departamento").length=0;
 // document.getElementById("municipio").length=0;
    
          $.get("{{route('get.tipoDocumento')}}", function(data) {
            try{
              $.each(data, function(i, value) {
                    if(value.nombreTipoDocumento == 'DUI'){
                         $('#tipoDoc').append('<option selected value="'+value.idTipoDocumento+'">'+value.nombreTipoDocumento+'</option>');
                         }else{
                         $('#tipoDoc').append('<option value="'+value.idTipoDocumento+'">'+value.nombreTipoDocumento+'</option>');
                          }
                    });

                        }
                    catch(e)
                    {console.log(e);}
             });

            $.get("{{route('get.tipo.tratamiento')}}", function(data) {
            try{
              $.each(data, function(i, value) {

                $('#tratamiento').append('<option value="'+value.idTipoTratamiento+'">'+value.nombreTratamiento+'</option>');
     
                    });

               }catch(e){console.log(e);}
             });
         $('#formNuevoVisitante').modal('toggle');

          $('#nuevoVisitante').submit(function(e){
                var form = new FormData($("#nuevoVisitante")[0]);
                event.preventDefault();
                $('#nuevoVisitante :input').each(function(index, el) {
                    $(this).parent().removeClass('has-error');
                });
               $.ajax({
                  url: '{{route('guardar.nueva.personaNatural')}}',
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
                      $('#formNuevoVisitante').modal('hide');
                      dtPersona.ajax.reload();
                      $('#nit').val("");
                      $('#fechaNacimiento').val("");
                      $('#conocido').val("");
                        document.getElementById("tipoDoc").length=0;
                        document.getElementById("tratamiento").length=0;
                         document.getElementById("sexo").length=0;
                      $('#numDoc1').val("");
                       $('#direccion').val("");
                      $('#nombres').val("");
                      $('#apellidos').val("");
                       $('#numDoc2').val("");
                      $('#tel1').val("");
                      $('#tel2').val("");
                      $('#email').val("");
                       alertify.alert('Mensaje del Sistema',response.message);
                    }else{
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
                }else{
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
};

function modalNuevoTitular(){
         $('#formNuevoTitular').modal('toggle');
};
function asignarVisitante(id,numDocu,email,tel1,tel2,conocido, nombres, apellidos){
    document.getElementById("nombreSolicitante").value = id;
  document.getElementById("nitSolicitante").value = id;
  document.getElementById("apellidosSolicitante").value= apellidos;
 document.getElementById("nombresSolicitante").value= nombres;
  document.getElementById("correoPN").value = email;
 // document.getElementById("conocidoPN").value = conocido;
 // document.getElementById("tel1PN").value = tel1;
 //  document.getElementById("tel2PN").value = tel2;

  $.get("{{route('pn.busqueda')}}?param="+id, function(data) {
            try{
                console.log(data.telefonosContacto.length);
                      if(data.telefonosContacto.length!=8){
                        var obj = JSON.parse(data.telefonosContacto);
                      $('#tel1PN').val(obj[0]);
                      $('#tel2PN').val(obj[1]);
                }
                    }
                    catch(e)
                    {console.log(e);}
     });

$('#panelVisitante').modal('hide');
}

$("#dt-Tit").on('click', '.btnEliminar', function () {
      $(this).closest('tr').remove();


  
  })

  function enviarTitular(){

  if(dataAddProds.length==0){
   document.getElementById('cont-tit').style.display = 'none';
  }else{
  $('#panelTitular').modal('hide');
  document.getElementById('cont-tit').style.display = 'block';


  for (var i =0; i< dataAddProds.length;i++) {
    $('#dt-Tit').append('<tr><td><input type="hidden" name="titular[]" value="'+dataAddProds[i][0]+'" >'+dataAddProds[i][1]+'</td><td><a class="btn btn-danger btn-perspective btnEliminar"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td></tr>');
      
      }  
    }
  
  }

 function habilitarInput(){
  tipo = document.getElementById("tipoDoc").value;
 // alert(tipo);

  if(tipo!=1){
    
    document.getElementById("numDoc1").value='';
    document.getElementById("numDoc2").value='';
    document.getElementById('N1').style.display='none';
     document.getElementById('N2').style.display='block';
  }else{
    document.getElementById("numDoc1").value='';
     document.getElementById("numDoc2").value='';
     document.getElementById('N2').style.display='none';
     document.getElementById('N1').style.display='block';
  }

  }
  
function eliminarTitular(id,idSoli){
  alertify.confirm('Mensaje de sistema','¿Esta seguro que desea elimiar el titular?',function(){
    $.ajax({
      data : {_token:'{{ csrf_token() }}',txtTitular: id,idSolicitud:idSoli},
      url: "{{ route('eliminar.titular') }}",
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
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo eliminar el titular!</p></strong>");
              console.log("Error en peticion AJAX!");  
          }
         });
      },null).set('labels', {ok:'SI', cancel:'NO'}); 
}
function eliminarArchivo(id,idSoli){
  alertify.confirm('Mensaje de sistema','¿Esta seguro que desea elimiar este archivo?',function(){
    $.ajax({
      data : {_token:'{{ csrf_token() }}',txtArchivo: id,idSolicitud:idSoli},
      url: "{{ route('eliminar.archivo') }}",
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
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo eliminar el archivo!</p></strong>");
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
</script>
@endsection
