<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    {!! Html::style('css/bootstrap.min.css') !!} 
	<title>Document</title>
</head>
<?php 
	
	$fechaIngreso = date('d-m-Y');
?>
<body>
	<h4>Se le notifica que:</h4>
	<p>En la solicitud <b>{{$soli->asunto}}</b> existe nuevo comentario</p>
	<br>
	<p><b>Participante: </b> {{$comentario->nombresEmpleado}} {{$comentario->apellidosEmpleado}} </p>
	<br>
	@if($comentario->comentario!='' || $comentario->comentario != null)
	<p><b>Comentario: </b> <?php echo $comentario->comentario; ?></p>
	@endif
	<br>
	  <?php
     $a=$soli->idSolicitud;
     $b=$comentario->idComentario; 
     $c=$soli->asunto;
     $ruta1=env('RUTA_APROBAR');
     $ruta2=env('RUTA_COMENTAR');
      
    ?>
	@if($comEstado==0)
    <a href="<?php echo $ruta1;?><?php echo $a;?>/<?php echo $b;?>">APROBAR</a>
    @endif
    
    <a href="<?php echo $ruta2;?><?php echo $a;?>">COMENTAR</a>
    <br>
    <a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
</body>
</html>