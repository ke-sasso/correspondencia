<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> </title>
    <style type="text/css">
   
      body{
        font-size: 14px;

      }

       #wrap {
      float: center;
      position: relative;
      left: 35%;
    }

    #content {
        float: center;
        position: relative;
      }
      div#header{
        width: 74%;
        display: inline-block;
        margin: 0 auto; 
        border:1px solid black;
      }
      div#header img#escudo{
        height: 60px;
        width: auto;
        max-width: 20%;
        display: inline-block;
        margin: 0.5em;
      }
      div#header img#logo{
        height: 40px;
        width: auto;
        max-width: 20%;
        display: inline-block;
        margin: 0.5em;
      }
      div#header div#mainTitle{
        width: 65%;
        display: inline-block;
        margin: 0.5em;  
        margin-right: 1em;
        text-align: center; 
      }
      #footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 0;
        text-align: center;
      }
       table#cabecera{
        width: auto;
        min-width: 100%;
        max-width: 100%;
        margin: 0 auto;
        border-collapse: collapse !important;
        width: auto !important;
        max-width: 70% !important;
        margin: 0 auto;
        font-style: 'times new roman' !important;
        width: auto !important;
        max-width: 70% !important;
        margin: 0 auto;

      }
      table#cabecera2{
        width: auto;
        min-width: 100%;
        max-width: 100%;
        margin: 0 auto;
        border-collapse: collapse !important;
        width: auto !important;
        max-width: 70% !important;
        margin: 0 auto;
        font-style: 'times new roman' !important;
        width: auto !important;
        max-width: 70% !important;
        margin: 0 auto;

      }
      table#cabecera tr td{
      border-left: .5px solid black;
      border-right: .5px solid black;
      border-top: .5px solid black;
      border-bottom: .5px solid black !important;
      padding-bottom: 0px  !important;
      max-width: 70% !important;
      margin: 0 auto;
      
    }
    </style>
  </head>
  <body>

         <div class="table">
      <table id="cabecera">
        <thead>
          <tr bgcolor= "#D8D8D8">
            <td colspan="2"> DENUNCIA @if($general->idTipo==2) TELÉFONICA @else CIUDADANA @endif</td>
          </tr>
        </thead>
        <tbody>
          <tr><td width="25%">No. Presentacion</td><td>{{$general->noPresentacion}}<br>&nbsp;</td></tr>
          <tr><td>Fecha de presentación</td><td>{{$general->fechaCreacion}}<br>&nbsp;</td></tr>
          <tr><td>Asunto</td><td><?php echo strip_tags($general->asunto); ?><br>&nbsp;</td></tr>
          @if($detalle->fechaEvento!='1900-01-01')
           <tr><td>Fecha del evento</td><td>{{$detalle->fechaEvento}}<br>&nbsp;</td></tr>
          @endif

          @if(strlen($general->descripcion)>0)
          <tr><td>Descripción</td><td><?php  echo $general->descripcion; ?></td></tr>
          @endif

          @if(strlen($detalle->nombreUsuario)>0)
           <tr><td>Nombre usuario</td><td>{{$detalle->nombreUsuario}}  
           @if(strlen($detalle->edad)>0)<br><b>Edad:</b>{{$detalle->edad}} @endif
           @if(strlen($detalle->profesion)>0)<br><b> Profesión:</b>{{$detalle->profesion}} @endif
           @if(strlen($detalle->noDocumento)>0)<br><b> No.Documento:</b>{{$detalle->noDocumento}} @endif
           <br>&nbsp;</td></tr>
          @endif
          @if(strlen($detalle->telLlamada)>1)
           <tr><td>Teléfono de llamada</td><td>{{$detalle->telLlamada}}<br>&nbsp;</td></tr>
          @endif

          @if(strlen($departamento)>0)
           <tr><td>Departamento</td><td>{{$departamento}}<br>&nbsp;</td></tr>
          @endif

          @if(strlen($municipio)>0)
           <tr><td>Municipio</td><td>{{$municipio}}<br>&nbsp;</td></tr>
          @endif
          @if(strlen($detalle->ofrecePrueba)>0)
           <tr><td>Ofrece de prueba</td><td><?php echo $detalle->ofrecePrueba; ?><br>&nbsp;</td></tr>
          @endif
          @if(strlen($detalle->pide)>0)
           <tr><td>Pide</td><td><?php echo $detalle->pide;?><br>&nbsp;</td></tr>
          @endif
          @if(strlen($detalle->tel1Notificar)>1 || strlen($detalle->tel2Notificar)>1 )
           <tr><td>Número de teléfono</td><td>{{$detalle->tel1Notificar}} <br> {{$detalle->tel2Notificar}}<br>&nbsp;</td></tr>
          @endif
           @if(strlen($detalle->correo)>0)
           <tr><td>Correo</td><td>{{$detalle->correo}}<br>&nbsp;</td></tr>
          @endif
          @if(count($arc)>0)
          <tr><td>Adjuntos de la denuncia</td><td>@foreach($arc as $a)-{{$a->nombreArchivo}}<br>@endforeach<br></td></tr>
          @endif
          @if(strlen($medio)>0)
           <tr><td>Medio de recepción</td><td>{{$medio}}<br>&nbsp;</td></tr>
          @endif 
           @if(strlen($general->observaciones)>0)
          <tr><td>Observaciones</td><td><?php  echo $general->observaciones; ?></td></tr>
          @endif
        </tbody>
      </table>
    </div><br>

      @if(count($establecimientos)>0)
      <div class="table">
      <table id="cabecera">
        <thead>
          <tr bgcolor= "#D8D8D8">
            <td colspan="4">ESTABLECIMIENTOS</td>
          </tr>
          <tr>
                <th>Nombre comercial</th>
                <th>No.Registro</th>
                <th>Estado</th>
                <th>Dirección</th>
        </tr>
        </thead>
        <tbody>

          @foreach($establecimientos as $reg)
           <tr>
            
             <td>{{$reg->nombreComercial}}<br>&nbsp;</td>
             @if(strlen($reg->noRegistro)>0)
             <td>{{$reg->noRegistro}}<br>&nbsp;</td>
             @else
             <td>N/A<br>&nbsp;</td>
             @endif
             <td>@if(trim($reg->estado=='A'))ACTIVO @elseif(trim($reg->estado=='I'))INACTIVO @elseif(trim($reg->estado=='C')) CANCELADO @elseif(trim($reg->estado=='E')) ELIMINADO @else N/A @endif  <br>&nbsp;</td>
             <td>{{$reg->direccion}}<br>&nbsp;</td>
  
           </tr>
          @endforeach

        </tbody>
 
      </table>
    </div><br>
     @endif

      @if(count($productos)>0)

     <div class="table">
      <table id="cabecera">
        <thead>
          <tr bgcolor= "#D8D8D8">
            <td colspan="5">PRODUCTOS</td>
          </tr>
          <tr>
                <th>Nombre comercial</th>
                <th>No.Registro</th>
                <th>Fecha Vencimiento</th>
                <th>No.Lote</th>
                 <th>Estado</th>
        </tr>
        </thead>
        <tbody>
          @foreach($productos as $reg2)
           <tr>
            
             <td>{{$reg2->nombreComercial}}<br>&nbsp;</td>

             @if(strlen($reg2->noRegistro)>0)
             <td>{{$reg2->noRegistro}}<br>&nbsp;</td>
             @else
             <td>N/A &nbsp;</td>
             @endif

             @if($reg2->fechaVencimiento=='1900-01-01')
               <td>N/A &nbsp;</td>
             @else
             <td>{{$reg2->fechaVencimiento}}<br>&nbsp;</td>
             @endif

             @if(strlen($reg2->noLote)>0)
             <td>{{$reg2->noLote}}<br>&nbsp;</td>
             @else
              <td>N/A<br>&nbsp;</td>
             @endif

             <td>@if(trim($reg2->estado=='A'))ACTIVO @elseif(trim($reg2->estado=='I'))INACTIVO @elseif(trim($reg2->estado=='C')) CANCELADO @elseif(trim($reg2->estado=='E')) ELIMINADO  @else N/A @endif  <br>&nbsp;</td>
  
           </tr>
          @endforeach

        </tbody>
        
      </table>
    </div><br>
     @endif
      
      
   


  </body>

</html>
