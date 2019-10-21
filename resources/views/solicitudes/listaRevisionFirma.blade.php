@extends('master')

@section('css')
<style type="text/css">
	td.details-control {
    	background: url("{{ asset('/plugins/datatable/images/details_open.png') }}") no-repeat right;
    	cursor: pointer;
	}
	tr.shown td.details-control {
    	background: url("{{ asset('/plugins/datatable/images/details_close.png') }}") no-repeat right;
	}
</style>
@endsection

@section('contenido')
{{-- MENSAJE DE EXITO --}}
@if(Session::has('msnExito'))
	<div class="alert alert-success square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
		<strong>Enhorabuena!</strong>
		{{ Session::get('msnExito') }}
	</div>
@endif
{{-- MENSAJE DE ERROR --}}
@if(Session::has('msnError'))
	<div class="alert alert-danger square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
		<strong>Auchh!</strong>
		Algo ha salido mal.	{{ Session::get('msnError') }}
	</div>
@endif
<div class="panel panel-success">
    <div class="panel-heading" >
        <h3 class="panel-title">
            <a class="block-collapse collapsed" id='colp' data-toggle="collapse" href="#collapse-filter">
            B&uacute;squeda avanzada de solicitudes
            <span class="right-content">
                <span class="right-icon"><i class="fa fa-plus icon-collapse"></i></span>
            </span>
            </a>
        </h3>
    </div>
    <div id="collapse-filter" class="collapse" style="height: 0px;">
        <div class="panel-body " >

            {{-- COLLAPSE CONTENT --}}
            <form role="form" id="search-form">
              
               <div class="row">
                       
                    <div class="form-group col-sm-12 col-xs-12">
                        <label>Asunto:</label>
                       <input type="text" name="asunto"  id="asunto" class="form-control" placeholder="Escriba el asunto de la solicitud"   autocomplete="off">        
                    </div>
                       
                    </div>


                    
                     <div class="row">
                       
                    <div class="form-group col-sm-6 col-xs-6">
                       <label>Fecha recepcion:</label>
                       <input type="text" name="fechaRecepcion"  id="fechaRecepcion" class="form-control  datepicker date_masking" placeholder="dd-mm-yyyy"  data-date-format="dd-mm-yyyy"  autocomplete="off">             
                    </div>
                       <div class="form-group col-sm-6 col-xs-6">
                        <label>Fecha asignacion:</label>
                       <input type="text" name="fechaDetalle"  id="fechaDetalle" class="form-control  datepicker date_masking" placeholder="dd-mm-yyyy"  data-date-format="dd-mm-yyyy"  autocomplete="off">        
                    </div>


                    </div>
                     <div class="row">
                       <input type="hidden" name="idEstado" id="idEstado" value="{{ $estado }}">
                    <div class="form-group col-sm-6 col-xs-6">
                        <label>No. Presentación:</label>
                       <input type="text" name="noPresentacion"  id="noPresentacion" class="form-control" placeholder="Escriba el número de presentación de la solicitud"   autocomplete="off">        
                    </div>

              </div>
                    <div class="row">
                <div class="modal-footer" >
                    <div align="center">
                             <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" class="form-control"/>
                            
                  <button type="submit" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Buscar</button><button   class="btn btn-warning btn-perspective" id="btnLimpiar" type="button" onclick="limpiarFormulario()"><i class="fa fa-eraser" ></i>Limpiar</button>
                           </div>
                        </div>
                    
                    
            </form>
            {{-- /.COLLAPSE CONTENT --}}
        </div><!-- /.panel-body -->
    </div><!-- /.collapse in -->
</div>

<div class="the-box">
    
    <!-- BEGIN DATA TABLE -->
	<div class="table-responsive">
	<table class="table table-th-block table-success table-hover" id="dt-solicitudes" style="font-size:13px;" width="100%">
		<thead class="the-box dark full">
			<tr>
                <th>Correlativo</th>
                <th>Asunto</th>
                <th>Fecha Recepcion</th>
                <th>Fecha Asignación</th>
                <th>Acciones</th>

			</tr>
     	</thead>
     	<tbody>
        </tbody>
	</table>
	</div><!-- /.table-responsive -->
</div><!-- /.the-box .default -->
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" class="form-control"/>
<!-- END DATA TABLE -->
@endsection

@include('solicitudes.paneles.notificarSolicitud')



@section('js')
 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 


<script>
 $('[data-toggle="tooltip"]').tooltip(); 
var table;
 function limpiarFormulario(){
       location.reload();          
    }

