
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
        min-width: 95%;
        max-width: 95%;
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
        min-width: 95%;
        max-width: 95%;
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
      text-align: center;
      max-width: 70% !important;
      margin: 0 auto;
      
    }
    </style>
  </head>
  <body>


    <header>

    <table  style="width:100%;">
      <tr>
        <td style="width:15%;">
          <center>
            <img id="escudo" src="{{ url('img/escudo.png') }}" />
          </center> 
        </td>
        <td style="width:70%;">
          <center>
            <h2 style="margin:0;padding:0;">
              &nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dirección Nacional de Medicamentos &nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <p>&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;República de El Salvador, América Central&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;</p>
              <strong>BOLETA DE PRESENTACIÓN</strong>
              ___________________________________________________________
            </h2>
          </center>
        </td>
        <td>
          <center>
            <img id="logo" src="{{ url('img/dnm.png') }}" />
          </center> 
        </td>
    </table>
    </header>
    <br/>
    <br/>
    <br/>

   <div align="center">
     <main>
       
         <div class="table">
      <table id="cabecera">
        <thead>
          <tr bgcolor= "#D8D8D8">
            <td colspan="2">INFORMACIÓN DE LA SOLICITUD<br>&nbsp;</td>
          </tr>
        </thead>
        <tbody>
          <tr><td width="25%">No. Presentacion</td><td>{{$general->noPresentacion}}<br>&nbsp;</td></tr>
          <tr><td>Fecha de presentación</td><td>{{$general->fechaRecepcion}}<br>&nbsp;</td></tr>
          <tr><td>Presentante</td><td>{{$solicitante->nombres}}&nbsp;{{$solicitante->apellidos}}&nbsp;({{$general->nitSolicitante}})<br>&nbsp;</td></tr>
          <tr><td>Télefono contacto</td><td>{{json_decode($solicitante->telefonosContacto)[0]}}&nbsp;&nbsp;&nbsp;{{json_decode($solicitante->telefonosContacto)[1]}}<br>&nbsp;</td></tr>
          <tr><td>Asunto</td><td>{{$general->asunto}}<br>&nbsp;</td></tr>
          <tr><td>Recepción</td><td>{{$usu}}<br>&nbsp;</td></tr>
          <tr><td>Adjuntos que presenta</td><td>@foreach($arc as $a)-{{$a->nombreArchivo}}<br>@endforeach<br></td></tr>
        </tbody>
        </tbody>
      </table>
    </div><br>
      
      
     </main>
    </div>
     <footer id="footer">
   ______________________________________________________________________________________________________
   Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
   &nbsp;&nbsp;
   PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </footer>
    <div style="page-break-after:always;"></div>
    <div class="table">
      <table id="cabecera2">
        <thead>
          <tr bgcolor= "#D8D8D8">
            <td colspan="6"><center>Clasificación de Correspondencia</center></td>
          </tr>
        </thead>
        <tbody  style=" font-size: 10px;">
          <tr>
            <td><input type="checkbox" name=""/></td><td>Solicitud de Respueta</td>          
          <td><input type="checkbox" name=""/></td><td>Notificación</td>
          <td><input type="checkbox" name=""/></td><td>Denuncia</td>
          
          </tr>
          
        </tbody>        
      </table>
    </div><br>      
       <div class="table">
      <table id="cabecera"  style=" font-size: 10px;">
        <thead>
          <tr bgcolor= "#D8D8D8">
            <td colspan="2">INFORMACIÓN DE LA SOLICITUD<br>&nbsp;</td>
          </tr>
        </thead>
        <tbody>
          <tr><td width="25%">No. Presentacion</td><td>{{$general->noPresentacion}}<br>&nbsp;</td></tr>
          <tr><td>Fecha de presentación</td><td>{{$general->fechaRecepcion}}<br>&nbsp;</td></tr>
          <tr><td>Presentante</td><td>{{$solicitante->nombres}}&nbsp;{{$solicitante->apellidos}}&nbsp;({{$general->nitSolicitante}})<br>&nbsp;</td></tr>
          <tr><td>Télefono contacto</td><td>{{json_decode($solicitante->telefonosContacto)[0]}}&nbsp;&nbsp;&nbsp;{{json_decode($solicitante->telefonosContacto)[1]}}<br>&nbsp;</td></tr>
          <tr><td>Asunto</td><td>{{$general->asunto}}<br>&nbsp;</td></tr>
          <tr><td>Recepción</td><td>{{$usu}}<br>&nbsp;</td></tr>
          <tr><td>Adjuntos que presenta</td><td>@foreach($arc as $a)-{{$a->nombreArchivo}}<br>@endforeach<br></td></tr>
        </tbody>
        </tbody>
      </table>
    </div><br>

     <div class="table">
      <table id="cabecera2">
        <thead>
          <tr bgcolor= "#D8D8D8">
            <td colspan="4"><center>Fecha respuesta</center></td>
          </tr>
        </thead>
        <tbody  style=" font-size: 10px; margin: 0px;">
          @for($h=0;$h<count($listFecha);$h++)
          <tr><td>{{$listFecha[$h]->nombreFecha}}</td><td><input type="checkbox" name=""/></td>
           <?php $h=$h+1;?>
          @if($h<count($listFecha))
          <td>{{$listFecha[$h]->nombreFecha}}</td><td><input type="checkbox" name=""/></td>
          @endif
          </tr>
          @endfor
        </tbody>
        </tbody>
      </table>
    </div><br>
  


          <div style="background-color: #DCDCDC; height: 20px;   margin-left: 15px; margin-right: 15px;">
            <p><center>Responsables</center></p>
          </div>

   <div style="display: inline-block; width: 45%;">
     <div class="table">
      <table id="cabecera2">
        <thead>
        </thead>
        <tbody style=" font-size: 10px;">
                  
          @foreach($emple1 as $j)
          <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;{{$j->nombresEmpleado}} {{$j->apellidosEmpleado}}</td><td><input type="checkbox" name=""/></td>
          </tr>
           @endforeach
        </tbody>
        </tbody>
      </table>
    </div>  
  </div>

<div  style="display: inline-block; width: 45%">
     <div class="table">
      <table id="cabecera2">
        <thead>
        </thead>
        <tbody style=" font-size: 10px;">
                  
          @foreach($emple2 as $v)
          <tr>
          <td>&nbsp;&nbsp;{{$v->nombresEmpleado}} {{$v->apellidosEmpleado}}</td><td><input type="checkbox" name=""/></td>
          </tr>
           @endforeach
        </tbody>
        </tbody>
      </table>
    </div>
  

</div>

    <br>
     <div class="table">
      <table id="cabecera2">
        <thead>
          <tr bgcolor= "#D8D8D8">
            <td colspan="4"><center>Acciones a tomar</center></td>
          </tr>
        </thead>
        <tbody style=" font-size: 10px;">
         @for($r=0;$r<count($listAcciones);$r++)
         <tr><td>{{$listAcciones[$r]->nombreAccion}}</td><td><input type="checkbox" name=""/><br></td>
            <?php $r=$r+1;?>
            @if($r<count($listAcciones))
        <td>{{$listAcciones[$r]->nombreAccion}}</td><td><input type="checkbox" name=""/><br></td>
            @endif
         </tr>
           @endfor
        </tbody>
        </tbody>
      </table>
    </div><br>
    <p>OBSERVACIONES</p>
    ______________________________________________________________________________________________________<br>
    <br>
    ______________________________________________________________________________________________________<br>
    <br>
    ______________________________________________________________________________________________________<br>
    <br>
    ______________________________________________________________________________________________________<br>
    <br>

  </body>

</html>