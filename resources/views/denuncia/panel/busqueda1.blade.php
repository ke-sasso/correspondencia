<div class="panel panel-success">
                                  <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <a class="block-collapse" data-parent="#accordion-2" data-toggle="collapse" href="#accordion-2-child-2">
                                       Búsqueda avanazada de establecimientos denunciados
                                       <span class="right-icon">
                                                <i class="glyphicon glyphicon-plus icon-collapse"></i>
                                        </span>
                                        </a>
                                    </h3>
                                  </div>

                                    <div id="accordion-2-child-2" class="collapse">
                                      <div class="panel-body">

                                      <form role="form" id="search-form">

                                      <div class="row">
                                         <div class="form-group col-sm-6 col-xs-6">
                                         <label>Nombre establecimiento:</label>
                                          <input type="text" name="esta1"  id="esta1" class="form-control" placeholder="Escriba el asunto de la solicitud"   autocomplete="off">        
                                      </div>
                                      <div class="form-group col-sm-6 col-xs-6">
                                         <label>No.registro</label>
                                          <input type="int" name="no1"  id="no1" class="form-control" placeholder="Escriba el asunto de la solicitud"   autocomplete="off">        
                                      </div>
                                      </div>
                                       <div class="row">
                                         <div class="form-group col-sm-12 col-xs-12">
                                         <label>Dirección:</label>
                                          <input type="text" name="direccion1"  id="direccion1" class="form-control" placeholder="Escriba el asunto de la solicitud"   autocomplete="off">        
                                      </div>
                                      </div>


                         <div class="modal-footer" >
                         <div align="center">
                             <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" class="form-control"/><button type="submit" class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Buscar</button>
                           </div>
                        </div>
                                     </form>
                                      </div><!-- /.panel-body -->
                                    </div><!-- /.collapse in -->
 </div><!-- /.panel panel-success -->