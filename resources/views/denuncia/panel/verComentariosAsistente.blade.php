<div class="the-box no-border">
                   <div class="row">
                   <div class="col-md-1"></div>
                   <div class="col-md-10">
                    <h4 class="small-heading more-margin-bottom"> @if($info->comentario!='') <?php $numCom=$numCom+1; echo $numCom;?> @else {{$numCom}} @endif COMENTARIOS </h4>
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

             @endforeach
             @endif
             </ul>


              </div><!-- /.the-box .no-border -->
              </div>
              </div>
</div>