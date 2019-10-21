<div class="the-box no-border">

       @if(!empty($estadoPart))
             @if($estadoPart[0]!=2)
              <!--  COMENTARIOS PARA RESPONSABLES -->
                    @include('solicitudes.comentarios.participante')
                    @include('solicitudes.comentarios.ingresarComentario')
             @else
            <!--  COMENTARIOS PARA COLABORADOR QUE BRINDAN OPINIÓN U ASISTENCIA  -->
                    @include('solicitudes.comentarios.colaborador')
             @endif <!--CIERRE DE IF PARA VALIDAR SI ES COLABORADOR -->
       @else
             @if(in_array(477, $permisos, true))
                  @include('solicitudes.comentarios.participante')
             @endif 
       @endif


  