$(document).ready(function(){

    var $states = $(".js-source-states");
                    var statesOptions = $states.html();
                    $states.remove();
                    $(".js-states").append(statesOptions);
                    $(".select_plaza").select2({
                        placeholder: "Seleccione un titular...",
                        allowClear: true
                    });
    var $states2 = $(".js-source-states2");
                    var statesOptions = $states2.html();
                    $states2.remove();
                    $(".js-states2").append(statesOptions);
                    $(".select_plaza2").select2({
                        placeholder: "Seleccione un estado...",
                        allowClear: true
                    });



      table = $('#dt-solicitudes').DataTable({
        filter:false,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('get.firma.revision') }}",
             data: function (d) {
                d.idEstado= $('#idEstado').val();
                d.asunto= $('#asunto').val();
                d.fechaRecepcion= $('#fechaRecepcion').val();
                d.fechaDetalle= $('#fechaDetalle').val();
                d.noPresentacion= $('#noPresentacion').val();
              
            }
        },
         columns: [
          {
                "className":      'details-control',
                "orderable":      true,
                "searchable":     true,
                "data":           'noPresentacion', "name": 'noPresentacion',
                "defaultContent": ''
            },
          {data: 'asunto', name: 'asunto'},
          {data: 'fechaRecepcion', name: 'fechaRecepcion'},
          {data: 'fechaDetalle', name: 'fechaDetalle',ordenable:true},
		     {data: 'detalle', name: 'detalle',ordenable:false,searchable:false},
            
        ],
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}"
            
            
        },
       "columnDefs": [ {
            "width": "20%",
            "searchable": false,
            "orderable": false,
            "targets": [0,1,4]
        } ],

        "order": [[ 3, 'desc' ]]
    });
	
	



    $('#search-form').on('submit', function(e) {
    	table.draw();
        e.preventDefault();
        $("#colp").attr("class", "block-collapse collapsed");
        $("#collapse-filter").attr("class", "collapse");
    });
    table.rows().remove();
    table.ajax.reload();
});


function asignarParticipantes(id){
  document.getElementById('idParticipante').style.display = 'block';

     var idSoli = id;
	$.get("{{route('get.empleados.jefes')}}", function(data) {
           
                    try{
                      $('#idSoli').val("");
                      document.getElementById("idParticipante").length=0;
                     
                      $('#idSoli').val(id); 
                          $.each(data, function(i, value) {
                                    // alert(value.nombresEmpleado)
                                     
                                     $('#idParticipante').append('<option value="'+value.idEmp+'">'+value.nombresEmpleado+' '+value.apellidosEmpleado+' ('+value.prefijo+')</option>');
                                });

                    
                        }
                    catch(e)
                    {
                      console.log(e);
                    }
                    
                  });


	$('#modalParcitipantes').modal('toggle'); 
    

      $('#formModal').submit(function(e){
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
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Los participantes se han registrado en la solicitud!</p></strong>",function(){
                var obj =  JSON.parse(response);
                 table.ajax.reload();
                 $('#modalParcitipantes').modal('hide'); 
              // window.location.href = "{{route('lista.solicitud')}}";
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
  function isJson(str) {
      try {
          JSON.parse(str);
      } catch (e) {
          return false;
      }
      return true;
  }

   $('#dt-solicitudes tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    });

  function format (d) {   
  $des = d.descripcion;
var id = d.idSolicitud;
var str1 = "#";
var str2 = id;
var res = str1.concat(str2);

var str3 = "#part";
var str4 = id;
var res2 = str3.concat(str4);

 $.get("{{route('soli.busqueda')}}?param="+id, function(data) {
            try{
                         $.each(data, function(i, value) {
                $(res).append('<span class="label label-primary">'+value.nombreTitular+'</span><br><br>');

                });
          }catch(e){
            console.log(e);
          }
     });

  $.get("{{route('soli.participantes.busqueda')}}?param="+id, function(data) {
            try{
             $.each(data, function(i, value) {
                $(res2).append('<span class="label label-info">'+value.nombresEmpleado+' '+value.apellidosEmpleado+'</span><br><br>');

                });
          }catch(e){
            console.log(e);
          }
     });

  if($des==='' || $des==null){
    return '<table  cellspacing="0" border="0">'+
        '<tr>'+
            '<td width="10"><b>Descripci&oacute;n:<b>&nbsp;&nbsp;</td>'+
            '<td>No existe ning&uacute;na descripci&oacute;n</td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10"><b>Titulares:<b>&nbsp;&nbsp;</td>'+
            '<td><div id="listas"></div></td>'+
        '</tr>'+
        '<tr>'+
    '</table>';
  }else{
    return '<table  cellspacing="0" border="0"  width="100%">'+
        '<tr>'+
            '<td width="10"><b>Descripci&oacute;n:<b>&nbsp;&nbsp;</td>'+
            '<td>'+$des+'</td>'+

        '</tr>'+
        '<tr>'+
            '<td width="10"><b>Titulares:<b>&nbsp;&nbsp;</td>'+
            '<td><br><div id="'+id+'"></div></td>'+
        '</tr>'+
        '<tr>'+
            '<td width="10"><b>Participantes:<b>&nbsp;&nbsp;</td>'+
            '<td><div id="part'+id+'"></div></td>'+
        '</tr>'+
        '<tr>'+
    '</table>';


  }
}
function vertitulares(id){

  document.getElementById('listas').innerHTML='';
  $.get("{{route('soli.busqueda')}}?param="+id, function(data) {
            try{
                         $.each(data, function(i, value) {
                $('#listas').append('<span>-'+value.nombreTitular+'</span><br>');
                });
          }catch(e){
            console.log(e);
          }
     });
  $('#verTit').modal('toggle'); 

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
                 // alertify.success('¡EXITO! La solicitud fue aprobada');
                   table.ajax.reload();
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
                  alertify.success('¡EXITO! La solicitud a finalizado');
                   table.ajax.reload();
                //   location.reload();
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
                table.ajax.reload();
                $('#notificarSoli').modal('hide'); 
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




</script>
@endsection

