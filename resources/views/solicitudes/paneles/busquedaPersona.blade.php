{{-- / MODAL PARA BUSCAR A LA PERSONA QUE VISITA--}}
<div class="modal fade" id="panelVisitante" tabindex="-1" role="dialog" >
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
                    B&Uacute;SQUEDA DE VISITANTE
                </h4>
            </div>            
            <div class="modal-body">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                      <a class="btn btn-success" onclick="modalNuevoVisitante();" id="btnNuevaPersona" ><i class="fa fa-plus-circle"></i> Nuevo</a>  
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="input-group">
                            <input type="text" class="form-control" id="txtBuscar" placeholder="">
                            <span class="input-group-btn">
                                <button type="button" id="btnBuscarPer" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>                
              <div class="pull-left">     
                
            </div>
            
            <!--
            <br><br>

            	<div class="panel panel-success">
    <div class="panel-heading" >
        <h3 class="panel-title">
            <a class="block-collapse collapsed" id='colp' data-toggle="collapse" href="#collapse-filter">
            B&uacute;squeda Avanzada de Visitante
            <span class="right-content">
                <span class="right-icon"><i class="fa fa-plus icon-collapse"></i></span>
            </span>
            </a>
        </h3>
    </div>


    
    <div id="collapse-filter" class="collapse" style="height: 0px;">
        <div class="panel-body " >
            {{-- COLLAPSE CONTENT --}}
            <form role="form" id="search-form-pn" >
               <div class="row">
                    <div class="form-group col-sm-5 col-xs-12 col-md-6 col-lg-6">
                        <label>Nombre persona:</label>
                        <input type="text" name="np" id="np" class="form-control" autocomplete="off">
                         
                    </div>
                    <div class="form-group col-sm-5 col-xs-12 col-md-6 col-lg-6">
                        <label>Apellido persona:</label>
                        <input type="text" name="ap" id="ap" class="form-control" autocomplete="off">
                         
                    </div>
               </div>
                <div class="row">
                    <div class="form-group col-sm-5 col-xs-12 col-md-6 col-lg-6">
                        <label>NIT persona:</label>
                        <input type="text" name="nitp" id="nitp" class="form-control nit_sv_masking" autocomplete="off">
                         
                    </div>
               </div>
                <div class="modal-footer" >
                    <div align="center">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}" class="form-control"/>
                  <button type="submit"  class="btn btn-success btn-perspective"><i class="fa fa-search"></i> Buscar</button>
                           </div>
                        </div>
                    
                    
            </form>
            {{-- /.COLLAPSE CONTENT --}}
        </div><!-- /.panel-body 
    </div><!-- /.collapse in 

    
</div>
-->
                    <div class="table">
                    	<table width="100%" class="table table-hover" id="dt-visitante">
                    		<thead class="the-box dark full">
                    			<tr>
                    				<th width="20%">NIT</th>
                                    <th width="20%"># DOCUMENTO</th>
                    				<th width="20%">NOMBRES</th>
                                    <th>APELLIDOS</th>
                    				<th>-</th>
                    			</tr>
                    		</thead>
                    		<tbody></tbody>
                    	</table>
                    </div>
               
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
            <div class="modal-footer">		                    
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Cancelar
                </button>                
            </div>
        </div>
    </div>
</div>

 {{-- / MODAL PARA BUSCAR EL TITULAR--}}
<div class="modal fade" id="panelTitular" tabindex="-1" role="dialog" >
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
                    B&Uacute;SQUEDA DE TITULAR
                </h4>
            </div>
            <div class="modal-body">
            <div class="pull-left">     
                <a class="btn btn-success" onclick="modalNuevoTitular();" id="btnNuevaPersonaA" ><i class="fa fa-plus-circle"></i> Nuevo</a>               
            </div>
            	
                    <div class="table">
                    	<table width="100%" class="table table-hover" id="dt-titular">
                    		<thead class="the-box dark full">
                    			<tr>
                    				<th>NOMBRE</th>
                    			    <th>-</th>
                    			</tr>
                    		</thead>
                    		<tbody>
                    		
                    		</tbody>
                    	</table>
                    </div>
               
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
            <div class="modal-footer">	
            <a class="btn btn-sm btn-success btn-perspective" onclick="enviarTitular();" ><i class="fa fa-check-square-o"></i>Guardar titular</a>	                    
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Cancelar
                </button>                
            </div>
        </div>
    </div>
</div>