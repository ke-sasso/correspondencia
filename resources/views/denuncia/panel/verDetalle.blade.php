 @if(!empty($establecimientos))
                  @if(count($establecimientos)>=1)
                  <div class="table-responsive">
                          <table class="table table-th-block table-success table-hover" id="dt-solicitudes" style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Nombre del establecimiento</th>
                              <th>Dirección</th>
                
                            </tr>
                          </thead>
                          <tbody>
                                @foreach($establecimientos as $est)
                                <tr>
                               <td> {{$est->nombreComercial}}</td>  
                               <td>{{$est->direccion}}</td>

                                 </tr>
                                @endforeach
                          </tbody>
                          </table>
                          </div><!-- /.table-responsive -->
 @endif
 @endif

  @if(!empty($productos))
                  @if(count($productos)>=1)
                  <div class="table-responsive">
                          <table class="table table-th-block table-success table-hover" id="dt-solicitudes" style="font-size:14px;" width="100%">
                         <thead class="the-box dark full">
                             <tr>
                              <th>Nombre del productos</th>
                              <th>Propietario</th>
                              <th>Fecha vencimiento</th>
                              <th>No. Lote</th>
                
                            </tr>
                          </thead>
                          <tbody>
                                @foreach($productos as $pro)
                                <tr>
                               <td> {{$pro->nombreComercial}}</td>  
                               <td>{{$pro->titular}}</td>
                               <td>{{$pro->fechaVencimiento}}</td>
                               <td>{{$pro->noLote}}</td>

                                 </tr>
                                @endforeach
                          </tbody>
                          </table>
                          </div><!-- /.table-responsive -->
 @endif
 @endif