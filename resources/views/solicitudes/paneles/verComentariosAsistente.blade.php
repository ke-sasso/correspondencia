<div class="the-box no-border">
                   <div class="row">
                   <div class="col-md-1"></div>
                   <div class="col-md-10">
                    <h4 class="small-heading more-margin-bottom">{{$numCom}} COMENTARIOS </h4>
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

              @if($info->idEstado == 7 || $info->idEstado == 8)
                  <div id="buttnAfirmar">
                   <center><button type="button" onclick="(revisionFir(1))" class="btn btn-success">Aprobada</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-danger" onclick="revisionFir(2)">Observar</button></center>
                   </div>


            <div class="panel panel-success" style="display: none;" id="aprobarFirmar">
                 <form id="FirmarRevision" method="post" enctype="multipart/form-data" action="{{ route('guardar.firmarevision.asistente') }}">
                <div class="panel-heading">
                  
                <h3 class="panel-title">REVISIÓN</h3>
                </div>
                <div class="panel-body">
                     <div class="input-group">
                       <div class="input-group-addon">Observaciones</div>
                       <textarea name="txtobsAfirmar" id="txtobsAfirmar" class="form-control" rows="3"></textarea>
                     </div>
             <input id="fileAprobar" name="fileAprobar" type="file"  class="file-loading"> 
             <input type="hidden" name="tipoRevision" id="tipoRevision">
             <input type="hidden" name="ids" id="ids" value="{{$info->idSolicitud}}">
            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" class="form-control"/>
                </div><!-- /.panel-body -->
                
                <div class="panel-footer">
                  <center>  <button type="submit" class="btn btn-primary btn-perspective">GUARDAR <i class="fa fa-check"></i></button></center>
                </div>
                  </form>
            </div><!-- /.panel panel-success -->
                @endif <!-- DEL ASESOR -->
</div>