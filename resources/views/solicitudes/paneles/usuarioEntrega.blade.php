      <div class="form-group">
                     <div class="row">
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Nombres</b></div>
                     <input type="text" name="nombres" id="nombres" disabled class="form-control" value="{{ $usuarioEntregado->nombres }}">
                    </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Apellidos</b></div>
                     <input type="text" name="apellidos" id="apellidos" disabled class="form-control" value="{{ $usuarioEntregado->apellidos }}">
                    </div>
                    </div>
                    </div>
                </div>
                 <div class="form-group">
                     <div class="row">
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Teléfono</b></div>
                     <input type="text" name="tel" id="tel" class="form-control" disabled {{ $usuarioEntregado->telefono }}>
                    </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Correo</b></div>
                     <input type="email" name="correo" id="correo" class="form-control" disabled {{ $usuarioEntregado->email }}>
                    </div>
                    </div>
                    </div>
                </div>
                  <div class="form-group">
                     <div class="row">
                    <div class="col-sm-12 col-md-12">
                     <div class="input-group ">
                      <div class="input-group-addon"><b>Observación</b></div>
                      <textarea class="form-control" id="observacion" name="observacion" disabled autocomplete="off" >{{ $usuarioEntregado->observacion }}</textarea>
                    </div>
                    </div>
                    </div>
                </div>