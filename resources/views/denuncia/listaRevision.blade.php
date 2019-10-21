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
<?php 
 /* $permisos = App\UserOptions::getAutUserOptions();

  foreach($permisos as $a){
    echo "<br>".$a;
  }
*/
?>

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
                       
                    <div class="form-group col-sm-6 col-xs-6">
                        <label>Asunto:</label>
                       <input type="text" name="asunto"  id="asunto" class="form-control" placeholder="Escriba el asunto de la solicitud"   autocomplete="off">        
                    </div>
                       <div class="form-group col-sm-6 col-xs-6">
                        <label>Fecha evento:</label>
                       <input type="text" name="fechaRecepcion"  id="fechaRecepcion" class="form-control  datepicker date_masking" placeholder="dd-mm-yyyy"  data-date-format="dd-mm-yyyy"  autocomplete="off">        
                    </div>
              </div>
                  <div class="row">
                       
                    <div class="form-group col-sm-6 col-xs-6">
                        <label>No. Presentación:</label>
                       <input type="text" name="noPresentacion"  id="noPresentacion" class="form-control" placeholder="Escriba el número de presentación de la solicitud"   autocomplete="off">        
                    </div>
                   

              </div>

                                  <div class="row">
                <div class="modal-footer" >
                    <div align="center">
                             <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" class="form-control"/>
                            
                  <button type="submit" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Buscar</button>
                  <button   class="btn btn-warning btn-perspective" id="btnLimpiar" type="button" onclick="limpiarFormulario()"><i class="fa fa-eraser" ></i>Limpiar</button>
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
                <th>Fecha del evento</th>
                <th>Estado</th>
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

@section('js')
 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!} 


<script>
 $('[data-toggle="tooltip"]').tooltip(); 
var table;
    function limpiarFormulario(){
        window.location = "{{route('lista.nuevas.denuncia')}}";            
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
            url: "{{ route('get.denuncia.revision') }}",
             data: function (d) {
                d.idTitular= $('#idTitular').val();
                d.idEstado= $('#idEstado').val();
                d.asunto= $('#asunto').val();
                d.fechaRecepcion= $('#fechaRecepcion').val();
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
          {data: 'fechaEvento', name: 'fechaEvento'},
          {data: 'nombreEstado', name: 'nombreEstado'},
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
            "targets": [0]
        } ],

        "order": [[ 0, 'desc' ]]
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


     if($des==='' || $des==null){
    return '<table  cellspacing="0" border="0">'+
        '<tr>'+
            '<td width="10"><b>Descripci&oacute;n:<b>&nbsp;&nbsp;</td>'+
            '<td>No existe ning&uacute;na descripci&oacute;n</td>'+
        '</tr>'+
    '</table>';
  }else{
    return '<table  cellspacing="0" border="0"  width="100%">'+
        '<tr>'+
            '<td width="10"><b>Descripci&oacute;n:<b>&nbsp;&nbsp;</td>'+
            '<td>'+$des+'</td>'+
            '</tr>'+
    '</table>';


  }
}







</script>
@endsection
