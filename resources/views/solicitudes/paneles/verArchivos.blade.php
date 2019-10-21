<div class="table-responsive">
                          <table class="table table-th-block table-success table-hover" id="dt-solicitudes" style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Tipo de documento</th>
                              <th>Nombre del archivo</th>
                              <th>-</th>
                            </tr>
                          </thead>
                          <tbody>
                                @foreach($archivos as $ar)
                                @if($ar->idEstado=='')
                            <tr>
                               <td> 
                              @if(trim($ar->tipoArchivo)==='application/pdf')
                               <center><i class="fa fa-file-o" style="font-size:25px;"></i></center>
                                @else
                               <center><i class="fa fa-picture-o" style="font-size:25px;"></i></center>
                              @endif
                              </td>  
                              <td>{{$ar->nombreArchivo}}</td>
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
