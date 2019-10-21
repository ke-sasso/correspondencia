
<form role="form" method="post" @if($info->idEstado==1 || $info->idEstado == 2) action="{{route('guardar.detalle.soli')}}"  @else action="{{route('post.guardar.participantes')}}" @endif autocomplete="off" id="formDetalle">
                 
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Clasificacion</b></div>
                        @if($info->idEstado==1 || $info->idEstado == 2)
                         <select name="idClasificacion" id="idClasificacion" class="form-control" onchange="SelectChanged2();">
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
                        @else
                        <select name="idClasificacion" id="idClasificacion" class="form-control" disabled>
                             
                             @foreach($listClasificacion as $cla)
                             @if($info->idClasificacion == $cla->idClasificacion)
                             <option value="{{$cla->idClasificacion}}" selected=""> {{$cla->nombreClasificacion}}</option>
                             @else
                              <option value="{{$cla->idClasificacion}}"> {{$cla->nombreClasificacion}}</option>
                             @endif
                             @endforeach


                        </select>
                       

                        @endif
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
                        <select name="idFechaResp" id="idFechaResp" class="form-control"  onchange="SelectChanged();">
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
                        @if($info->idEstado==1 || $info->idEstado == 2)
                        <input type="number" step="1"  name="dias" id="dias" min="0" max="5" placeholder="Ingrese el número de días" value="{{$info->dias}}" class="form-control">
                        @else
                       <input type="text" name="aaa" value="{{$info->dias}}" class="form-control">
                        @endif
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
                        <input type=checkbox value="{{$acc->idAccion}}"  name="idAccion[]" id="idAccion"  class="ckb-check" checked> {{$acc->nombreAccion}}</input>
                        <br>
                        @else
                         <input type=checkbox value="{{$acc->idAccion}}" name="idAccion[]" id="idAccion"  class="ckb-check"> {{$acc->nombreAccion}}</input>
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
                    <textarea name="comentario" id="comentario" class="summernote-sm">{{$info->comentario}}</textarea>
                    </div>
                    </div>
                    </div>
      

                
                    @if(in_array(477, $permisos, true))
                    @if($info->idEstado==1 || $info->idEstado == 2 || $info->idEstado == 3)
                      <div class="from-group" align="center">
                     <input type="hidden" name="idSoliDetalle" value="{{$info->idSolicitud}}" />
                       <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                      <button type="submit" class="btn btn-primary btn-perspective">Guardar</button>
                      </div>
                    @endif
                    @endif         
</form>
