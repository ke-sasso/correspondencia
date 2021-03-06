<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="Unidad de Informática">
		<link href="{{{ asset('img/favicon.ico') }}}" rel="shortcut icon">
		<title>DNM | 401</title>
 
		<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
		{!! Html::style('css/bootstrap.min.css') !!} 
		
		<!-- MAIN CSS (REQUIRED ALL PAGE)-->
		{!! Html::style('plugins/font-awesome/css/font-awesome.min.css') !!} 
		{!! Html::style('css/style.css') !!} 
		{!! Html::style('css/style-responsive.css') !!} 
 
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
 
	<body class="tooltips"  style="background: #C2CB8A;">
		
		<!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
		<div class="login-header text-center">
			{!! Html::image('img/logo-login.png','',['class'=>'logo','alt'=>'Logo ADE']) !!}

		</div>
		<div class="login-wrapper">
			<center><p class="text-center text-warning"><h3><strong>¡ERROR! en la solicitud ya existe una respuesta aprobada.</strong></h3> </p></center>
			<br><br>
					
			<p class="text-center"><strong><a href="{{ url('/inicio/') }}">-INGRESAR AL SISTEMA-</a></strong></p>
		</div><!-- /.login-wrapper -->
		<!--
		===========================================================
		END PAGE
		===========================================================
		-->

		<!--
		===========================================================
		Placed at the end of the document so the pages load faster
		===========================================================
		-->
		<!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
		{!! Html::script('js/jquery.min.js') !!}
		{!! Html::script('js/bootstrap.min.js') !!}
		
	</body>
</html>