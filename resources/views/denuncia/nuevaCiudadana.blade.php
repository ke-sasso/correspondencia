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
<form id="infoGeneral" method="post" enctype="multipart/form-data" action="{{route('store.detalle.denuncia')}}">
	
			<div class="panel-body">

              
              <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha del evento reportado</b></div>
           <input type="text" class="form-control datepicker date_masking" id="fechaEvento" name="fechaEvento" value="" autocomplete="off" placeholder="dd-mm-yyyy. Ejemplo(31-12-2017)" >
                    </div>
                    </div>

   
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Medio de recepción</b></div>
                      <select class="form-control" name="medio" id="medio">
                        <option value="">Seleccione un medio de recepción...</option>
                        @if(!empty($medios))
                        @foreach($medios as $med)
                        <option value="{{trim($med->idMedio)}}">{{$med->nombreMedio}}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                    </div>




                   </div>
                </div>
                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Asunto</b></div>
                      <input type="text" class="form-control" id="asunto" name="asunto" autocomplete="off" value="">
                    </div>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                    <div class="input-group-addon"><b>MOTIVO</b></div>
                     <div class="form-group">
                        <textarea name="descripcion" id="descripcion" class="summernote-sm"></textarea>                                        
                        </div>
                    </div>
                    </div>
                </div>
    
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-9">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombre usuario</b></div>
                      <input type="text" class="form-control" id="usuario" name="usuario" value="" autocomplete="off" >
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Edad</b></div>
                     <input type="number" min="0" max="99" maxlength="2" class="form-control cel_masking" id="edad" name="edad" autocomplete="off" >
                    </div>
                    </div>


                    </div>
                </div>
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Profesión u Oficio</b></div>
                      <input type="text" class="form-control" id="profesion" name="profesion" autocomplete="off" value="">
                    </div>
                    </div>
                    </div>
                </div>
                   <div class="form-group">
                    <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Tipo documento:</b></div>
                               <select  class="form-control" id="tipo" name="tipo" onclick="habilitarInput();">
                                  <option value="DUI" selected>DUI</option>
                                  <option value="PASAPORTE">PASAPORTE</option>
                                  <option value="OTRO">OTRO</option>
                              </select>
                                </div>
                            </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N1">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento DUI</b></div>
                                    <input type="text" class="form-control dui_masking" id="numDocumentoP" name="numDocumentoP" autocomplete="off">
                                    </div>
                             </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N2" style="display: none;">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento</b></div>
                                    <input type="text" class="form-control" id="numDocumento2" maxlength="30" name="numDocumento2" autocomplete="off">
                                    </div>
                               </div>                                      
                        </div>                                                                 
                    </div>


                 <div class="panel panel-primary" id="cont-tit">
                      <div class="panel-heading">
                          <div class="right-content">
                          <button  type="button" class="btn btn-primary" id="btnBuscarEstablecimiento"><i class="fa fa-plus" ></i>Establecimiento</button>
                          </div>
                          <h3 class="panel-title">LISTA DE ESTABLECIMIENTOS</h3>
                        </div>
                        <div class="panel-body">
                          <div class="table-responsive">
                                 <table class="table table-hover" id="list-Establecimientos">
                                  <thead>
                                    <tr>
                                    <th>Nombre del establecimiento</th>
                                    <th>-</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                </div><!-- /.table-responsive -->
                        </div>
              </div>
                <div class="panel panel-primary" id="cont-productos">
                      <div class="panel-heading">
                          <div class="right-content">
                          <button  type="button" class="btn btn-primary" id="btnBuscarProducto"><i class="fa fa-plus" ></i>Productos</button>
                          </div>
                          <h3 class="panel-title">LISTA DE PRODUCTOS</h3>
                        </div>
                        <div class="panel-body">
                          <div class="table-responsive">
                                 <table class="table table-hover" id="list-Productos">
                                  <thead>
                                    <tr>
                                     <th>Nombre comercial</th>
                                     <th>Propietario</th>
                                     <th>Fecha vencimiento</th>
                                     <th>No.Lote</th>
                                    <th>-</th>
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
                    <div class="input-group-addon"><b>Ofrece de prueba:</b></div>
                     <div class="form-group">
                        <textarea name="prueba" id="prueba" class="summernote-sm"></textarea>                                        
                        </div>
                    </div>
                    </div>
                </div>
                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                    <div class="input-group-addon"><b>Por lo antes mencionado PIDE:</b></div>
                     <div class="form-group">
                        <textarea name="pide" id="pide" class="summernote-sm"></textarea>                                        
                        </div>
                    </div>
                    </div>
                </div>

                    <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Señalo para oír notificaciones:  </b></div>
                      <input type="text" class="form-control" id="aviso" name="aviso" value="" autocomplete="off" >
                    </div>
                    </div>
                     
                    </div>
                    </div>

                     <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Teléfonos a notificar</b></div>
                     <input type="text" class="form-control cel_masking" id="tel1" name="tel1" autocomplete="off" >
                     <input type="text" class="form-control cel_masking" id="tel2" name="tel2" autocomplete="off" >
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Correo</b></div>
                       <input type="email" class="form-control" id="correo" name="correo" value="" autocomplete="off" >
                    </div>
                    </div>
                    </div>
                    </div>
                       <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                    <div class="input-group-addon"><b>Observaciones</b></div>
                     <div class="form-group">
                        <textarea name="observacion" id="observacion" class="summernote-sm"></textarea>                                        
                        </div>
                    </div>
                    </div>
                </div>
                   
              
               <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group">
                       <div class="input-group-addon"><b>Documentos...s</b></div>
                       <input id="fileA" name="fileA[]"  type="file" multiple class="file-loading"> 
                      
                       </div>
                     <div id="errorBlockNew" class="help-block"></div>
                    </div>
                    </div>
                </div>
                

             
                

				<div class="panel-footer text-center">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="tipoForm" value="2" />
                <button type="submit" class="btn btn-primary btn-perspective">GUARDAR <i class="fa fa-check"></i></button>
                
				</div>
			</div>
			</form>

	@include('denuncia.panel.busqueda')
