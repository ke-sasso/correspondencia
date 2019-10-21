                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha del evento reportado</b></div>
                      <?php $date = date_create($detalle->fechaEvento); ?>
                  @if($detalle->fechaEvento=='1900-01-01' || $detalle->fechaEvento=='')
                     <input type="text" class="form-control datepicker date_masking" id="fechaEvento" name="fechaEvento"  autocomplete="off" placeholder="dd-mm-yyyy. Ejemplo(31-12-2017)" value="" disabled>
                 @else
                  <input type="text" class="form-control datepicker date_masking" id="fechaEvento" name="fechaEvento"  autocomplete="off" placeholder="dd-mm-yyyy. Ejemplo(31-12-2017)" value="{{date_format($date, 'd-m-Y')}}" disabled>
                      
                 @endif
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Medio de recepción</b></div>
                      <input type="text" class="form-control" id="medio" name="medio" autocomplete="off" value="{{$medio}}" disabled>
                    </div>
                    </div>
                   </div>
                </div>
                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Asunto</b></div>
                      <input type="text" class="form-control" id="asunto" name="asunto" autocomplete="off" value="{{$info->asunto}}" disabled>
                    </div>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                    <div class="input-group-addon"><b>MOTIVO</b></div>
                     <div class="form-group">
                            <div class="panel panel-square panel-default">
                          <div class="panel-heading">
                          <?php echo $info->descripcion;?> 
                          </div>
                           </div>                       
                    </div>
                    </div>
                    </div>
                </div>
    
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-9">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombre usuario</b></div>
                      <input type="text" class="form-control" id="usuario" name="usuario" value="{{$detalle->nombreUsuario}}" autocomplete="off" disabled >
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Edad</b></div>
                     <input type="text"  class="form-control cel_masking" id="edad" name="edad" autocomplete="off" value="{{$detalle->edad}}" disabled>
                    </div>
                    </div>


                    </div>
                </div>
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Profesión u Oficio</b></div>
                      <input type="text" class="form-control" id="profesion" name="profesion" autocomplete="off" value="{{$detalle->profesion}}" disabled>
                    </div>
                    </div>
                    </div>
                </div>
                   <div class="form-group">
                    <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-addon"><b>Tipo documento:</b></div>
                          <select  class="form-control" id="tipo" name="tipo" onclick="habilitarInput();" disabled>
                                  @if(trim($detalle->tipoDoc)=='DUI')
                                  <option value="DUI" selected>DUI</option>
                                  <option value="PASAPORTE">PASAPORTE</option>
                                  <option value="OTRO">OTRO</option>
                                  @elseif(trim($detalle->tipoDoc)=='PASAPORTE')
                                  <option value="DUI">DUI</option>
                                  <option value="PASAPORTE" selected>PASAPORTE</option>
                                  <option value="OTRO">OTRO</option>
                                  @else
                                  <option value="DUI">DUI</option>
                                  <option value="PASAPORTE">PASAPORTE</option>
                                  <option value="OTRO" selected>OTRO</option>
                                  
                                  @endif
                          </select>
                                </div>
                            </div>
                          @if(trim($detalle->tipoDoc)=='DUI')
                           <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N1">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento DUI</b></div>
                                    <input type="text" class="form-control dui_masking" id="numDocumentoP" name="numDocumentoP" autocomplete="off" value="{{$detalle->noDocumento}}" disabled>
                                    </div>
                             </div>
                             <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N2" style="display: none;" >
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento</b></div>
                                    <input type="text" class="form-control" id="numDocumento2" maxlength="30" name="numDocumento2" autocomplete="off" value="{{$detalle->noDocumento}}" disabled>
                                    </div>
                               </div> 
                          @else
                             <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N1" style="display: none;">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento DUI</b></div>
                                    <input type="text" class="form-control dui_masking" id="numDocumentoP" name="numDocumentoP" autocomplete="off" value="{{$detalle->noDocumento}}" disabled>
                                    </div>
                             </div>
                              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" id="N2">
                                    <div class="input-group">
                                    <div class="input-group-addon"><b># Documento</b></div>
                                    <input type="text" class="form-control" id="numDocumento2" maxlength="30" name="numDocumento2" autocomplete="off" value="{{$detalle->noDocumento}}" disabled>
                                    </div>
                               </div> 
                          @endif                                     
                        </div>                                                                 
                    </div>


                
                   <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Puede ser notificado en:</b></div>
                      <input type="text" class="form-control" id="notificado" name="notificado" autocomplete="off" value="{{$detalle->notificado}}" disabled>
                    </div>
                    </div>
                    </div>
                </div>
                 <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                    <div class="input-group-addon"><b>Ofrece de prueba:</b></div>
                     <div class="form-group">
                        <textarea name="prueba" id="prueba" rows="3" cols="141" disabled><?php echo strip_tags($detalle->ofrecePrueba);?></textarea>                                        
                        </div>
                    </div>
                    </div>
                </div>
                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                    <div class="input-group-addon"><b>Por lo antes mencionado PIDE:</b></div>
                     <div class="form-group">
                        <textarea name="pide" id="pide" rows="3" cols="141" disabled><?php echo strip_tags($detalle->pide);?></textarea>                                        
                        </div>
                    </div>
                    </div>
                </div>

                    <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Señalo para oír notificaciones:  </b></div>
                      <input type="text" class="form-control" id="aviso" name="aviso" value="{{$detalle->aviso}}" autocomplete="off" disabled>
                    </div>
                    </div>
                     
                    </div>
                    </div>

                     <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Télefonos a notificar</b></div>
                     <input type="text" class="form-control cel_masking" id="tel1" name="tel1" autocomplete="off" value="{{$detalle->tel1Notificar}}" disabled>
                     <input type="text" class="form-control cel_masking" id="tel2" name="tel2" autocomplete="off" value="{{$detalle->tel2Notificar}}" disabled>
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Correo</b></div>
                       <input type="email" class="form-control" id="correo" name="correo" autocomplete="off" value="{{$detalle->correoUsuario}}" disabled>
                    </div>
                    </div>
                    </div>
                    </div>
                      <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                    
                    <div class="input-group-addon"><b>Observación</b></div>
                            
                      <div class="panel panel-square panel-default">
                          <div class="panel-heading">
                          <?php echo $info->observaciones;?>   
                          </div>
                           </div>                                     
           
                    </div>
                    </div>
                </div>
                    <br>
                     @if(!empty($archivos))
                  @if(count($archivos)>=1)
                  <div class="table-responsive">
                          <table class="table table-th-block table-success table-hover" id="dt-solicitudes" style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Tipo de documento</th>
                              <th>Nombre del archivo</th>
                              <th>-</th>
                            </tr>
                          </thead>
                          <tbody>
                                @foreach($archivos as $ar)
                            <tr>
                               <td> 
                              @if(trim($ar->tipoArchivo)==='application/pdf')
                               <center><i class="fa fa-file-text" style="font-size:25px;"></i></center>
                              @elseif(trim($ar->tipoArchivo)==='image/jpeg' || trim($ar->tipoArchivo)==='image/png' || trim($ar->tipoArchivo)==='image/gif')
                               <center><i class="fa fa-picture-o" style="font-size:25px;"></i></center>
                               @else
                               <center><i class="fa fa-file-text" style="font-size:25px;"></i></center>
                              @endif
                              </td>  
                              <td>{{$ar->nombreArchivo}}</td>
                              <td>
                                  <a href="{{route('ver.documento',['urlDocumento' => Crypt::encrypt($ar->urlArchivo),'tipoArchivo'=>  Crypt::encrypt($ar->tipoArchivo)])}}" class="btn btn-xs btn btn-primary btn-perspective" target="_blank"><i class="fa  fa-location-arrow"></i> Ver documento 
                                    </a>
                                </td>
                                 </tr>
                                @endforeach
                          </tbody>
                          </table>
                          </div><!-- /.table-responsive -->
                    @endif
                    @endif