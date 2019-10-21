<?php
	$permisos = App\UserOptions::getAutUserOptions();
	$count = App\Traits\CantidadSolicitudesTrait::countSolPerfilAsesor($permisos);
?>
<!-- BEGIN SIDEBAR LEFT -->
<div class="sidebar-left sidebar-nicescroller {{ (Session::get('cfgHideMenu',false))?'toggle':'' }}">
	<ul class="sidebar-menu">
		<li class="{{ (Request::is('inicio') || Request::is('/')) ? 'active selected' : '' }}">
			<a href="{{ url('/inicio') }}"><i class="fa fa-dashboard icon-sidebar"></i>Inicio</a>
		</li>

    	<li>

		</li>

		<li class="dropdown">
	      <a href="#fakelink">
	        <i class="fa fa-envelope-o icon-sidebar"></i>
	        <i class="fa fa-angle-right chevron-icon-sidebar"></i>
	       CORRESPONDENCIA @if(!empty($count)) <span class="label label-success span-sidebar">{{$count['total']}}</span>@endif
	      </a>
	      <ul class="submenu">
	     	@if(in_array(470, $permisos, true))
			<li><a href="{{route('nueva.solicitud')}}">Nueva</a></li>
			 <li><a href="{{route('lista.nuevas.recepcion')}}">Listado Ingresadas</a></li>
             <li><a href="{{route('lista.firmar.soli',['idEstado'=>9])}}">A notificar</a></li>
			@endif

			@if(in_array(478, $permisos, true) || in_array(479, $permisos, true))
			<li><a href="{{route('lista.solicitud.revision')}}">Para Revisión</a></li>
			<li><a href="{{route('lista.solicitud.asignadas.asesor')}}">Asignadas</a></li>
			@endif

			@if(in_array(477, $permisos, true))
			<li> <a href="{{route('lista.pendientes.asginar')}}">Pendientes asignar</a></li>
			<li> <a href="{{route('lista.asignadas.asistente')}}">Asignadas</a></li>
			<li><a href="{{route('lista.todas.asistente')}}">Historial</a></li>
			@endif
			@if(in_array(491, $permisos, true))
				<li><a href="{{route('lista.todas.asistente')}}">Historial</a></li>
			@endif

			@if(in_array(493, $permisos, true))
			<li><a href="{{route('lista.firmar.soli',['idEstado'=>7])}}">Para Revisión @if(!empty($count))<span class="label label-info span-sidebar">{{$count['revision']}}</span>@endif</a></li>
			<li><a href="{{route('lista.firmar.soli',['idEstado'=>8])}}">Para firma  @if(!empty($count))<span class="label label-info span-sidebar">{{$count['firma']}}</span>@endif</a></li>
			@endif


			@if(in_array(471, $permisos, true) || in_array(472, $permisos, true))

						@if(in_array(478, $permisos, true) || in_array(479, $permisos, true))
									<li><a href="{{route('lista.solicitud')}}">Historial</a></li>
						@else
									@if(in_array(493, $permisos, true))
									<!--PARA RPENA -->
									<li><a href="{{route('lista.solicitud.asignadas.asesor')}}">Pendientes de resolver @if(!empty($count))<span class="label label-info span-sidebar">{{$count['asignada']}}</span>@endif</a></li>
									@else

						         	<li><a href="{{route('lista.pendi.partici')}}">Pendientes de resolver</a></li>

									@endif
									<li><a href="{{route('lista.solicitud')}}">Ver todo</a></li>
									<li><a href="{{route('lista.informativa.partici')}}">Informátiva</a></li>
						@endif

			@endif
			@if(in_array(477, $permisos, true))
				<li><a href="{{route('reporte.soli.pendientes')}}">Reporte</a></li>
			@endif
			@if(in_array(497, $permisos, true))
					<li><a href="{{route('lista.pendi.partici')}}">Pendientes de resolver</a></li>
			        <li><a href="{{route('lista.solicitud')}}">Ver todo</a></li>
			        <li><a href="{{route('lista.informativa.partici')}}">Informátiva</a></li>
			@endif
	      </ul>
	    </li>


	    @if(in_array(483, $permisos, true) || in_array(471, $permisos, true) || in_array(487, $permisos, true) || in_array(490, $permisos, true)  )
	    <li class="dropdown">
	      <a href="#fakelink">
	        <i class="fa fa-gavel icon-sidebar"></i>
	        <i class="fa fa-angle-right chevron-icon-sidebar"></i>
	       DENUNCIA @if(!empty($count))<span class="label label-success span-sidebar">{{$count['denuncia']}}</span>@endif
	      </a>
	      <ul class="submenu">
	     	@if(in_array(483, $permisos, true))
			<li>
				<a href="{{route('ver.detalle.denuncia')}}">Denuncia telefónica</a>
				<a href="{{route('nueva.denuncia.ciudadana')}}">Denuncia Ciudadana</a>
				<a href="{{route('ver.cat.medios')}}">Cat. Medios de recepción</a>
			</li>
			@endif
			@if(in_array(490, $permisos, true))
			<li><a href="{{route('lista.nuevas.denuncia')}}">Nuevas denuncias</a></li>
			<li><a href="{{route('lista.denuncia.archivadas')}}">Denuncias archivadas</a></li>
			<li><a href="{{route('lista.solicitud.denuncia')}}">Historial</a></li>
			@endif
			@if(in_array(471, $permisos, true) || in_array(483, $permisos, true)  || in_array(487, $permisos, true) )

						@if(in_array(471, $permisos, true))
						 	@if(in_array(478, $permisos, true)||in_array(486, $permisos, true))
						 	        <li><a href="{{route('lista.nuevas.denuncia')}}">Nuevas denuncias</a> </li>
								    <li><a href="{{route('lista.revision.denuncia')}}">Denuncia en revisión</a>
								    </li>
									<li><a href="{{route('lista.solicitud.denuncia')}}">Historial</a></li>

						    @else
						    		<li><a href="{{route('lista.denuncia.asignadas')}}">Solicitudes asignadas</a></li>

							@endif

						@else
						           <li><a href="{{route('lista.solicitud.denuncia')}}">Lista</a></li>

						@endif

			@endif

	      </ul>
	    </li>
	    @endif

	</ul>
</div><!-- /.sidebar-left -->
<!-- END SIDEBAR LEFT -->
