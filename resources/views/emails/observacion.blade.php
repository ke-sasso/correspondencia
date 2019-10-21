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
	<p>Se informa que en la solicitud: <b>{{$soli->asunto}}</b> con número de presentación <b>{{$soli->noPresentacion}}</b> tiene la siguiente observación:</p>
	<br><p>{{$obser}}</p><br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
</body>
</html>