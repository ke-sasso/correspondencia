<?php
  $permisos = App\UserOptions::getAutUserOptions();
  $estadoPart = App\Models\Solicitud\Participantes::getEstado($info->idSolicitud);
  $casoPart = App\Models\Solicitud\Participantes::getCaso($info->idSolicitud);
  $permisoPart = App\Models\Solicitud\Participantes::getPermiso($info->idSolicitud);
  $comentariosDestino =  App\Models\Solicitud\ComentarioDestino::getComentariosDest($info->idSolicitud);

?>

<div class="the-box no-border">
@if(!empty($estadoPart))
@if($estadoPart[0]!=2)
<!-- -------------------------------- COMENTARIOS ASESOR JURIDICO ---------------------------------------- -->
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
@else
<!-- -------------------------------- COMENTARIOS PARA COLABORADOR ---------------------------------------- -->
                   <div class="row">
                   <div class="col-md-1"></div>
                   <div class="col-md-10">
                    <h4 class="small-heading more-margin-bottom">@if($info->comentario!='') <?php $numCom=$numCom+1; echo $numCom;?> @else {{$numCom}} @endif COMENTARIOS </h4>
                     @if($info->comentario!='')
                 <div class="" >
                  <ul class="media-list media-xs media-dotted media-chat"  id="list" name="list">
                 <li class="media"><a class="pull-left"><img class="media-object img-circle" src="{{ asset('img/avatar/default_avatar_male.jpg') }}" alt="Avatar"></a>
                   <div class=""><p class="name"><small>Archivo oficial de información</small></p> <p class="small"><?php echo $info->comentario;?></p>
                  </div></li>
                  </ul>
                  </div>
                 @endif
                    @if($numCom!=0)
                  <div class="" >
                  <ul class="media-list media-xs media-dotted media-chat"  id="list" name="list">
            <?php //VAMOS A TRAER LOS COMENTARIOS QUE ESTAN EN COMENTARIO DESTINO
                  $comentarioss=App\Models\Solicitud\Comentarios::listComentariosColaboradores($info->idSolicitud);
            

            ?>
            @foreach($comentarioss as $com)

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
                             @if($bc->idEmpleado==Auth::user()->idEmpleado)

                                    @if(in_array(471, $permisos, true) || in_array(472, $permisos, true))
                                    @if($info->idEstado == 3 || $info->idEstado == 13 || $info->idEstado == 7)
                                    @if(!empty($estadoPart) && !empty($casoPart))            
                                    @if($estadoPart[0]==2 && $casoPart[0]==0)
               
                <div class="action-chat">
                 <form id="comentarioColaborado-{{$ban1}}" action="{{route('guardar.denuncia.colaborador')}}" method="post" enctype="multipart/form-data">
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
                      <input type="hidden" name="tipo" value="{{$permisoPart[0]}}">
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




