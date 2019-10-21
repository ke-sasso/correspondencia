  <div class="panel panel-primary" id="cont-tit">
@if(in_array(477, $permisos, true))
                         @if($info->idEstado ==1 || $info->idEstado == 2 || $info->idEstado == 3)
                         
                      <div class="panel-heading">
                          <div class="right-content">
                          <a onclick="asignarParticipantes({{$info->idSolicitud}});"  style="" type="button" id="cancelar" class="btn btn-primary m-t-10"><i class="fa fa-plus" aria-hidden="true"></i>Responsable</a>
                          </div>
                          <h3 class="panel-title">RESPONSABLES</h3>
                        </div>
                        <div class="panel-body">
                          <div class="table-responsive">
                       
                                 <table class="table table-hover" id="dt-Tit">
                                  <thead>
                                    <tr>
                                    <th>Nombre responsable</th>
                                    <th>- </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      </tbody>
                                </table>
                                
                                </div><!-- /.table-responsive -->
                        </div>
                        @else
                        <div class="panel-heading">
                         <h3 class="panel-title">RESPONSABLES</h3>
                        </div>
                    
                             @endif
@else
 <div class="panel-heading">
                <h3 class="panel-title">RESPONSABLES</h3>
  </div>

@endif 
<div class="panel-body">
                           <div class="table-responsive">
                          <table class="table table-th-block table-success table-hover" id="dt-solicitudes" style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Cód Empleado</th>
                              <th>Nombre Empleado</th>
                              <th>Unidad</th>
                              <th>Fecha Asignación</th>
                               @if(in_array(477, $permisos, true))
                               @if($info->idEstado==1 || $info->idEstado == 2 ||  $info->idEstado == 3 ||  $info->idEstado == 17)
                               <th>-</th>
                               @endif
                               @endif
                               @if(in_array(477, $permisos, true) || in_array(479, $permisos, true))
                              
                               <th>-</th>
                      
                               @endif
                            </tr>
                          </thead>
                          <tbody>
                     @foreach($part as $p)
                            <tr>
                               <td>{{$p->idEmp}} </td>
                               <td>{{$p->nombresEmpleado}} {{$p->apellidosEmpleado}}</td>
                               <td>{{$p->nombreUnidad}} ({{$p->prefijo}})</td>
                               <td>{{$p->fechaCreacion}}</td>
                               
                                    @if(in_array(477, $permisos, true))
                                     @if($info->idEstado==1 || $info->idEstado == 2 || $info->idEstado == 3 || $info->idEstado == 17)
                                                @if($p->idEmp==7)
                                                      <td></td>
                                                   @else
                                                   <td><a class="btn btn-xs btn-danger btn-perspective" onclick="elminarParticipantes({{$p->idParticipante}},{{$info->idSolicitud}});" ><i class="fa fa-trash-o"></i></a></td>
                                            
                                                  @endif
                                      @endif
                                      @endif

                                       @if(in_array(477, $permisos, true))
                                           
                                                 @if($p->caso==1)
                                                 <td> <a class="btn btn-xs btn-success btn-perspective" onclick="habilitarCaso({{$p->idParticipante}},{{$info->idSolicitud}});" ><i class="fa fa-check"></i>Abrir Caso</a></td>
                                                 @else
                                                 <td><span class="badge badge-success icon-count">&nbsp;&nbsp;</span></td>
                                                
                                                   @endif
                                          
                                       @endif
                            </tr>
                    @endforeach
                               
                          </tbody>
                          </table>
                          </div><!-- /.table-responsive -->
                          </div>
                          </div>