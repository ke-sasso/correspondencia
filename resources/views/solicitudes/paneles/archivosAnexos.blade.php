
<div class="table-responsive">
                          <table class="table table-th-block table-success table-hover" id="dt-anexos-arch" style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Anexos</th>
                              <th>Nombre del archivo</th>
                              <th>Usuario</th>
                              <th>-</th>
                            </tr>
                          </thead>
                          <tbody>
                                @foreach($archivos as $ar)
                                @if($ar->idEstado==2)
                            <tr>
                               <td> 
                              @if(trim($ar->tipoArchivo)==='application/pdf')
                               <center><i class="fa fa-file-o" style="font-size:25px;"></i></center>
                                @else
                               <center><i class="fa fa-picture-o" style="font-size:25px;"></i></center>
                              @endif
                              </td>  
                              <td>{{$ar->nombreArchivo}}</td>
                              <td>{{$ar->usuarioCreacion }}</td>
                              <td>
                                  <a href="{{route('ver.documento',['urlDocumento' => Crypt::encrypt($ar->urlArchivo),'tipoArchivo'=>  Crypt::encrypt($ar->tipoArchivo)])}}" class="btn btn-xs btn btn-primary btn-perspective" target="_blank"><i class="fa  fa-location-arrow"></i> Ver documento 
                                    </a>
                                </td>
                                 </tr>
                                 @endif
                                @endforeach
                          </tbody>
                          </table>
  </div><!-- /.table-responsive -->

  @if(in_array(471, $permisos, true) || in_array(497, $permisos, true))
<br><br>
   <form id="archivosAnexos" method="post" enctype="multipart/form-data" action="{{ route('guardar.archivos.anexos') }}">
  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-8 col-md-8">
                     <div class="input-group">
                       <div class="input-group-addon"><b>Documentos/Anexos</b></div>
                       <input id="fileAnexos" name="fileAnexos[]" required type="file" multiple class="file-loading"> 
                      
                       </div>
                     <div id="errorBlockNew" class="help-block"></div>
                    </div>
                      <div class="col-sm-4 col-md-4">
                       <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="idSoli" value="{{$info->idSolicitud}}">
                <button  type="submit" class="btn btn-primary btn-perspective">Enviar anexos<i class="fa fa-check"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                    </div>
                    </div>

                  
  </div>
  </form>

@if($info->idEstado == 2 || $info->idEstado == 3 || $info->idEstado == 11)

<!--
   <form id="archivosUsuarioFinal" method="post" enctype="multipart/form-data" action="{{ route('guardar.archivos.respuestaFinal') }}">
   <div class="form-group">
   <div class="row">
                    <div class="col-sm-8 col-md-8">
                     <div class="input-group">
                       <div class="input-group-addon"><b>Archivo de respuesta final al usuario</b></div>
                     <input id="fileRespuesta" name="fileRespuesta" required type="file" class="file-loading"> 
                      
                       </div>
                     <div id="errorBlockNew" class="help-block"></div>
                    </div>
                      <div class="col-sm-4 col-md-4">
                       <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="idSoli" value="{{$info->idSolicitud}}">
                <button type="submit" class="btn btn-primary btn-perspective">Enviar respuesta<i class="fa fa-check"></i></button>
                    </div>
                    </div>

                  
  </div>
  </form>

-->
@endif

@endif