@include('denuncia.panel.nuevoEstablecimiento')
@include('denuncia.panel.nuevoProducto')

@endsection

{{-- JS ESPECIFICOS --}}
@section('js')

{!! Html::script('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/fileinput.min.js') !!}
{!! Html::script('plugins/bootstrap-fileinput/js/fileinput_locale_es.js') !!}
 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 
{{-- Bootstrap Modal --}}

<script>
var dataAddEstNo = [];
var dataAddEstSi = [];
var dataAddProNo = [];
var dataAddProSi = [];
var data = [];
var dtEstNo;
var dtEstSi;
var dtProNo;
var dtProSi;
function otro(){
  alert("si");
}
$(document).ready(function(){

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
                     // window.location = "{{route('lista.solicitud.denuncia')}}";  
                      window.open("{{route('pdf.boleta.denuncia')}}");
                                   
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

 
   
   $('#btnBuscarEstablecimiento').click(function(event){
      dataAddEstNo.length=0;
      dataAddEstSi.length=0;
       
             dtEstNo=$('#dt-EstNo').DataTable({
                              processing: true,
                              filter:false,
                              destroy: true,
                              pageLength: 5,
                              serverSide: true,
                              ajax: {
                                url: "{{route('get.establecimientos.no')}}",
                        data: function (d) {
                        d.buscar= $('#buscar').val();
                                 }

                              },
                              columns:[ 
                                      {searchable: false,
                              "mData": null,
                              "bSortable": false,
                              "mRender": function (data,type,full) { 
                                if(data.alerta==1){
                                    return '';
                                }
                                else{
                                    if(data.base==1){
                                       return data.id;
                                    }else{
                                       if(data.noregistro==='' || data.noregistro=='null'){
                                        return 'N/A';
                                       }else{
                                       return data.noregistro;
                                       }
                                    }
                              }
                              }}, 
                                     {data: 'nom',name:'nom'},   
                                     {data: 'direc',name:'direc'}, 
                                     {searchable: false,
                              "mData": null,
                              "bSortable": false,
                              "mRender": function (data,type,full) { 
                                if(data.alerta==1){
                                    return '';
                                }
                                else{
                                    if(data.estado=="A"){
                                       return '<span class="label label-success">ACTIVO</span>';
                                    }else if(data.estado=="I"){
                                      return '<span class="label label-danger">INACTIVO</span>';
                                    }else if(data.estado=="E"){
                                      return '<span class="label label-danger">ELIMINADO</span>';
                                    }else if(data.estado=="C"){
                                      return '<span class="label label-success">CANCELADO</span>';
                                    }else{
                                    return '<span class="label label-primary">N/A</span>';
                                    }
                              }
                              }},                         
                              {searchable: false,
                              "mData": null,
                              "bSortable": false,
                              "mRender": function (data,type,full) { 
                                if(data.alerta==1){
                                    return '';
                                }
                                else{
                                  if(data.base==1){
                              return '<input type=checkbox value="test your look ;)" name="idrepres"  data-idest="'+data.id+'" data-nomest="'+data.nom+'" data-estadoest="'+data.estado+'" data-direcesta="'+data.direc+'" class="ckb-check1">'
                                }else{
                                return '<input type=checkbox value="test your look ;)" name="idrepres"  data-idregistro="'+data.id+'" data-nomestsi="'+data.nom+'"  class="ckb-check2">'
                                }
                                }
                              }
                              }
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                              
                          }, 
                           "columnDefs": [ {
                         "searchable": true,
                         "orderable": false,
                         "targets": [1]
                          } ],   
                          "order": [[ 0, 'asc' ]]                           
          });

          $('#panelEstablecimientos').modal('toggle');

    });
    $('#search-form').on('submit', function(e) {
      dtEstNo.draw();
        e.preventDefault();
        dtEstNo.rows().remove();
   // dtEstNo.ajax.reload();
    });
    
 

   $('#btnBuscarProducto').click(function(event){
      dataAddProNo.length=0;
      dataAddProSi.length=0;
       
             dtProNo=$('#dt-ProNo').DataTable({
                              processing: true,
                              filter:false,
                              destroy: true,
                              pageLength: 5,
                              serverSide: true,
                              ajax: {
                                url: "{{route('get.productos.no')}}",
                             data: function (d) {
                              d.buscar= $('#buscar2').val();
                                 }

                              },
                              columns:[ 
                                     {searchable: false,
                              "mData": null,
                              "bSortable": false,
                              "mRender": function (data,type,full) { 
                                if(data.alerta==1){
                                    return '';
                                }
                                else{
                                    if(data.base==1){
                                       return data.id;
                                    }else{
                                       if(data.noregistro==='' || data.noregistro=='null'){
                                        return 'N/A';
                                       }else{
                                       return data.noregistro;
                                       }
                                    }
                              }
                              }},  
                                     {data: 'nom',name:'nom'},   
                                     {data: 'prop',name:'prop'},
                                     {data: 'fecha',name:'fecha'},
                                     {data: 'lote',name:'lote'},
                                      {searchable: false,
                              "mData": null,
                              "bSortable": false,
                              "mRender": function (data,type,full) { 
                                if(data.alerta==1){
                                    return '';
                                }
                                else{
                                    if(data.estado=="A"){
                                       return '<span class="label label-success">ACTIVO</span>';
                                    }else if(data.estado=="I"){
                                      return '<span class="label label-danger">INACTIVO</span>';
                                    }else if(data.estado=="E"){
                                      return '<span class="label label-danger">ELIMINADO</span>';
                                    }else if(data.estado=="C"){
                                      return '<span class="label label-success">CANCELADO</span>';
                                    }else{
                                    return '<span class="label label-primary">N/A</span>';
                                    }
                              }
                              }},                      
                              {searchable: false,
                              "mData": null,
                              "bSortable": false,
                              "mRender": function (data,type,full) { 
                                if(data.alerta==1){
                                    return '';
                                }
                                else{
                                  if(data.base==1){
                           return '<input type=checkbox value="test your look ;)" name="idrepres" data-idprod="'+data.id+'" data-nompro="'+data.nom+'"   data-propietario="'+data.prop+'" data-estadopro="'+data.estado+'" class="ckb-check3">'
                            }else{
                              return '<input type=checkbox value="test your look ;)" name="idrepres"  data-idregistro="'+data.id+'" data-nomprosi="'+data.nom+'" data-fechaven="'+data.fecha+'" data-nolote="'+data.lote+'"  data-titular="'+data.prop+'" class="ckb-check4">'
                            }
                                }
                              }
                              }
                                  ],
                             language: {
                              processing: '<div class=\"dlgwait\"></div>',
                              "url": "{{ asset('plugins/datatable/lang/es.json') }}"
                            
                              
                          }, 
                           "columnDefs": [ {
                        
                         "searchable": true,
                         "orderable": false,
                         "targets": [1]
                          } ],   
                          "order": [[ 0, 'asc' ]]                           
          });

          $('#panelProducto').modal('toggle');

    });
 $('#search-form2').on('submit', function(e) {
        dtProNo.draw();
        e.preventDefault();
        dtProNo.rows().remove();
   // dtEstNo.ajax.reload();
    });
    
});
  
  function habilitarInput(){
  tipo = document.getElementById("tipo").value;
 //alert(tipo);

  if(tipo!='DUI'){
    
    document.getElementById("numDocumentoP").value='';
    document.getElementById("numDocumento2").value='';
    document.getElementById('N1').style.display='none';
     document.getElementById('N2').style.display='block';
  }else{
    document.getElementById("numDocumentoP").value='';
     document.getElementById("numDocumento2").value='';
     document.getElementById('N2').style.display='none';
     document.getElementById('N1').style.display='block';
  }

  }
  function isJson(str) {
      try {
          JSON.parse(str);
      } catch (e) {
          return false;
      }
      return true;
  }
