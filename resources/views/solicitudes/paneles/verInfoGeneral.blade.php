                          <div class="form-group">
                          <div class="row">
                            <div class="col-sm-12 col-md-12">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Asunto</b></div>
                          <input type="text" class="form-control" id="asunto" value="{{$info->asunto}}" name="asunto" autocomplete="off" readonly>
                          </div>
                          </div>
                          </div>
                          </div>

                          <div class="form-group">
                          <div class="row">
                          <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Estado</b></div>
                          <input type="text" class="form-control" id="noPresentacion" value="{{$estado->nombreEstado}}" name="noPresentacion" autocomplete="off" readonly>
                          </div>
                          </div>
                            <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>No presentación</b></div>
                          <input type="text" class="form-control" id="noPresentacion" value="{{$info->noPresentacion}}" name="noPresentacion" autocomplete="off" readonly>
                          </div>
                          </div>
                          </div>
                          </div>
                            <div class="form-group">
                          <div class="row">
                            <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Fecha Recepción</b></div>
                          <input type="text" class="form-control" id="asunto" value="{{$info->fechaRecepcion}}" name="asunto" autocomplete="off" readonly>
                          </div>
                          </div>
                           <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Fecha de finalización</b></div>
                          <input type="text" class="form-control" id="asunto" value="{{$info->fechaFinalProceso}}" name="asunto" autocomplete="off" readonly>
                          </div>
                          </div>
                          </div>
                          </div>
                               <div class="form-group">
                          <div class="row">
                            <div class="col-sm-12 col-md-12">
                             <div class="input-group ">
                              <div class="input-group-addon"><b>Descripci&oacute;n</b></div>
                              <textarea class="form-control"  rows="2" id="descripcion" name="descripcion" autocomplete="off">{{$info->descripcion}}</textarea>
                              </div>
                           </div>
                           </div>
                           </div>
                           <br>
                           <div class="form-group">
                          <div class="row">
                            <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>NIT Persona que presenta</b></div>
                          <input type="text" class="form-control" id="asunto" value="{{$info->nitSolicitante}}" name="asunto" autocomplete="off" readonly>
                          </div>
                          </div>
                          <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Nombre</b></div>
                          <input type="text" class="form-control" id="noPresentacion" @if(!empty($pn)) value="{{$pn->nombres}} {{$pn->apellidos}}" @endif name="noPresentacion" autocomplete="off" readonly>
                          </div>
                          </div>
                          </div>
                          </div>
                           <div class="form-group">
                          <div class="row">
                            <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Teléfono 1</b></div>
                          <input type="text" class="form-control" id="asunto" @if(!empty($t1)) value="{{$t1}}" @endif name="asunto" autocomplete="off" readonly>
                          </div>
                          </div>
                          <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Teléfono 2</b></div>
                          <input type="text" class="form-control" id="noPresentacion" @if(!empty($t2)) value="{{$t2}}" @endif name="noPresentacion" autocomplete="off" readonly>
                          </div>
                          </div>
                          </div>
                          </div>
                          <div class="form-group">
                          <div class="row">
                            <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Correo</b></div>
                          <input type="text" class="form-control" id="asunto" @if(!empty($pn)) value="{{$pn->emailsContacto}}" @endif name="asunto" autocomplete="off" readonly>
                          </div>
                          </div>
                          <div class="col-sm-12 col-md-6">
                           <div class="input-group ">
                           <div class="input-group-addon"><b>Conocido por</b></div>
                          <input type="text" class="form-control" id="noPresentacion" @if(!empty($pn)) value="{{$pn->conocidoPor}}" @endif name="noPresentacion" autocomplete="off" readonly>
                          </div>
                          </div>
                          </div>
                          </div>