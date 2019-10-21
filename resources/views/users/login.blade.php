<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="BITACORA">
		<meta name="keywords" content="Bitacora,bitacora">
		<meta name="author" content="Unidad de Informática">
		<link href="{{{ asset('img/favicon.ico') }}}" rel="shortcut icon">
		<title>CORRESPONDENCIA DNM</title>
 
		<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
		{!! Html::style('css/bootstrap.min.css') !!} 
		
		
		{!! Html::style('plugins/font-awesome/css/font-awesome.min.css') !!} 
		{!! Html::style('css/style.css') !!} 
		{!! Html::style('css/style-responsive.css') !!} 
		{!! Html::style('plugins/alertifyjs/css/alertify.min.css') !!} 
		{!! Html::style('plugins/alertifyjs/css/themes/default.min.css') !!} 
 		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
 
	<body class="login tooltips">
		<!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
		<div class="login-header text-center">
			<img src="{{ asset('img/logo-login2.png') }}" class="logo" alt="Logo">
		</div>
		<div class="login-wrapper">
			<form role="form" id="frmLogin" method="post">
				<div class="form-group has-feedback lg left-feedback no-label">
				  {!! Form::text('txtUsuario',null,['class'=>'form-control no-border input-lg rounded','placeholder'=>'Usuario','autofocus'=>'true']) !!}
				  <span class="fa fa-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback lg left-feedback no-label">
				  {!! Form::password('txtPwd',['class'=>'form-control no-border input-lg rounded','placeholder'=>'Contraseña','id'=>'txtPwd']) !!}
				  <span class="fa fa-unlock-alt form-control-feedback"></span>
				</div>

				<div class="form-group">
					<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
					<button type="button" id="btnSend" class="btn btn-info btn-lg btn-perspective btn-block">Iniciar Sesión</button>
				</div>
			</form>
	
		<!-- END PAGE CONTENT -->
			
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
		{!! Html::script('js/jquery.min.js') !!}
		{!! Html::script('js/bootstrap.min.js') !!}
		{!! Html::script('plugins/alertifyjs/alertify.min.js') !!}
		<script type="text/javascript">
		$(document).ready(function() {

			$('#txtPwd').keydown(function(event) {
				if(event.keyCode == 13)
				{
					sendFrm();
				}
			});

			$('#btnSend').on('click', function(event) {
				sendFrm();
				
			});

			function sendFrm()
			{

				$.post('{{route('login')}}', $('#frmLogin').serialize(), function(data, textStatus, xhr) {
					try {
						var obj = data;
						if(obj.status == 200)
						{
							document.location.href = obj.redirect;
						}
						else {
							alertify.defaults.glossary.title = 'Mensaje del Sistema :'+obj.status;
							alertify.alert(obj.message);
						}

					} catch(e) {
						// statements
						console.log(e);
					}
				});
			}
		});
	</script>
	</body>
	
</html>