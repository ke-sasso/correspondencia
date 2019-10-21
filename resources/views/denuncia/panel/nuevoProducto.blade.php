<div class="modal fade" id="formNuevoProductos"  name="formNuevoProductos" tabindex="-1" role="dialog" >
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
                   NUEVO PRODUCTO
                </h4>
            </div>
            <div class="modal-body">
              <form action="{{route('guardar.nuevo.producto')}}" method="POST" class="form form-vertical" role="form" id="nuevoProducto">
                            
                    <div class="panel-body">                                                    
                       
                           <div class="form-group"> 
                           <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Nombre del producto</b></div>
                                    <input type="text" class="form-control" id="nombreComercial" name="nombreComercial" value="" required>
                                </div>
                            </div>
                            </div>
                           </div>
                           <div class="form-group"> 
                           <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Fabricante</b></div>
                                <input type="text" class="form-control" id="propietario" name="propietario" value="">
                                </div>
                            </div>
                            </div>
                            </div>
                            
                      
                           <div class="form-group"> 
                           <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Fecha vencimiento</b></div>
                                   <input type="text" class="form-control datepicker" id="fecha" name="fecha" value="">
                                </div>
                            </div>
                             <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>No. Lote</b></div>
                                   <input type="text" class="form-control" id="nolote" name="nolote" value="">
                                </div>
                            </div>
                           </div>
                           </div>   
                      
                           <div class="form-group"> 
                            <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Observación</b></div>
                                    <textarea class="form-control" id="observacion" name="observacion"></textarea>
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