$(document).on('click','.ckb-check1',function(e) {
      if (this.checked) {
        dataAddEstNo.push([$(this).data("idest"),$(this).data("nomest"),$(this).data("estadoest"),$(this).data("direcesta")]);
        console.info(dataAddEstNo);

      } else {
        
      }

});

$(document).on('click','.ckb-check2',function(e) {
   if (this.checked) {
       
        dataAddEstSi.push([$(this).data("idregistro"),$(this).data("nomestsi")]);
        console.info(dataAddEstSi);
      } else {
        
      }

});
$(document).on('click','.ckb-check3',function(e) {
   if (this.checked) {
       
    dataAddProNo.push([$(this).data("idprod"),$(this).data("nompro"),$(this).data("propietario"),$(this).data("estadopro")]);
        console.info(dataAddProNo);
      } else {
        
      }

});

$(document).on('click','.ckb-check4',function(e) {
   if (this.checked) {
       dataAddProSi.push([$(this).data("idregistro"),$(this).data("nomprosi"),$(this).data("fechaven"),$(this).data("nolote"),$(this).data("titular")]);
        console.info(dataAddProSi);
      } else {
        
      }
 
});

 function enviarEstablecimientos(){

   if(dataAddEstNo.length!=0){
 $('#panelEstablecimientos').modal('hide');
  for (var i =0; i< dataAddEstNo.length;i++) {
    $('#list-Establecimientos').append('<tr><td><input type="hidden" name="estaNo[]" value="'+dataAddEstNo[i][0]+'"><input type="hidden" name="nombestable'+dataAddEstNo[i][0]+'" value="'+dataAddEstNo[i][1]+'"><input type="hidden" name="estadoest'+dataAddEstNo[i][0]+'" value="'+dataAddEstNo[i][2]+'"><input type="hidden" name="direest'+dataAddEstNo[i][0]+'" value="'+dataAddEstNo[i][3]+'">'+dataAddEstNo[i][1]+'</td><td><a class="btn btn-danger btn-perspective btnEliminar"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td></tr>');
      
      } 

    }

   if(dataAddEstSi.length!=0){
  $('#panelEstablecimientos').modal('hide');
  //document.getElementById('cont-tit').style.display = 'block';
    for (var i =0; i< dataAddEstSi.length;i++) {
    $('#list-Establecimientos').append('<tr><td><input type="hidden" name="estaSi[]" value="'+dataAddEstSi[i][0]+'" >'+dataAddEstSi[i][1]+'</td><td><a class="btn btn-danger btn-perspective btnEliminar"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td></tr>');
      
      } 

    }
  
  }

  function enviarProductos(){

   if(dataAddProNo.length!=0){
   $('#panelProducto').modal('hide');
  for (var i =0; i< dataAddProNo.length;i++){
    $('#list-Productos').append('<tr><td><input type="hidden" name="proNo[]" value="'+dataAddProNo[i][0]+'" ><input type="hidden" name="nombre'+dataAddProNo[i][0]+'"  id="nombre'+dataAddProNo[i][0]+'" value="'+dataAddProNo[i][1]+'" ><input type="hidden" name="estadopro'+dataAddProNo[i][0]+'"  id="estadopro'+dataAddProNo[i][0]+'" value="'+dataAddProNo[i][3]+'" >'+dataAddProNo[i][1]+'</td><td><input type="hidden" name="propietario'+dataAddProNo[i][0]+'" id="propietario'+dataAddProNo[i][0]+'" value="'+dataAddProNo[i][2]+'" >'+dataAddProNo[i][2]+'</td><td><input type="date" class="form-control datepicker" name="fecha'+dataAddProNo[i][0]+'" id="fecha'+dataAddProNo[i][0]+'"><td><input type="text" class="form-control" name="lote'+dataAddProNo[i][0]+'" id="lote'+dataAddProNo[i][0]+'"></td><td><a class="btn btn-danger btn-perspective btnEliminar"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td></tr>');
      
      } 

    }

   if(dataAddProSi.length!=0){
  $('#panelProducto').modal('hide');
  //document.getElementById('cont-tit').style.display = 'block';
    for (var i =0; i< dataAddProSi.length;i++) {
    $('#list-Productos').append('<tr><td><input type="hidden" name="proSi[]" value="'+dataAddProSi[i][0]+'" >'+dataAddProSi[i][1]+'</td><td>'+dataAddProSi[i][4]+'</td><td>'+dataAddProSi[i][2]+'<td>'+dataAddProSi[i][3]+'</td></td><td><a class="btn btn-danger btn-perspective btnEliminar"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td></tr>');
      
      } 

    }
  
  }

  $("#list-Establecimientos").on('click', '.btnEliminar', function () {
      $(this).closest('tr').remove();
  
  })
  $("#list-Productos").on('click', '.btnEliminar', function () {
      $(this).closest('tr').remove();
  
  })

  function nuevoEstablecimiento(){
    
   // $('#panelEstablecimientos').modal('hide');
     $('#formNuevoEstablecimiento').modal('toggle');
   //  $('#tipoPost').val(1);
  }

  function nuevoProducto(){
     $('#formNuevoProductos').modal('toggle');
     //$('#tipoPost').val(2);
  }

 $('#nuevoEstablecimiento').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Establecimiento registrado con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                     $('#formNuevoEstablecimiento').modal('hide');
                      dtEstNo.ajax.reload();
                      $('#nombreComercial').val("");
                      $('#propietario').val("");
                      $('#direccion').val("");
                      $('#observacion').val("");
                      document.getElementById("nuevoEstablecimiento").reset();
                 
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

  $('#nuevoProducto').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Producto registrado con exito!</p></strong>",function(){
                var obj =  JSON.parse(response);
                     $('#formNuevoProductos').modal('hide');
                      dtProNo.ajax.reload();
                      $('#nombreComercial').val("");
                      $('#propietario').val("");
                      $('#fecha').val("");
                      $('#nolote').val("");
                      $('#observacion').val("");
                      document.getElementById("nuevoProducto").reset();
                 
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
