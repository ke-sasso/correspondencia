  @if(!is_null($observacion))
                          <div class="alert alert-success fade in alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                           @foreach($observacion as $a)
                         <strong>{{$a->observacion}}</strong>
                         <br>
                          @endforeach
                         </div>
                          @else
                          <div class="alert alert-warning fade in alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                         <strong>La solicitud por el momento no esta aprobada</strong>
                         </div>

                          @endif
                          @if($comEstado!=0)
                          <table class="table table-th-block table-success table-hover"  style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Comentario</th>
                              <th>Archivo</th>
                            </tr>
                          </thead>
                          <tbody>
                               
                            <tr>  
                              <td><?php echo $obsSoli->comentario; ?></td>
                              <td>
                              @if($obsSoli->archivo=='' || $obsSoli->archivo==NULL)
                                <span class="alert-danger">NO HAY ARCHIVO</span>
                              @else
                                  <a href="{{route('ver.documento',['urlDocumento' => Crypt::encrypt($obsSoli->archivo),'tipoArchivo'=>  Crypt::encrypt($obsSoli->tipoArchivo)])}}" class="btn btn-xs btn btn-primary btn-perspective" target="_blank"><i class="fa  fa-location-arrow"></i> Ver documento 
                                    </a>
                              @endif
                                </td>
                            </tr>
                          
                          </tbody>
                          </table>

                          @endif