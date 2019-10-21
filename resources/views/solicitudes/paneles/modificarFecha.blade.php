<div class="modal fade modal-center" id="fechaPar"  style='display:none;' tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
            <div class="row">
            <div class="col-xs-1 col-sm-1 col-md-2 col-lg-2"></div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <h4 class="modal-title" id="frmModalLabel">
                  MODIFICAR FECHA PARTICIPANTE
                </h4>
            </div>
                <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">

               </div>
            </div>  
            </div>  
                
        <!-- Modal Body -->
      <div class="modal-body">

        <form role="form" method="post" action="{{route('guardar.fecha.part')}}"   autocomplete="off" id="formFechaParte">
                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha Respuesta</b></div>
                      <input type="date" class="form-control" id="fechaParticipante" name="fechaParticipante"  />
                    </div>
                    </div>
                    </div>
                </div>
                 <input type="hidden" class="form-control"  id="idmodificarPart" name="idmodificarPart">
                  <div class="from-group" align="center">
                     
                       <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-primary btn-perspective">ACEPTAR <i class="fa fa-check"></i></button>
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