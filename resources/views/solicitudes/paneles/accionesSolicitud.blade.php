<div class="rows">
			<div class="col-sm-12">
@if(in_array(497, $permisos, true))         
		
      <div class="btn-group">	
      				    @if($info->idEstado == 2 || $info->idEstado == 3 || $info->idEstado == 11)
				                  <button onclick="accionSol(1);" data-toggle="tooltip" title="Reportar asignación" type="button" class="btn btn-success"><i class="fa  fa-bullhorn"></i></button>
				                  <button onclick="accionSol(2);"  data-toggle="tooltip" title="Prórroga de solicitud" type="button" class="btn btn-success"><i class="fa  fa-retweet"></i></button>
				         @endif
				                  <button onclick="accionSol(3);"  data-toggle="tooltip" title="Justificaciones de prórroga" type="button" class="btn btn-success"><i class="fa  fa-tag"></i></button>
      </div>
 @else

  @if(!empty($estadoPart))
             @if($estadoPart[0]==1)  
			
				              <div class="btn-group">
				           @if($info->idEstado == 2 || $info->idEstado == 3 || $info->idEstado == 11)
				                  <button onclick="accionSol(1);" data-toggle="tooltip" title="Reportar asignación" type="button" class="btn btn-success"><i class="fa  fa-bullhorn"></i></button>
				                  <button onclick="accionSol(2);"  data-toggle="tooltip" title="Prórroga de solicitud" type="button" class="btn btn-success"><i class="fa  fa-retweet"></i></button>
				         @endif
				                  <button onclick="accionSol(3);"  data-toggle="tooltip" title="Justificaciones de prórroga" type="button" class="btn btn-success"><i class="fa  fa-tag"></i></button>
    @endif @endif

@endif
  </div><!-- /.col-sm-6 col-md-12 -->
</div>