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
	<h4>Se le ha enviado a revisión la denuncia: <b>{{$soli->asunto}}</b></h4>
	<br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
</body>
</html>