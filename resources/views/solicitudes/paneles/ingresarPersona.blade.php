<div class="modal fade" id="formNuevoVisitante" tabindex="-1" role="dialog" >
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
                   NUEVO VISITANTE
                </h4>
            </div>
            <div class="modal-body">
              <form action="" method="POST" class="form form-vertical" role="form" id="nuevoVisitante">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="panel-body">                                                    
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>NIT</b></div>
                                    <input type="text" class="form-control nit_sv_masking" id="nit" name="nit" value="" autocomplete="off">
                                </div>
                            </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="input-group">
                                        <div class="input-group-addon"><b>Nombre Tratamiento</b></div>
                                      <select class="form-control" id="tratamiento" name="tratamiento"></select>
                                    </div>
                                </div> 
                            <!--<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Fecha nacimiento</b></div>
                                    <input type="text" class="form-control datepicker date_masking" id="fechaNacimiento" name="fechaNacimiento" value="">
                                </div>
                            </div>-->
                          </div> 
                          <br><br>
                          <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Tipo de documento</b></div>
                                    <select class="form-control" name="tipoDoc" id="tipoDoc" onclick="habilitarInput();"></select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N1">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento DUI</b></div>
                                    <input type="text" class="form-control dui_masking" id="numDoc1" name="numDoc1" autocomplete="off">
                                    </div>
                             </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N2" style="display: none;">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento</b></div>
                                    <input type="text" class="form-control" id="numDoc2" maxlength="30" name="numDoc2" autocomplete="off">
                                    </div>
                               </div>
                               
                            </div>
                         
                          <br><br>
                           <div class="form-group"> 
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Nombres</b></div>
                                    <input type="text" class="form-control" id="nombres" name="nombres" value="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Apellidos</b></div>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="" autocomplete="off">
                                </div>
                            </div>

                            </div>
                             <!--<div class="form-group">
                         
                                <!--<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="input-group">
                                        <div class="input-group-addon"><b>Conocido por</b></div>
                                      <input type="text" class="form-control" id="conocido" name="conocido" value="">
                                    </div>
                                </div> 
                            </div> -->
                           <!-- <div class="form-group">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="input-group">
                                        <div class="input-group-addon"><b>DIRECCI&Oacute;N</b></div>
                                        <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                                    </div>
                                </div>  
                            </div>-->
                       <!-- <div class="form-group">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">   
                                <div class="input-group">
                                    <div class="input-group-addon"><b>DEPARTAMENTO</b></div>
                                    <select class="form-control" id="departamento" name="departamento"></select> 
                                </div>          
                            </div> 
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">   
                                <div class="input-group">
                                    <div class="input-group-addon"><b>MUNICIPIO</b></div>
                                    <select class="form-control" id="municipio" name="municipio">
                                    </select> 
                                </div>          
                            </div>                                  
                        </div> -->
                        <br>  <br>              
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>TELEFONOS DE CONTACTO:</b></div>
                                    <input type="text" class="form-control cel_masking" minlength="9" name="tel1" id="tel1" value="" autocomplete="off">
                                    <input type="text" class="form-control cel_masking" minlength="9" name="tel2" id="tel2" value="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                             <div class="input-group">
                                    <div class="input-group-addon"><b>SEXO</b></div>
                                     <select class="form-control" id="sexo" name="sexo">
                                     <option value="M">Masculino</option>
                                     <option value="F">Femenino</option>
                                    </select> 
                                </div>
                                <div class="input-group">
                                    <div class="input-group-addon"><b>CORREO ELECTR&Oacute;NICO</b></div>
                                    <input type="email" class="form-control" id="email" name="email" value="" autocomplete="off">

                                </div>
                               
                        </div>                                       
                                                                                         
                    </div>
                  
                              <br><br>    <br>  <br>         
                 <div class=" text-center">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-default"
                            data-dismiss="modal">
                                Cancelar</button>
                    </div>             
                    
                    
                </form>     
               </div>
            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
          
        </div>
    </div>
</div>

<div class="modal fade" id="formNuevoTitular" tabindex="-1" role="dialog" >
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
                   NUEVO TITULAR
                </h4>
            </div>
            <div class="modal-body">
              <form action="{{route('guardar.nuevo.titular')}}" method="POST" class="form form-vertical" role="form" id="nuevoTitular">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="panel-body">                                                    
                       
                           <div class="form-group"> 
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Nombre</b></div>
                                    <input type="text" class="form-control" id="nombreTitular" name="nombreTitular" value="">
                                </div>
                            </div>
                          
                            </div>
                             <br><br>
                                     
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>TELEFONOS DE CONTACTO:</b></div>
                                    <input type="text" class="form-control cel_masking" minlength="9" name="telefono1" id="telefono1" value="">
                                    <input type="text" class="form-control cel_masking" minlength="9" name="telefono2" id="telefono2" value="">
                                </div>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>CORREO ELECTR&Oacute;NICO</b></div>
                                    <input type="email" class="form-control" id="emailTitular" name="emailTitular" value="">
                                </div>
                            </div>
                        </div>                                       
                                                                                         
                    </div>
                   <div class="modal-footer">                          
                 <div class=" text-center">
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