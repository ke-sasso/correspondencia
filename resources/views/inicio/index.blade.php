@extends('master')

@section('css')



</style>
@endsection


@section('contenido')
@if(Session::has('msnExito'))
	<div class="alert alert-success square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
		<strong>Enhorabuena!</strong>
		{{ Session::get('msnExito') }}
	</div>
@endif
{{-- MENSAJE DE ERROR --}}
@if(Session::has('msnError'))
	<div class="alert alert-danger square fade in alert-dismissable">
		<button class="close" aria-hidden="true" data-dismiss="alert" type="button">x</button>
		<strong>Auchh!</strong>
		Algo ha salido mal.	{{ Session::get('msnError') }}
	</div>
@endif

	<!-- BEGIN CAROUSEL ITEM -->
			    <div class="the-box no-border">
				<h4 class="small-heading more-margin-bottom text-center">PROCESO DE LA SOLICITUD DE CORRESPONDENCIA</h4>
						<div id="store-item-carousel-3" class="owl-carousel shop-carousel">
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
 										<i class="fa fa-envelope-o fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#">INGRESADA</a></h4>
									  <p class="brand">La solicitud de correspondecia es ingresa al sistema y a espera de ser asignada</p>
									</div>
								</div>
							</div><!-- /.item -->
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
									  <i class="fa  fa-group fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#fakelink">ASIGNADA</a></h4>
									  <p class="brand">La solicitud de correspondencia es asignada a uno o más participantes</p>
									</div>
								</div>
							</div><!-- /.item -->
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
									    <i class="fa   fa-comments fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#fakelink">EN PROCESO</a></h4>
									  <p class="brand">Los participantes ya realizaron una acción dentro de la solicitud que puede ser: asistencia, opinión o comentar. </p>
									</div>
								</div>
							</div><!-- /.item -->
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
											<i class="fa  fa-legal fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#fakelink">EN REVISIÓN</a></h4>
									  <p class="brand">A ESPERA DE QUE LA SOLICITUD SEA OBSERVADA O APROBADA</p>
									</div>
								</div>
							</div><!-- /.item -->
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
											<i class="fa  fa-legal fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#fakelink">PARA FIRMA</a></h4>
									  <p class="brand">A ESPERA DE QUE LA SOLICITUD SEA OBSERVADA O APROBADA</p>
									</div>
								</div>
							</div><!-- /.item -->
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
									 <i class="fa  fa-refresh fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#fakelink">OBSERVADA</a></h4>
									  <p class="brand">SE HABILITA PERMISOS A PARTICIPANTES PARA VOLVER A COMENTAR O CERRAR CASO</p>
									  <p class="text-danger">Ver en comentarios la observación</p>
									</div>
								</div>
							</div><!-- /.item -->
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
									   <i class="fa  fa-pencil-square-o fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#fakelink">FIRMADA</a></h4>
									  <p class="brand">SE ADJUNTO DOCUMENTO FIRMADO PARA SER ENTREGADO A USUARIO</p>
									</div>
								</div>
							</div><!-- /.item -->
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
									  <i class="fa   fa-check-square-o fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#fakelink">NOTIFICADA</a></h4>
									  <p class="brand">EL DOCUMENTO FIRMADO FUE ENTREGADO A USARIO Y FINALIZA SU PROCESO</p>
									</div>
								</div>
							</div><!-- /.item -->
							<div class="item">
								<div class="media">
									<a class="pull-left" href="#fakelink">
									  <i class="fa   fa-file-text-o fa-5" style="font-size:50px" aria-hidden="true"></i>
									</a>
									<div class="media-body">
									  <h4 class="media-heading"><a href="#fakelink">FINALIZADA</a></h4>
									  <p class="brand">NO SE ENTREGO NINGUN DOCUMENTO A USUARIO Y FINALIZA SU PROCESO</p>
									</div>
								</div>
							</div><!-- /.item -->
						</div><!-- /#store-item-carousel-1 -->
					</div><!-- /.the-box .no-border -->
					<!-- END CAROUSEL ITEM -->
@endsection

@section('js')


@endsection