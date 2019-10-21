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
	<p>La solicitud: <b>{{$soli->asunto}} con número de presentación {{$soli->noPresentacion}}</b>  ya fue entregada al usuario</b> 
	</p>
	<br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
	
</body>
</html>