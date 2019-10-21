<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title> </title>
    <style type="text/css">
   
      body{
        font-size: 11px;

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
              <strong>UNIDAD DE ACCESO A LA INFORMACIÓN PÚBLICA</strong>
              ___________________________________________________________
            </h2>
          </center>
        </td>
        <td>
          <center>
            <img id="logo" src="{{ url('img/dnm.png') }}" />
          </center> 
        </td>
        </tr>
    </table>
    </header>
    <br/>
   
      <div style="text-align: right;margin-right: 40px;">
      <b> REFERENCIA: {{$general->noPresentacion}}</b>
      </div>
      <br>
      <div style="text-align: center;">
        <b>ACTA DE CIERRE DE EXPEDIENTE DE DENUNCIA / AVISO</b>
      </div><br>
      <div style="text-align: justify; margin-left:  40px; margin-right: 40px;">
       
      Unidad de Acceso a la Información Pública: En la ciudad de Santa Tecla, Departamento de La Libertad, a las {{$fecha}}.
      <br><br>
      </div>
      <div style=" margin-left:  40px; margin-right: 40px;">
       
      <b>LA SUSCRITA OFICIAL DE INFORMACIÓN, CONSIDERANDO QUE:</b>
      <br><br>
      </div>

     <div style="margin-left:  40px; margin-right: 40px;">
      <table id="tb1">
        <tr><td width="30" style=" text-align:  justify;">I)</td><td  style="text-align:  justify;">De acuerdo a la Constitución de la República Toda persona pueda expresar y difundir libremente sus pensamientos y  toda persona tiene derecho a dirigir sus peticiones por escrito, de manera decorosa, a las autoridades legalmente establecidas; a que se le resuelvan, y a que se le haga saber lo resuelto.  Así mismo y a fin de darle cumplimiento al derecho enunciado, se crea la Ley de Acceso a la Información Pública, la cual tiene por objeto garantizar el derecho de acceso de toda persona a la información pública, a fin de contribuir con la transparencia de las actuaciones de las instituciones del Estado. </td></tr>
      </table>
     
      </div>
      <div style="margin-left:  40px; margin-right: 40px;">
      <table id="tb2">
        <tr><td width="30" style="float: right;">II)</td><td  style="text-align:  justify;"> La Convención Americana sobre Derechos Humanos, establece que todas las personas deben contar con igualdad de oportunidades para recibir, buscar e impartir información e ideas de toda índole, sin consideración  de fronteras, ya sea oralmente, por escrito, en forma impresa o artística o  por cualquier medio de comunicación sin discriminación, por ningún motivo, inclusive los de raza, color, religión, sexo, idioma, opiniones políticas o de cualquier otra índole, origen nacional o social, posición económica, nacimiento o cualquier otra condición social”. </td></tr>
      </table>

      </div>
      <br>
       <div style="margin-left:  40px; margin-right: 40px;">
       
      <b>Narración de la denuncia ciudadana planteada: </b><br><br>
      </div>
       <div style="text-align: justify; margin-left:  40px; margin-right: 40px;">
       
           En atención al caso planteado de forma @if($general->idTipo==2) teléfonica @else presencial @endif, el denunciante:  {{$detalle->nombreUsuario}},@if(strlen($detalle->profesion) || strlen($detalle->noDocumento)>0) @if(strlen($detalle->noDocumento)>0)con No.Documento: {{$detalle->noDocumento}} @endif  @if(strlen($detalle->edad)>1) de {{$detalle->edad}} años de edad @endif  @if(strlen($detalle->profesion)>0) de profesión: {{$detalle->profesion}}. @endif  @endif Notificó sobre <?php echo strip_tags($general->asunto); ?>. Esta oficina procedió a enviar memorando a la  Unidad Jurídica, para realizar el respectivo análisis con base a la información proporcionada por el ciudadano. Según el informe final bajo la referencia {{$general->noPresentacion}}, de fecha {{$fechaCreacion}}, que en lo pertinente dice:
      </div>
      <br>
       <div style="text-align: justify; margin-left:  40px; margin-right: 40px;">
      "<?php  $aaa=strip_tags($general->descripcion); echo str_replace("&NBSP;", '', $aaa); ?>"
      </div>
       @if(strlen($detalle->comentarioPDF)>0)
       <div style="text-align: justify; margin-left:  40px; margin-right: 40px;">
         <?php echo $detalle->comentarioPDF;?>
        </div>
       @endif
       <br>
      <div style="text-align: justify; margin-left:  40px; margin-right: 40px;">
      <b>POR TANTO:</b> En razón de lo antes expuesto y con base a lo estipulado en el artículo 18 de la Constitución de la República de El Salvador; se archiva el presente expediente administrativo planteado bajo el correlativo {{$general->noPresentacion}}, se le informa al ciudadano la resolución final en caso de haber proporcionado un lugar o medio de notificaciones. 
      </div>

        <br>   
  <center>Lic.<?php echo env('nombreInfo','');  echo " "; echo env('apellidoInfo','');?><br>Oficial de Información</center>
     <footer id="footer">
   ________________________________________________________________________________________________________________________________
   Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
   &nbsp;&nbsp;
   PBX 2522-5000 / Correo: uaip@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </footer>
  </body>
</html>
