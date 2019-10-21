<?php
  $permisos = App\UserOptions::getAutUserOptions();
  $estadoPart = App\Models\Solicitud\Participantes::getEstado($info->idSolicitud);
  $casoPart = App\Models\Solicitud\Participantes::getCaso($info->idSolicitud);
  $permisoPart = App\Models\Solicitud\Participantes::getPermiso($info->idSolicitud);
  $comentariosDestino =  App\Models\Solicitud\ComentarioDestino::getComentariosDest($info->idSolicitud);

?>
<div class="row">
                   <div class="col-md-1"></div>
                   <div class="col-md-10">
                    <h4 class="small-heading more-margin-bottom">@if($info->comentario!='') <?php $numCom=$numCom+1; echo $numCom;?> @else {{$numCom}} @endif COMENTARIOS </h4>
                 @if($info->comentario!='')
                 <div class="" >
                  <ul class="media-list media-xs media-dotted media-chat"  id="list" name="list">
                 <li class="media"><a class="pull-left"><img class="media-object img-circle" src="{{ asset('img/avatar/default_avatar_male.jpg') }}" alt="Avatar"></a>
                   <div class=""><p class="name"><small>{{$info->usuarioEnviarJunta}}</small></p> <p class="small"><?php echo $info->comentario;?></p>
                  </div></li>
                  </ul>
                  </div>
                 @endif

                    @if($numCom!=0)
                  <div class="" >
                  <ul class="media-list media-xs media-dotted media-chat"  id="list" name="list">

            @foreach($comentarios as $com)
                   <li class="media"><a class="pull-left"><img class="media-object img-circle" src="{{ asset('img/avatar/default_avatar_male.jpg') }}" alt="Avatar"></a>
                   <div class=""><p class="name"><small>{{$com->nombresEmpleado}} {{$com->apellidosEmpleado}}</small></p> <p class="small"><?php echo $com->comentario; ?></p>

                     @if($com->archivo!=null || $com->archivo!='')
                          <p class="comment-action">
                          <a target="_blank"  title="Descargar Archivo" class="btn btn-xs btn-default btn-square" href="{{route('ver.documento',['urlDocumento' => Crypt::encrypt($com->archivo),'tipoArchivo'=>  Crypt::encrypt($com->tipoArchivo)])}}" ><i class="fa fa-download"></i>DESCARGAR ARCHIVO</a>
                          </p>
                     @endif
                    <p class="text-right" >{{$com->fechaCreacion}}</p></div></li>

              @if(!empty($comentariosDestino))
                  @if(in_array($com->idComentario, $comentariosDestino, true))
                                <?php
                                //consultamos la lista de participantes del comentario
                          $lpart= App\Models\Solicitud\ComentarioDestino::getPartDestino($com->idComentario);
                              //enviamo el array con los idParticipantes
                          $lpart2= App\Models\Solicitud\Comentarios::comentarioParticipante2($lpart);
                                 ?>
                          <b>Para:</b>
                          @foreach($lpart2 as $bc)
                            <span class="label label-success">{{$bc->nombresEmpleado}}  {{$bc->apellidosEmpleado}}</span>
                          @endforeach

                          <?php 
                           $comentariosHijos= App\Models\Solicitud\Comentarios::comentariosHijos($com->idComentario);
                          ?>
                      @if(!empty($comentariosHijos))
                          @foreach($comentariosHijos as $comhijos)
                          <ul class="media-list">
                      <li class="media">
                      <a class="pull-left" href="#fakelink">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </a>
                      <div class="media-body">
                        <h4 class="media-heading"><a href="#fakelink">{{$comhijos->nombresEmpleado}} {{$comhijos->apellidosEmpleado}}</a></h4>
                        <p class="date"><small>{{$comhijos->fechaCreacion}}</small></p>
                         <?php echo $comhijos->comentario; ?>
                          @if($comhijos->archivo!=null || $comhijos->archivo!='')
                          <p class="comment-action">
                          <a target="_blank"  title="Descargar Archivo" class="btn btn-xs btn-default btn-square" href="{{route('ver.documento',['urlDocumento' => Crypt::encrypt($comhijos->archivo),'tipoArchivo'=>  Crypt::encrypt($comhijos->tipoArchivo)])}}" ><i class="fa fa-download"></i>DESCARGAR ARCHIVO</a>
                          </p>
                     @endif
                      </div>
                      </li>
                    </ul>@endforeach
                    @endif<!--CIERRE DE EMPTY($COMENTARIOS HIJOS)-->

                  @endif<!-- si existe en el arreglo in_array -->
              @endif<!-- comentario destino -->

          @endforeach                   
                    
                  </ul>
                </div><!-- /.chat-wrap -->
              @endif
              </div><div class="col-md-1"></div>
              </div>