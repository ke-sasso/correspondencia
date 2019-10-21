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
	<p>Se le ha solicitado <b>opinión</b> para la resolución de la solicitud  <b>{{$soli->asunto}}</b>, favor revisar sistema de correspondencia</p>
	<br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
</body>
</html>