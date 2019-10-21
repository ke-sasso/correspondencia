<div class="modal fade" id="panelEstablecimientos" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    B&Uacute;SQUEDA DE ESTABLECIMIENTO
                </h4>
            </div>
            <div class="modal-body">
            <div class="panel with-nav-tabs panel-success">
                              <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#panel-home-1" data-toggle="tab">ESTABLECIMIENTOS</a></li>
                                   
                                    <li>
                                        <a class="btn btn-success" onclick="nuevoEstablecimiento();" id="btnNuevaEstablecimiento" ><i class="fa fa-plus-circle"></i> Nuevo</a>  
                                    </li>
                                </ul>
                              </div>
                                <div id="panel-collapse-1" class="collapse in">
                                    <div class="panel-body">
                                        <div class="tab-content">
                                            <div class="tab-pane fade in active" id="panel-home-1">

                                                <div class="table">
                                                <form role="form" id="search-form">
                                                <div class="row">
                       
                                                <div class="form-group col-sm-10 col-xs-10">
                                             
                                                <input type="text" name="buscar"  id="buscar" class="form-control" placeholder="Escriba cualquier dato relacionado al establecimiento..."   autocomplete="off">        
                                                </div>
                                                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" class="form-control"/>
                                                    <button type="submit" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Buscar</button>
                       
                                                </div>
                                                </form>
                                                  <table width="100%" class="table table-hover" id="dt-EstNo">
                                                        <thead class="the-box dark full">
                                                            <tr>
                                                                <th>REGISTRO</th>
                                                                <th>NOMBRE ESTABLECIMIENTO</th>
                                                                <th>DIRECCIÓN</th>
                                                                <th>ESTADO</th>
                                                                <th>-</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                            
                                                        </tbody>
                                                    </table>
                                                    </div>
                                              
                                            </div>
                                          
                                        
                                        </div><!-- /.tab-content -->
                                    </div><!-- /.panel-body -->
                                  
                                </div><!-- /.collapse in -->
                            </div><!-- /.panel .panel-success -->
                             <div align="center">   
            <a class="btn btn-sm btn-success btn-perspective" onclick="enviarEstablecimientos();" ><i class="fa fa-check-square-o"></i>Guardar Establecimientos</a>                     
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Cancelar
                </button>                
            </div>
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
        </div>
    </div>
</div>


<div class="modal fade" id="panelProducto" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
                <button type="button" class="close" 
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    B&Uacute;SQUEDA DE PRODUCTOS
                </h4>
            </div>
            <div class="modal-body">
            <div class="panel with-nav-tabs panel-success">
                              <div class="panel-heading">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#panel-home-12" data-toggle="tab">PRODUCTOS</a></li>
                                    <li>
                                        <a class="btn btn-success" onclick="nuevoProducto();" id="btnNuevaProducto" ><i class="fa fa-plus-circle"></i> Nuevo</a>  
                                    </li>
                                </ul>
                              </div>
                                <div id="panel-collapse-1" class="collapse in">
                                    <div class="panel-body">
                                        <div class="tab-content">
                                       
                                            <div class="tab-pane fade in active" id="panel-home-12">
                                                   <div class="table">
                                                    <form role="form" id="search-form2">
                                                <div class="row">
                       
                                                <div class="form-group col-sm-10 col-xs-10">
                                             
                                                <input type="text" name="buscar2"  id="buscar2" class="form-control" placeholder="Escriba cualquier dato relacionado al producto..."   autocomplete="off">        
                                                </div>
                                                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" class="form-control"/>
                                                    <button type="submit" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Buscar</button>
                       
                                                </div>
                                                </form>
                                                 <table width="100%" class="table table-hover" id="dt-ProNo">
                                                        <thead class="the-box dark full">
                                                            <tr>
                                                                <th>REGISTRO</th>
                                                                <th>NOMBRE COMERCIAL</th>
                                                                <th>PROPIETARIO</th>
                                                                <th>FECHA VENCIMIENTO</th>
                                                                <th>NO.LOTE</th>
                                                                <th>ESTADO</th>
                                                                <th>-</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                            
                                                        </tbody>
                                                    </table>
                                                    </div>
                                              
                                            </div>
                                        
                                        </div><!-- /.tab-content -->
                                    </div><!-- /.panel-body -->
                                  
                                </div><!-- /.collapse in -->
                            </div><!-- /.panel .panel-success -->
            <div align="center">  
            <a class="btn btn-sm btn-success btn-perspective" onclick="enviarProductos();" ><i class="fa fa-check-square-o"></i>Guardar Productos</a>                     
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Cancelar
            </button>                
            </div>
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
        </div>
    </div>
</div>