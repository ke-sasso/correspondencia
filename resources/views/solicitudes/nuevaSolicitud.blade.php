
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
                         <input type="text" class="form-control" id="nitSolicitante" name="nitSolicitante" autocomplete="off" readonly>
                      <span class="input-group-btn">
	                  <button type="button" class="btn btn-primary" id="btnBuscarSolicitante"><i class="fa fa-search" ></i></button></span>  
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Correo a notificar</b></div>
                         <input type="email" class="form-control" id="correoNotificar" name="correoNotificar" autocomplete="off">
                    </div>
                    </div>
                    </div>
                </div>
           
                <div class="form-group" id="datosPN" style="display: none;">
                     <div class="row">
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombres</b></div>
                      <input type="text" class="form-control" id="nombresSolicitante" name="nombresSolicitante" autocomplete="off" required="">
                    </div>
                    </div>
                       <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Apellidos</b></div>
                      <input type="text" class="form-control" id="apellidosSolicitante" name="apellidosSolicitante" autocomplete="off" required="">
                    </div>
                    </div>
                    
                    </div>
                       <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Correo:</b></div>
                        <input type="email" class="form-control" id="correoPN" name="correoPN" autocomplete="off">
                    </div>
                    </div>
                                        <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b># Documento</b></div>
                         <input type="text" class="form-control" id="documento" name="documento" autocomplete="off" disabled=""> 
                    </div>
                    </div>
                      
                    </div>
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Teléfono 1:</b></div>
                        <input type="text" class="form-control cel_masking" id="tel1PN" name="tel1PN" autocomplete="off">
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Teléfono 2:</b></div>
                        <input type="text" class="form-control cel_masking" id="tel2PN" name="tel2PN" autocomplete="off">
                    </div>
                    </div>    
                    </div>
                </div>
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Asunto</b></div>
                      <input type="text" class="form-control" id="asunto" name="asunto" autocomplete="off" >
                    </div>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Descripci&oacute;n</b></div>
                      <textarea class="form-control"  rows="2" id="descripcion" name="descripcion" autocomplete="off"></textarea>
                    </div>
                    </div>
                    </div>
                </div>
                   

                   <div class="panel panel-primary" id="cont-tit">
                      <div class="panel-heading">
                          <div class="right-content">
                          <button  type="button" class="btn btn-primary" id="btnBuscarTitular"><i class="fa fa-plus" ></i>Titulares</button>
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
                                      </tbody>
                                </table>
                                </div><!-- /.table-responsive -->
                        </div>
                      </div>


                                      <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group">
                       <div class="input-group-addon"><b>Documentos...</b></div>
                       <input id="fileA" name="fileA[]" required type="file" multiple class="file-loading"> 
                      
                       </div>
                     <div id="errorBlockNew" class="help-block"></div>
                    </div>
                    </div>
                </div>
                

				<div class="panel-footer text-center">
				 <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-primary btn-perspective">GUARDAR <i class="fa fa-check"></i></button>
                
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

$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
      dropZoneEnabled: true,
       layoutTemplates: {main2: '{preview} {remove} {browse}'},
       msgErrorClass: 'alert alert-block alert-danger',
    });



      $('#btnBuscarSolicitante').click(function(event) {

          $('#panelVisitante').modal('toggle');


    });
  
    $('#btnBuscarPer').on('click', function(event) {
      event.preventDefault();
      dtPersona.draw();
    });


    $('#btnBuscarTitular').click(function(event){
      dataAddProds.length=0;
       
             dtVisitante= $('#dt-titular').DataTable({
                               filter:true,
                              destroy: true,
                              pageLength: 5,
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
                  url: '{{route('guardar.info.solicitud')}}',
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
 // document.getElementById("tipoDoc").length=0;
  //document.getElementById("departamento").length=0;
  //document.getElementById("municipio").length=0;
    
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


function asignarVisitante(id,numDocu,email,tel1,tel2,conocido, nombres, apellidos){
  document.getElementById("nitSolicitante").value = id;
  document.getElementById("apellidosSolicitante").value= apellidos;
 document.getElementById("nombresSolicitante").value= nombres;
  document.getElementById("correoPN").value = email;
 // document.getElementById("conocidoPN").value = conocido;
  document.getElementById("documento").value = numDocu;
  //document.getElementById("tel1PN").value = tel1;
 //  document.getElementById("tel2PN").value = tel2;
     document.getElementById('datosPN').style.display = 'block';

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

  function modalNuevoTitular(){
         $('#formNuevoTitular').modal('toggle');

};
  

</script>
@endsection