@endif <!--CIERRE DE IF PARA VALIDAR SI ES COLABORADOR -->
@endif



  <!--INGRESAR COMENTARIOS  -->
                <div class="row"> 
                <div class="col-md-2"></div>
                <div class="col-md-8">

      @if(in_array(471, $permisos, true) || in_array(472, $permisos, true))
      @if($info->idEstado == 1 || $info->idEstado == 3 || $info->idEstado == 13 || $info->idEstado == 15)
      @if(!empty($estadoPart) && !empty($casoPart))
                  

     <!--COMENTARIO ASESOR  -->
 @if($estadoPart[0]==3 || $estadoPart[0]==4)
 @if(in_array(0, $casoPart))
 @if($info->idEstado == 3 || $info->idEstado == 13 || $info->idEstado == 15)
                <div class="action-chat">
                <form id="comentarioAsesor" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-sm-11"  id="txtComen" style="display: none;">
                        <div class="form-group">
                        <textarea name="comentarioAsesor" id="comentarioAsesor" class="summernote-sm"></textarea> </div>
                    </div>
                   </div>


                <div class="row">
                   <div class="col-sm-4">
                             <div class="form-group" id="btnArchivo" style="display: none;">
                             <div>
                             <input type="file" id="fileAsesor" name="fileAsesor" class="file-loading btn btn-primary btn-perspective"/>
                             <div id="errorBlockAsesor" class="help-blockAsesor"></div>
                             </div>
                            </div>

                             <input type="hidden" name="idSoli" value="{{$info->idSolicitud}}">
                             <input type="hidden" name="idTipo" id="idTipo" value="">
                    </div><!-- /.col-xs-8 -->
                   <div class="col-sm-8">
                        <div class="form-group"  id="btnFormulario" style="display: none;">
                             <button type="submit" class="btn btn-success btn-perspective"><i class="fa   fa-check-square-o"></i>Enviar</button>
                    </div>

                        <div class="form-group"  id="btnPrincipales">
                       @if($info->idEstado == 15)
                       <a class="btn btn-success btn-perspective" onclick="ObservarSolicitud();" ><i class="fa fa-check-square-o"></i>CERRAR CASO (ARCHIVO)</a>&nbsp; <a class="btn btn-success btn-perspective" onclick="ObservarSEIPS();" ><i class="fa fa-check-square-o"></i>SEIPS</a>
                       @endif
                       @if($info->idEstado == 3)
                        <a class="btn btn-success btn-perspective"  href="{{route('procede.denuncia',['idSolicitud'=>Crypt::encrypt($info->idSolicitud),'formulario'=>$formulario,'tipoProcede'=>1])}}" ><i class="fa fa-check-square-o"></i>PROCEDE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>    &nbsp;
                       <a class="btn btn-success btn-perspective" onclick="NoSolicitud();" ><i class="fa fa-check-square-o"></i>NO PROCEDE</a>
                       </div>
                       @endif
        
                  </div>
                  </div>


                  </form>
                </div>
      @endif <!-- DEL ASESOR -->
      @endif <!-- DEL ASESOR -->
      @endif  <!-- in_array estadoPart -->

        <!--COMENTARIO PRIMER COLABORADOR -->
 @if($estadoPart[0]==1)
 @if(in_array(0, $casoPart))
 @if($info->idEstado == 1 || $info->idEstado == 13)
                <div class="action-chat">
                <form id="finalizarColaborador" method="post" enctype="multipart/form-data" action="{{route('finalizar.colaborador')}}">
                  <div class="row">
                    <div class="col-sm-11" id="txtComen2">
                        <div class="form-group">
                        <textarea name="comentarioFinalizar" id="comentarioFinalizar" class="summernote-sm"></textarea> </div>
                    </div>
                   </div>


                <div class="row">
                   <div class="col-sm-4">
                             <div class="form-group" id="btnArchivo2" >
                             <div>
                             <input type="file" id="fileFinalizar" name="fileFinalizar" class="file-loading btn btn-primary btn-perspective"/>
                             <div id="errorBlockAsesor" class="help-blockAsesor"></div>
                             </div>
                            </div>

                             <input type="hidden" name="idSoli" value="{{$info->idSolicitud}}">
                             <input type="hidden" name="idTipo" id="idTipo" value="">
                    </div><!-- /.col-xs-8 -->
                   <div class="col-sm-8">

                    <div class="form-group"  id="btnFormulario2">
                        <button type="submit" class="btn btn-success btn-perspective"><i class="fa   fa-check-square-o"></i>Finalizar</button>&nbsp;&nbsp;
                        <a class="btn btn-success btn-perspective"  href="{{route('procede.denuncia',['idSolicitud'=>Crypt::encrypt($info->idSolicitud),'formulario'=>$formulario,'tipoProcede'=>2])}}" ><i class="fa fa-check-square-o"></i>ASISTENCIA</a>
                    </div>
                      
        
                  </div>
                  </div>


                  </form>
                </div>
      @endif <!-- DEL COLABORADOR -->
      @endif <!-- DEL COLABORADOR -->
      @endif  <!-- in_array estadoPart -->


@endif <!-- DEL EMPTY -->
@endif  <!-- DEL estado -->
@endif<!-- DEL PERMISO -->

                </div><div class="col-md-2"></div></div>
              </div><!-- /.the-box .no-border -->