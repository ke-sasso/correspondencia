<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<?php 
	
	$fechaIngreso = date('d-m-Y');
?>
<body>
	<h4>Solicitud de correspondencia</h4>
	<p><b>Número de Presentación:</b>  {{ $solicitud->noPresentacion }}</p>
	<p><b>Asunto:</b>  {{ $solicitud->asunto }}</p><br><br>
	<h4>Se le notifica que:</h4>
	<p>El usuario <b>{{$usuario}}</b> detalla la siguiente observación: </p>
	<br>
	 <p>{{ $comentario }}</p>
	<br><br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>


</body>
</html>