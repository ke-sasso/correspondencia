 <div class="form-group">
                 <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha del evento reportado</b></div>
                      <?php $date = date_create($detalle->fechaEvento); ?>
                  @if($detalle->fechaEvento!='1900-01-01')
                      <input type="text" class="form-control datepicker date_masking" id="fechaEvento" name="fechaEvento"  autocomplete="off" placeholder="dd-mm-yyyy. Ejemplo(31-12-2017)" value="{{date_format($date, 'd-m-Y')}}" disabled>
                 @else
                      <input type="text" class="form-control datepicker date_masking" id="fechaEvento" name="fechaEvento"  autocomplete="off" placeholder="dd-mm-yyyy. Ejemplo(31-12-2017)" value="" disabled>
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
                   <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group">
                      <div class="input-group-addon"><b>Asunto</b></div>
                      <input type="text" class="form-control" id="asunto" name="asunto" value="{{$info->asunto}}" disabled>
                    </div>
                    </div>
                    </div>
              </div>
             
      
      <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                    
                    <div class="input-group-addon"><b>Descripción</b></div>
                          <div class="panel panel-square panel-default">
                          <div class="panel-heading">
                          <?php echo $info->descripcion;?> 
                          </div>
                           </div>                                  
                    </div>
                    </div>
      </div>



                @include('denuncia.panel.info2')
        
 

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