<div class="modal fade" id="formNuevoEstablecimiento" tabindex="-1" role="dialog" >
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
                   NUEVO ESTABLECIMIENTO
                </h4>
            </div>
            <div class="modal-body">
              <form action="{{route('guardar.nuevo.establecimiento')}}" method="POST" class="form form-vertical" role="form" id="nuevoEstablecimiento">
                            
                    <div class="panel-body">                                                    
                       
                           <div class="form-group"> 
                           <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Nombre del establecimiento</b></div>
                                    <input type="text" class="form-control" id="nombreComercial" name="nombreComercial" value="" required autocomplete="off">
                                </div>
                            </div>
                            </div>
                           </div>

                           <div class="form-group"> 
                           <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                <div class="input-group-addon"><b>Propietario</b></div>
                                <input type="text" class="form-control" id="propietario" name="propietario" value="" autocomplete="off">
                                </div>
                            </div>
                            </div>
                            </div>
                          
                      
                           <div class="form-group"> 
                           <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Dirección</b></div>
                                    <textarea class="form-control" id="direccion" name="direccion" autocomplete="off" required></textarea>
                                </div>
                            </div>
                            </div> 
                           </div>  
                      
                           <div class="form-group"> 
                           <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Observación</b></div>
                                    <textarea class="form-control" id="observacion" name="observacion" autocomplete="off"></textarea>
                                </div>
                            </div>
                            </div>
                           </div>                                    
                                                                                         
                    </div>
                   <div class="modal-footer">                          
                 <div class=" text-center">
                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="tipoPost" id="tipoPost">

                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-default"
                            data-dismiss="modal">
                                Cancelar</button>
                    </div>             
            </div>
                                
                    
                </form>     
               
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
           
        </div>
    </div>
</div>
</div>