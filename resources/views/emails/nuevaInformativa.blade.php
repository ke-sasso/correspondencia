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
	<h4>Se le ha asignado la siguiente correspondencia <b>informátiva<b>:  <b>{{$soli->asunto}} <br>No de presentación: {{$soli->noPresentacion}}</b>. </h4>
	<br>
	<a href="http://correspondencia.medicamentos.gob.sv/">IR AL SISTEMA</a>
</body>
</html>