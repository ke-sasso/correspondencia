<div class="modal fade modal-center" id="editarSoli"  style='display:none;' tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
            <div class="row">
            <div class="col-xs-1 col-sm-1 col-md-2 col-lg-2"></div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <h4 class="modal-title" id="frmModalLabel">
                  EDITAR SOLICITUD
                </h4>
            </div>
                <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">

               </div>
            </div>  
            </div>  
                
        <!-- Modal Body -->
      <div class="modal-body">

        <form role="form" method="post" action="{{route('guardar.notificacion')}}"   autocomplete="off" id="formModalNoti">
                <div class="panel-body">
                 <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Persona que presenta</b></div>
                        <input type="text" class="form-control" id="nombreSolicitante" name="nombreSolicitante" autocomplete="off" readonly>
                      <input type="hidden" class="form-control" id="nitSolicitante" name="nitSolicitante" autocomplete="off" readonly>
                      <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="btnBuscarSolicitante"><i class="fa fa-search" ></i></button></span>  
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Titular</b></div>
                      <input type="text" class="form-control" id="nombre" name="nombre" autocomplete="off" >
                      <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="btnBuscarTitular"><i class="fa fa-search" ></i></button></span>
                    </div>
                    </div>
                    </div>
                </div>
                <div class="form-group" id="cont-tit" style="display: none;"> 
                                <div class="table-responsive">
                                <table class="table table-th-block table-success table-hover" id="dt-Tit" style="font-size:13px;" width="100%">
                                  <thead class="the-box dark full">
                                    <tr>
                                    <th>Nombre titular</th>
                                    <th>-</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      </tbody>
                                </table>
                                </div><!-- /.table-responsive -->
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
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group">
                       <div class="input-group-addon"><b>Documentos (PDF O IMG)</b></div>
                       <input id="fileA" name="fileA[]" required type="file" multiple class="file-loading"> 
                      
                       </div>
                     <div id="errorBlockNew" class="help-block"></div>
                    </div>
                    </div>
                </div>
                </div>
                
                 <input type="hidden" class="form-control"  id="idSoliEditar" name="idSoliEditar">
                  <div class="from-group" align="center">
                     
                       <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <button type="submit" class="btn btn-primary btn-perspective">EDITAR <i class="fa fa-check"></i></button>
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