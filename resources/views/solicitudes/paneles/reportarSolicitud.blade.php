<div class="modal fade" id="formReportarSol" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                   REPORTAR ASIGNACIÓN
                </h4>
            </div>
            <div class="modal-body">
              <form action="{{route('reportar.sol.asignacion')}}" method="POST" class="form form-vertical" role="form" id="reportarSolasig">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="idsol" name="idsol" value="{{ $info->idSolicitud}}">
                    <div class="panel-body">                                                    
                       
                           <div class="form-group"> 
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Comentario</b></div>
                                    <textarea cols="90" rows="5" id="comentario" name="comentario" class="form-control"></textarea>
                                </div>
                            </div>
                            </div>
                             <br><br>                                                       
                    </div>
                   <div class="modal-footer">                          
                 <div class=" text-center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-default"
                            data-dismiss="modal">
                                Cancelar</button>
                    </div></div>                     
                    </form>
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
        </div>
    </div>
</div>