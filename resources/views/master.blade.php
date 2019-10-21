<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="Sistema de Sesiones">
		<meta name="keywords" content="Sesiones">
		<meta name="author" content="Unidad de Informática">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<link href="{{{ asset('img/favicon.ico') }}}" rel="shortcut icon">
		 <link rel="stylesheet" type="text/css" href="{{url("/css/select2.min.css")}}">
		<title>SISTEMA INTEGRADO</title>
 
		<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
		{!! Html::style('css/bootstrap.min.css') !!} 
		<!--<link href="" rel="stylesheet">-->
		
		<!-- PLUGINS CSS -->
		{!! Html::style('plugins/weather-icon/css/weather-icons.min.css') !!} 
		{!! Html::style('plugins/prettify/prettify.min.css') !!} 
		{!! Html::style('plugins/magnific-popup/magnific-popup.min.css') !!} 
		{!! Html::style('plugins/owl-carousel/owl.carousel.min.css') !!} 
		{!! Html::style('plugins/owl-carousel/owl.theme.min.css') !!} 
		{!! Html::style('plugins/owl-carousel/owl.transitions.min.css') !!} 
		{!! Html::style('plugins/chosen/chosen.min.css') !!} 
		{!! Html::style('plugins/icheck/skins/all.css') !!} 
		{!! Html::style('plugins/datepicker/datepicker.min.css') !!} 
		{!! Html::style('plugins/timepicker/bootstrap-timepicker.min.css') !!} 
		{!! Html::style('plugins/validator/bootstrapValidator.min.css') !!} 
		{!! Html::style('plugins/summernote/summernote.css') !!} 
		{!! Html::style('plugins/markdown/bootstrap-markdown.min.css') !!} 
		{!! Html::style('plugins/datatable/css/bootstrap.datatable.min.css') !!} 
		{!! Html::style('plugins/morris-chart/morris.min.css') !!} 
		{!! Html::style('plugins/c3-chart/c3.min.css') !!} 
		{!! Html::style('plugins/slider/slider.min.css') !!}
		{!! Html::style('plugins/alertifyjs/css/alertify.min.css') !!} 
		{!! Html::style('plugins/alertifyjs/css/themes/default.min.css') !!} 

		<!-- MAIN CSS (REQUIRED ALL PAGE)-->
		{!! Html::style('plugins/font-awesome/css/font-awesome.min.css') !!} 
		{!! Html::style('css/style.css') !!} 
		{!! Html::style('css/style-responsive.css') !!} 
 		
		@yield('css')

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style type="text/css" media="screen">
			.text-uppercase{
				text-transform: uppercase;
			}
			.modal {
			    width:      100%;
			    background: rgba( 255, 255, 255, .8 );
			}
			.dlgwait {
			    display:    none;
			    position:   fixed;
			    z-index:    1000;
			    top:        0;
			    left:       0;
			    height:     100%;
			    width:      100%;
			    background: rgba( 255, 255, 255, .8 ) 
			                url("{{ asset('img/ajax-loader.gif') }}") 
			                50% 50% 
			                no-repeat;
			}
			.modal {
			    width:      100%;
			    background: rgba( 255, 255, 255, .8 );
			}

			/* When the body has the loading class, we turn
			   the scrollbar off with overflow:hidden */
			body.loading {
			    overflow: hidden;
			}

			/* Anytime the body has the loading class, our
			   modal element will be visible */
			body.loading .dlgwait {
			    display: block;
			}
		</style>
	</head>
 
	<body class="tooltips">
		<div class="dlgwait"><!-- Place at bottom of page --></div>
		<!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
		<div class="wrapper">
			@include('layouts.top-nav')
			
			@include('layouts.sidebar-left-menu')
			
			<!-- BEGIN PAGE CONTENT -->
			<div class="page-content {{ (Session::get('cfgHideMenu',false))?'toggle':'' }}">
				<div>&nbsp;</div>
				<div class="container-fluid">				
				@if (!empty($breadcrumb))
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="{{ url('/') }}"><i class="fa fa-home"></i></a></li>
						@if(count($breadcrumb)>1)
							@for ($i = 0; $i < count($breadcrumb) ; $i++)
								@if(($i+1) == count($breadcrumb))
									<li class="active">{{ $breadcrumb[$i]['nom'] }}</li>
								@else
									<li><a href="{{ $breadcrumb[$i]['url'] }}">{{ $breadcrumb[$i]['nom'] }}</a></li>
								@endif
							@endfor
						@else
							<li class="active">{{ $breadcrumb[0]['nom']}}</li>					
						@endif
					</ol>
					<!-- End breadcrumb -->
				@endif					
				<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">{{ $title }} <small>{{ $subtitle }}</small></h3>
						</div>
						<div class="panel-body">
								@yield('contenido')			
						</div>
					</div>	
				
								
				</div><!-- /.container-fluid -->
				
				<!-- BEGIN FOOTER -->
				<footer>
					&copy; 2017 <a href="www.medicamentos.gob.sv">Dirección Nacional de Medicamentos</a><br />
					Diseñado por <a href="#">IT DNM</a>
				</footer>
				<!-- END FOOTER -->
				
				
			</div><!-- /.page-content -->
		</div><!-- /.wrapper -->
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
		<!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
		{!! Html::script('js/jquery.min.js') !!}
		{!! Html::script('js/bootstrap.min.js') !!}
		{!! Html::script('plugins/retina/retina.min.js') !!}
		{!! Html::script('plugins/nicescroll/jquery.nicescroll.js') !!}
		{!! Html::script('plugins/slimscroll/jquery.slimscroll.min.js') !!}
		{!! Html::script('plugins/backstretch/jquery.backstretch.min.js') !!}
 
		<!-- PLUGINS -->
		{!! Html::script('plugins/skycons/skycons.js') !!}
		{!! Html::script('plugins/prettify/prettify.js') !!}
		{!! Html::script('plugins/magnific-popup/jquery.magnific-popup.min.js') !!}
		{!! Html::script('plugins/owl-carousel/owl.carousel.min.js') !!}
		{!! Html::script('plugins/chosen/chosen.jquery.min.js') !!}
		{!! Html::script('plugins/icheck/icheck.min.js') !!}
		{!! Html::script('plugins/datepicker/bootstrap-datepicker.js') !!}
		{!! Html::script('plugins/timepicker/bootstrap-timepicker.js') !!}
		{!! Html::script('plugins/mask/jquery.mask.min.js') !!}
		{!! Html::script('plugins/validator/bootstrapValidator.min.js') !!}
		{!! Html::script('plugins/datatable/js/jquery.dataTables.min.js') !!}
		{!! Html::script('plugins/datatable/js/bootstrap.datatable.js') !!}
		{!! Html::script('plugins/summernote/summernote.min.js') !!}
		{!! Html::script('plugins/markdown/markdown.js') !!}
		{!! Html::script('plugins/markdown/to-markdown.js') !!}
		{!! Html::script('plugins/markdown/bootstrap-markdown.js') !!}
		{!! Html::script('plugins/slider/bootstrap-slider.js') !!}
		{!! Html::script('plugins/alertifyjs/alertify.min.js') !!}

		@yield('js')

		<!-- MAIN APPS JS -->
		{!! Html::script('js/apps.js') !!}
		  <script src="{{url('/js/select2.full.min.js')}}"></script>

		<script type="text/javascript">
			$(document).ajaxStart(function ()
			{
			    $('body').addClass('loading');

			}).ajaxComplete(function () {

			    $('body').removeClass('loading');

			});
			$(document).ready(function() {
				alertify.defaults.glossary.title = 'Sesiones - Mensaje del Sistema';
			});
			function changeConfigMenu()
			{
				$.ajax({
					url:   "{{url('cfg/menu')}}",
            		type:  'get'
				});
			}
		</script>

	</body>
</html>