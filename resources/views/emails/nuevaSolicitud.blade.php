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
	<p>Nueva soliciud de correspodencia para responder:</p> 
	<p>Asunto:<b>{{$soli->asunto}}</b> <br><br>Número de presentación: <b>{{$soli->noPresentacion}}</b></p>
	<br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
</body>
</html>