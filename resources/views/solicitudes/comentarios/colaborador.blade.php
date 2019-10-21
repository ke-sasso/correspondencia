    <div class="row">
                   <div class="col-md-1"></div>
                   <div class="col-md-10">
                    <h4 class="small-heading more-margin-bottom">{{$numCom}} COMENTARIOS </h4>
                    @if($numCom!=0)
                  <div class="" >
                  <ul class="media-list media-xs media-dotted media-chat"  id="list" name="list">
            <?php //VAMOS A TRAER LOS COMENTARIOS QUE ESTAN EN COMENTARIO DESTINO
                  $comentarios=App\Models\Solicitud\Comentarios::listComentariosColaboradores($info->idSolicitud);
            ?>
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
                          <?php $ban1=0;?>
                          @foreach($lpart2 as $bc)
                          <?php $ban1=$ban1+1;?>
                             @if($bc->idEmpleado==Auth::user()->idEmpleado || $bc->idEmpleadoAsistente==Auth::user()->idEmpleado)

                                    @if(in_array(471, $permisos, true) || in_array(497, $permisos, true))
                                    @if($info->idEstado == 2 || $info->idEstado == 3)
                                    @if(!empty($estadoPart) && !empty($casoPart))            
                                    @if($estadoPart[0]==2 && $casoPart[0]==0)
               
                <div class="action-chat">
                 <form id="comentarioColaborado-{{$ban1}}" action="{{route('guardar.comentario.colaborador')}}" method="post" enctype="multipart/form-data">
                  <div class="row">
                  <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <div class="form-group">
                        <textarea required name="comentarioColaborador" id="comentarioColaborador" class="summernote-sm"></textarea> </div>
                    </div>
                    </div>


                     <div class="row">
                     <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <div class="row">
                        <div class="col-sm-8">
                        <div class="form-group">
                       <div>
                         <input type="file" id="fileColaborador" name="fileColaborador" class="form-control"/>
                      </div>
                      <input type="hidden" name="idSoli" value="{{$info->idSolicitud}}">
                       <input type="hidden" name="idPadre" value="{{$com->idComentario}}">
                     {{--  <input type="hidden" name="tipo" value="{{$permisoPart[0]}}"> --}}
                       <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                       </div><!-- /.col-xs-8 -->
                       </div>
                       <div class="col-sm-4">
                      <div class="form-group">
                         <button type="submit" class="btn btn-primary"><i class="fa   fa-check-square-o"></i>RESPONDER</button>
                      </div> 
                      </div>
                      </div>
                    </div>
                    </div>
                  </form>
                </div>
                @endif<!-- fin de estado y caso-->
                 @endif
                  @endif
                  @endif
                             @endif <!-- validar si es el usuario logueado -->
                          @endforeach

                  @endif<!-- si existe en el arreglo in_array -->
              @endif<!-- comentario destino -->

          @endforeach                   
                    
                  </ul>
                </div><!-- /.chat-wrap -->
              @endif
              </div><div class="col-md-1"></div>
                </div>