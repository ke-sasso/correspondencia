<div class="modal fade" id="panelEmpleados" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-success">
                <button type="button" class="close"
                   data-dismiss="modal">
                       <span aria-hidden="true">&times;</span>
                       <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    B&Uacute;SQUEDA DE EMPLEADOS
                </h4>
            </div>

            <div class="modal-body">

                    <div class="table">

                      <table width="100%" class="table table-hover" id="dt-empleados">
                        <thead class="the-box dark full">
                          <tr>
                            <th>CÓD EMPLEADO</th>
                            <th>NOMBRES</th>
                            <th>APELLIDOS</th>
                            <th>UNIDAD</th>
                            <th>PREFIJO</th>
                            <th>-</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>

                    </div>

            </div>
            <!-- End Modal Body -->
            <!-- Modal Footer -->
            <div class="modal-footer">
              <input type="hidden" class="form-control"  id="idSoli" name="idSoli" value="{{$info->idSolicitud}}">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
             <a class="btn btn-sm btn-success btn-perspective" onclick="enviarTitular();" ><i class="fa fa-check-square-o"></i>Guardar participantes</a>
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                            Cancelar
                </button>
            </div>

        </div>
    </div>
</div>


