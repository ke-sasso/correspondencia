
              <div class="panel panel-primary" id="cont-tit">
                      <div class="panel-heading">
                          <div class="right-content">
                          <button  type="button" class="btn btn-primary" id="btnBuscarEstablecimiento"><i class="fa fa-plus" ></i>Establecimiento</button>
                          </div>
                          <h3 class="panel-title">LISTA DE ESTABLECIMIENTOS</h3>
                        </div>
                        <div class="panel-body">
                          <div class="table-responsive">
                                 <table class="table table-hover" id="list-Establecimientos">
                                  <thead>
                                    <tr>
                                    <th>Nombre del establecimiento</th>
                                    <th>-</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                     @if(!empty($establecimientos))
                                      @if(count($establecimientos)>=1)
                                             @foreach($establecimientos as $est)
                                                   <tr>
                                                    <td> {{$est->nombreComercial}}</td>  
                                                    <td>{{$est->direccion}}</td>
                                                    <td><a class="btn btn-xs btn btn-primary btn-danger btn-perspective" onclick="eliminarRegistro(  {{$est->idDetalle}});"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                                                      </tr>
                                             @endforeach
                                      @endif
                                     @endif
                                    </tbody>
                                </table>
                                </div><!-- /.table-responsive -->
                        </div>
              </div>
                <div class="panel panel-primary" id="cont-productos">
                      <div class="panel-heading">
                          <div class="right-content">
                          <button  type="button" class="btn btn-primary" id="btnBuscarProducto"><i class="fa fa-plus" ></i>Productos</button>
                          </div>
                          <h3 class="panel-title">LISTA DE PRODUCTOS</h3>
                        </div>
                        <div class="panel-body">
                          <div class="table-responsive">
                                 <table class="table table-hover" id="list-Productos">
                                  <thead>
                                    <tr>
                                     <th>Nombre comercial</th>
                                     <th>Propietario</th>
                                     <th>Fecha vencimiento</th>
                                     <th>No.Lote</th>
                                    <th>-</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @if(!empty($productos))
                                      @if(count($productos)>=1)
                                             @foreach($productos as $pro)
                                               <tr>
                                              <td> {{$pro->nombreComercial}}</td>  
                                              <td>{{$pro->titular}}</td>
                                              @if($pro->fechaVencimiento!='1900-01-01')
                                              <td>{{$pro->fechaVencimiento}}</td>
                                              @else
                                               <td><p class="text-info">FECHA SIN REGISTRAR</p></td>
                                              @endif
                                              <td>{{$pro->noLote}}</td>
                                              <td><a class="btn btn-xs btn btn-primary btn-danger btn-perspective" onclick="eliminarRegistro({{$pro->idDetalle}});"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                                                </tr>
                                               @endforeach

                                      @endif
                                      @endif
                                    </tbody>
                                </table>
                                </div><!-- /.table-responsive -->
                        </div>
              </div>