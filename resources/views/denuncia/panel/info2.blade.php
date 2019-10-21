
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombre usuario</b></div>
                      <input type="text" class="form-control" id="usuario" name="usuario" value="{{$detalle->nombreUsuario}}" autocomplete="off" disabled >
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Télefono del que llama</b></div>
                     <input type="text" class="form-control" value="{{$detalle->telLlamada}}" id="telLlamada" name="telLlamada" autocomplete="off"  disabled>
                    </div>
                    </div>


                    </div>
                </div>

                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Departamento</b></div>
                      <select class="form-control" name="departamento" id="departamento" disabled>
                        @if($detalle->idDepartamento!=0)
                       
                           @foreach($listDepartamento as $dep)
                             @if(trim($detalle->idDepartamento)==trim($dep->idDepartamento))
                              <option value="{{trim($dep->idDepartamento)}}" selected>{{$dep->nombreDepartamento}}</option>
                              @else
                            <option value="{{trim($dep->idDepartamento)}}">{{$dep->nombreDepartamento}}</option>
                              @endif
                           @endforeach
                        
                        @else
                              <option value="">Seleccione un departamento...</option>
                              @foreach($listDepartamento as $dep)
                              <option value="{{trim($dep->idDepartamento)}}">{{$dep->nombreDepartamento}}</option>
                              @endforeach


                        @endif
                      </select>
                    </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Municipio</b></div>
                     <select class="form-control" name="municipio" id="municipio" disabled>
                    @if($detalle->idMunicipio!=0)

                        @foreach($listMunicipios as $munn) 
                         
                          @if(trim($detalle->idMunicipio)==trim($munn->idMunicipio))
                        <option value="{{trim($munn->idMunicipio)}}" selected>{{$munn->nombreMunicipio}}</option>
                          @else
                             <option value="{{trim($munn->idMunicipio)}}">{{$munn->nombreMunicipio}}</option>
                          @endif
                        @endforeach

                    @else
                       <option value="">Seleccione un municipio...</option>
                        @foreach($listMunicipios as $mun)
                        <option value="{{trim($mun->idMunicipio)}}">{{$mun->nombreMunicipio}}</option>
                        @endforeach
                    @endif
                     </select>
                    </div>
                    </div>
                    </div>
                </div>

         

    
        
                <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Télefonos a notificar</b></div>
                     <input type="text" class="form-control cel_masking" id="tel1" name="tel1" autocomplete="off" value="{{$detalle->tel1Notificar}}" disabled>
                     <input type="text" class="form-control cel_masking" id="tel2" name="tel2" autocomplete="off" value="{{$detalle->tel2Notificar}}" disabled>
                    </div>
                    </div>
                     <div class="col-sm-12 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Correo</b></div>
                       <input type="email" class="form-control" id="correo" name="correo" autocomplete="off" value="{{$detalle->correo}}" disabled>
                    </div>
                    </div>
                    </div>
                    </div>

                    <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Desea que se le notifique la resolución del aviso </b></div>
                      <input type="text" class="form-control" id="aviso" name="aviso" value="{{$detalle->aviso}}" autocomplete="off" disabled>
                    </div>
                    </div>
                     
                    </div>
                    </div>
