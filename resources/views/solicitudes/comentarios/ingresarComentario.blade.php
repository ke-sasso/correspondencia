<!--INGRESAR COMENTARIOS  -->
                <div class="row"> 
                <div class="col-md-2"></div>
                <div class="col-md-8">

                @if(in_array(471, $permisos, true) || in_array(497, $permisos, true))
                @if($info->idEstado == 2 || $info->idEstado == 3 || $info->idEstado == 11)
               
 <!--COMENTARIO RESPONSABLE  -->
        
                <div class="action-chat" id="responsableComen" style="display: none;">
                 <form id="comentarioResponsable" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-sm-10">
                        <div class="form-group">
                        <textarea name="comentarioResponsable" id="comentarioResponsable" class="summernote-sm"></textarea>                                        
                        </div>
                    </div>
                    <div class="col-sm-2">
              
                    </div>
                    </div>
                     <div class="row">
                     <div class="col-sm-6">
                      <div class="form-group">
                       <div class="input-group-addon"><b>Subir archivo</b></div>
                       <div>
                         <input type="file" id="file" name="file" class="file-loading btn btn-primary btn-perspective"/>
                             <div id="errorBlock" class="help-block"></div>
                             </div>
                      </div>
                      <input type="hidden" name="idSoli" value="{{$info->idSolicitud}}">
                      <input type="hidden" name="tipoUsu" value="0">
                      <input type="hidden" name="tipoComen" id="tipoComen">
                    </div><!-- /.col-xs-8 -->
                    <div class="col-sm-6">
                      <div class="form-group">
                         <button type="submit" class="btn btn-primary"><i class="fa   fa-check-square-o"></i>Enviar</button>
                      </div>
        
                    </div>
                    </div>
                  </form>
                </div>
                <div class="form-group" id="buttonResponsable">
                <a class="btn btn-success btn-perspective"  href="{{route('cometarios.asistencia',['idSolicitud'=>Crypt::encrypt($info->idSolicitud)])}}" ><i class="fa fa-check-square-o"></i>Asistencia</a>&nbsp; <a class="btn  btn-success btn-perspective"  href="{{route('comentario.opinion',['idSolicitud'=>Crypt::encrypt($info->idSolicitud)])}}" ><i class="fa fa-check-square-o"></i>Opinión&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;<a class="btn btn-success btn-perspective" onclick="resComentar(1);" ><i class="fa fa-check-square-o"></i>COMENTAR</a>&nbsp;<a class="btn btn-success btn-perspective" onclick="resComentar(2);" ><i class="fa fa-check-square-o"></i>CERRAR CASO</a>
                  </div>
               
      @endif
      @endif
   <!--- -->
                </div><div class="col-md-2"></div></div>
              </div><!-- /.the-box .no-border -->