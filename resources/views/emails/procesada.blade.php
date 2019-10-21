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
	<h4>Se le notifica que:</h4>
	<p>La solicitud <b>{{$soli->asunto}} con número de presentación {{$soli->noPresentacion}}</b> fue aprobada.</p>
	<br>
	Observación: <?php if(strlen($obser)>1){ echo $obser; }else{ echo ' no existe observación.';} ?>
	<br><br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>


</body>
</html>