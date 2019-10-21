<div class="modal fade modal-center" id="notificarSoli"  style='display:none;' tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
            <div class="row">
            <div class="col-xs-1 col-sm-1 col-md-2 col-lg-2"></div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <h4 class="modal-title" id="frmModalLabel">
                   NOTIFICAR SOLICITUD
                </h4>
            </div>
                <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">

               </div>
            </div>  
            </div>  
                
        <!-- Modal Body -->
      <div class="modal-body">

        <form role="form" method="post" action="{{route('guardar.notificacion')}}"   autocomplete="off" id="formModalNoti">
                 <button type="button" class="btn btn-primary" id="btnBuscarSolicitante"><i class="fa fa-search" >Buscar persona</i></button></span>
                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombres</b></div>
                     <input type="text" name="notnombres" id="notnombres" class="form-control">
                    </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Apellidos</b></div>
                     <input type="text" name="notapellidos" id="notapellidos" class="form-control">
                    </div>
                    </div>
                    </div>
                </div>
                 <div class="form-group">
                     <div class="row">
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Teléfono</b></div>
                     <input type="text" name="nottel" id="nottel" class="form-control">
                    </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Correo</b></div>
                     <input type="email" name="notcorreo" id="notcorreo" class="form-control">
                    </div>
                    </div>
                    </div>
                </div>
                 <div class="form-group">
                     <div class="row">
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Dui</b></div>
                     <input type="text" name="notdui" id="notdui" class="form-control dui_masking">
                    </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nit</b></div>
                     <input type="text" name="notnit" id="notnit" class="form-control">
                    </div>
                    </div>
                    </div>
                </div>
                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Observación</b></div>
                      <textarea class="form-control" id="observacion" name="observacion" autocomplete="off" ></textarea>
                    </div>
                    </div>
                    </div>
                </div>
                 <input type="hidden" class="form-control"  id="idSoliNotificar" name="idSoliNotificar">
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