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
	<p>En la solicitud <b>{{$soli->asunto}}</b> 
	 ya tiene una nueva observaci&oacute;n.
	</p>
	<br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
</body>
</html>