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
	<p>La correspondencia <b>{{$soli->asunto}}</b> con el número de presentación <b>{{$soli->noPresentacion}}</b>, ha sido reaperturado por la Dirección Ejecutiva.</p>
	<br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
</body>
</html>