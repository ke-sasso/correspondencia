 <div class="action-chat">
                 <form id="comentarioAsesor" method="post" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-sm-11">
                        <div class="form-group">
                        <textarea name="comentarioAsesor" id="comentarioAsesor" class="summernote-sm"></textarea> </div>
                    </div>
              @if(in_array(1, $estadoPart))
                 <div class="col-sm-1">
                        <div class="form-group">
                               <a class="btn btn-xs btn-success btn-perspective"  href="{{route('cometarios.asistencia',['idSolicitud'=>Crypt::encrypt($info->idSolicitud)])}}" ><i class="fa fa-check-square-o"></i>Asistencia</a>  <br><br>
                      
                      <a class="btn btn-xs btn-success btn-perspective"  href="{{route('comentario.opinion',['idSolicitud'=>Crypt::encrypt($info->idSolicitud)])}}" ><i class="fa fa-check-square-o"></i>Opinión&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>                                        
                        </div>
                    </div>
                @endif

                   </div>
                     <div class="row">
                     <div class="col-sm-4">
                      <div class="form-group">
                       <div>
                         <input type="file" id="fileAsesor" name="fileAsesor" class="file-loading btn btn-primary btn-perspective"  accept="image/*,application/pdf"/>
                             <div id="errorBlockAsesor" class="help-blockAsesor"></div>
                             </div>
                      </div>

                      <input type="hidden" name="idSoli" value="{{$info->idSolicitud}}">
                    </div><!-- /.col-xs-8 -->
                    <div class="col-sm-8">
                        <div class="form-group">
                       <button type="submit" class="btn btn-success btn-perspective"><i class="fa   fa-check-square-o"></i>OBSERVAR</button>&nbsp;<a class="btn btn-success btn-perspective" onclick="favorableSolicitud({{$info->idSolicitud}});" ><i class="fa fa-check-square-o"></i>FAVORABLE</a>&nbsp;<a class="btn btn-success btn-perspective" onclick="NoSolicitud({{$info->idSolicitud}});" ><i class="fa fa-check-square-o"></i>NO APLICA</a>
                </div>
        
                    </div>
                    </div>
                  </form>
</div>