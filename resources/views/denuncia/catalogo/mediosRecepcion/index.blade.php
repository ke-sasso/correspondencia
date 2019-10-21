@extends('master')

@section('css')
<style type="text/css">
    td.details-control {
        background: url("{{ asset('/plugins/datatable/images/details_open.png') }}") no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.details-control {
        background: url("{{ asset('/plugins/datatable/images/details_close.png') }}") no-repeat center center;
    }
</style>
@endsection

@section('contenido')
{{-- MENSAJE DE EXITO --}}

{{-- MENSAJE DE ERROR --}}
@if(Session::has('msnError'))
	<div class="alert alert-danger square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
		<strong>Algo ha salido mal.!</strong>
			{{ Session::get('msnError') }}
	</div>
@endif


<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<a href="#" style="" type="button" id="cancelar" class="btn btn-primary m-t-10" onclick="nuevo();"><i class="fa fa-plus" aria-hidden="true"></i>Nuevo medio de recepción</a>
</div>
</div>


    <div class="the-box">

	<div class="table-responsive">
	<table class="table table-striped table-hover"  id="tr-manera" style="font-size:14px;" width="100%">
		<thead class="the-box dark full">
			<tr>
				        <th>Tipos de medio de recepción</th>
                <th>-</th>      
			</tr>
     	</thead>
     	<tbody></tbody>
	</table>
	</div><!-- /.table-responsive -->
</div><!-- /.the-box .default -->
<!-- END DATA TABLE -->
<div class="modal fade modal-center" id="info"  style='display:none;' tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
            <div class="row">
            <div class="col-xs-1 col-sm-1 col-md-2 col-lg-2"></div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <h4 class="modal-title" id="frmModalLabel">
                   Formulario para Medios de recepción
                </h4>
            </div>
                <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
                
               </div>
            </div>  
            </div>  
                
        <!-- Modal Body -->
      <div class="modal-body">

        <form role="form" method="post" action="{{route('store.info.medios')}}"   autocomplete="off" id="formModal">
                <div class="form-group">
                  <div class="row">

                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Tipo de medio de recepción</b></div>
                      <input type="text" class="form-control" id="manera" name="manera" autocomplete="off" >
                    </div>
                    </div>

                 </div>
                </div>
                

                <input type="hidden" class="form-control"  id="id" name="id">
                <input type="hidden" class="form-control"  id="accion" name="accion">

                                    

                      <div class="from-group" align="center">
                     
                       <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                     <button type="submit" class="btn btn-primary btn-perspective">Enviar <i class="fa fa-check"></i></button>
                      </div>      
            </form>
            </div>
                        
         

        <!-- End Modal Body -->
        <!-- Modal Footer -->
        <div class="modal-footer">                        
            <button type="button" class="btn btn-default" data-dismiss="modal">CERRAR
            </button>                
        </div>
      </div>
    </div>
 
</div>
@endsection


@section('js')
 {!! Html::script('plugins/bootstrap-modal/js/bootstrap-modalmanager.js') !!}

<script>

$( document ).ready(function() {
    var table = $('#tr-manera').DataTable({
        filter:true,
        processing: true,
        serverSide: false,

        ajax: {
            url: "{{route('get.medios.recepcion')}}",
        },
        columns: [
          {data: 'nombreMedio', name: 'nombreMedio'},
		      {data: 'detalle', name: 'detalle',ordenable:false,searchable:false},
            
        ],
        language: {
            "sProcessing": '<div class=\"dlgwait\"></div>',
            "url": "{{ asset('plugins/datatable/lang/es.json') }}",
            "searchPlaceholder": ""
   
        },
       "columnDefs": [ {
            "searchable": true,
            "orderable": false,      
             "targets": [1]
        } ],

        "order": [[ 0, 'desc' ]]
    } );
	
});
function  nuevo(){
       $('#info').modal('toggle'); 
        $('#accion').val(1);
}
function editarInfo(id){

    $.get("{{route('data.medios.recepcion')}}?id="+id, function(data) {
                    try{
                      $('#manera').val(""); 
                      $('#id').val("");
                      $('#accion').val("");


                      $('#manera').val(data.nombreMedio); 
              
                      $('#id').val(data.idMedio);
                      $('#accion').val(2);
                     
                    }catch(e){
                      console.log(e);
                    }
                    
                  });

    $('#info').modal('toggle'); 
    
}
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
            
                var obj =  JSON.parse(response);
 
              alertify.alert("Mensaje de Sistema","<strong><p class='text-justify'>¡Informaci&oacute;n ingresada de forma exitosa!</p></strong>",function(){
                      location.reload();
                    });
             
     
           
              
            }else{
              alertify.alert("Mensaje de Sistema","<strong><p class='text-warning text-justify'>ADVERTENCIA:"+ response +"</p></strong>")
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
        $('body').modalmanager('loading');
        alertify.alert("Mensaje de Sistema","<strong><p class='text-danger text-justify'>ERROR: No se pudo registrar la informaci&oacute;n!</p></strong>");
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

       
</script>
@endsection
