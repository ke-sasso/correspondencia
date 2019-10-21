
                 
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Clasificacion</b></div>
                       <select name="idClasificacion" id="idClasificacion" class="form-control" disabled>
                             @if($info->idClasificacion=='')

                             <option value="" selected="">Seleccione una clasificación...</option>
                             @foreach($listClasificacion as $cla)
                             <option value="{{$cla->idClasificacion}}"> {{$cla->nombreClasificacion}}</option>
                             @endforeach

                             @else

                             @foreach($listClasificacion as $cla)
                             @if($info->idClasificacion == $cla->idClasificacion)
                             <option value="{{$cla->idClasificacion}}" selected=""> {{$cla->nombreClasificacion}}</option>
                             @else
                              <option value="{{$cla->idClasificacion}}"> {{$cla->nombreClasificacion}}</option>
                             @endif
                             @endforeach

                             @endif
                        </select>
                    </div>
                    </div>
                    </div>
                </div>
<div id="valClasificacion">
    

                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Fecha respuesta</b></div>
                        <select name="idFechaResp" id="idFechaResp" class="form-control"  disabled>
                        @if($info->idfechaRespuesta=='')
                        
                            <option value="" selected="">Seleccione una fecha de respuesta...</option>
                            @foreach($listFecha as $fech)
                             <option value="{{$fech->idfechaRespuesta}}"> {{$fech->nombreFecha}}</option>
                             @endforeach
                        @else
                             @foreach($listFecha as $fech)
                            @if($info->idfechaRespuesta==$fech->idfechaRespuesta)
                             <option value="{{$fech->idfechaRespuesta}}" selected=""> {{$fech->nombreFecha}}</option>
                             @else
                              <option value="{{$fech->idfechaRespuesta}}"> {{$fech->nombreFecha}}</option>
                             @endif
                             @endforeach

                        @endif
                        </select>

                        <div id="divDias">
                        <input type="number" step="1"  name="dias" id="dias" min="0" max="5" placeholder="Ingrese el número de días" value="{{$info->dias}}" class="form-control">
                        </div>
                
                        
                    </div>
                    </div>
                    </div>
                </div>
                 <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Accion</b></div>
                        @foreach($listAcciones as $acc)
                        @if(in_array($acc->idAccion, $accSolicitud, false))
                        <input type=checkbox value="{{$acc->idAccion}}" name="idAccion[]" id="idAccion"  class="ckb-check" checked disabled> {{$acc->nombreAccion}}</input>
                        <br>
                        @else
                         <input type=checkbox value="{{$acc->idAccion}}" name="idAccion[]" id="idAccion"  class="ckb-check" disabled> {{$acc->nombreAccion}}</input>
                        <br>
                        @endif
                        @endforeach
                        
                    </div>
                    </div>
                    </div>
                </div>

    </div>
                 @include('solicitudes.paneles.verParticipantes')
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                                         <div class="input-group ">
                <div class="input-group-addon"><b>Observación</b></div></div>
                    <textarea name="comentario" id="comentario" class="form-control"  disabled><?php echo strip_tags($info->comentario);?></textarea>
                    </div>
                    </div>
                    </div>
      



