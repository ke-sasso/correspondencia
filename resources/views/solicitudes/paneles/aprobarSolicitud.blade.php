@if(in_array(472, $permisos, true))
  <!--
                  <div class="right-content">
                  <div class="btn-group">
                    <button class="btn btn-success btn-sm btn-rounded-lg dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu success pull-right square margin-list" role="menu">
                      @if($info->idEstado==2 || $info->idEstado==3)
                      <li><a  onclick="aprobarSolicitud({{$info->idSolicitud}})" ><i class="fa   fa-check"></i>APROBAR</a></li>
                      @endif
                      @if($info->idEstado==4)
                      <li><a  onclick="notificarSolicitud({{$info->idSolicitud}})" ><i class="fa   fa-bell"></i> NOTIFICAR</a></li>
                      @endif
                      </ul>
                  </div>
                  </div>
    -->
@endif 
@if(in_array(470, $permisos, true))
                   @if($info->idEstado==9)
                  <div class="right-content">
                  <div class="btn-group">
                    <button class="btn btn-success btn-sm btn-rounded-lg dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu success pull-right square margin-list" role="menu">
                       <li><a onclick="notificarSolicitud({{$info->idSolicitud}})" ><i class="fa  fa-bullhorn"></i>ENTREGAR</a></li>
                    </ul>
                  </div>
                  </div>
                 @endif
@endif