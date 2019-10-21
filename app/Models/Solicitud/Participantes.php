<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
use DB;
use user;
use Auth;
use App\UserOptions;
use App\Models\Solicitud\PersonaParticipante;
class Participantes extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudParticipantes';
    protected $primaryKey = 'idParticipante';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

    public function comentarios()
    {
        return $this->hasMany('App\Models\Solicitud\Comentarios', 'idParticipante', 'idParticipante');
    }
      public static function getEmpleadosJefe(){
     return DB::connection('sqlsrv')->table('dnm_rrhh_si.RH.empleados AS T1')
       // ->join('dnm_rrhh_si.RH.jefes AS T2','T1.idPlazaFuncional','=','T2.idPlazaFuncional')
        ->leftJoin('dnm_rrhh_si.RH.plazasFuncionales AS T3', 'T1.idPlazaFuncional', '=','T3.idPlazaFuncional')
        ->leftJoin('dnm_rrhh_si.RH.unidades AS T4', 'T3.idUnidad', '=','T4.idUnidad')
        ->join('dnm_correspondencia_si.COR.participantes as T5','T1.idEmpleado','=','T5.idEmpleado')
		->select( 'T1.idEmpleado as idEmp', 'T1.nombresEmpleado', 'T1.apellidosEmpleado', 'T4.nombreUnidad','T4.prefijo')
		->orderby('nombresEmpleado')
    ->where('T5.estado',1)
    ->distinct()
		->get();

	}
    public static function getResponsablesAsesores($id){
      $lista = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudParticipantes')
       ->where('idSolicitud','=',$id)
       ->whereIn('idEstado',[1,3,4])
       ->select('idEmpleado')->pluck('idEmpleado')->toArray();
       return $lista;
  }
    public static function getAsesores($id){
      $lista = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudParticipantes')
       ->where('idSolicitud','=',$id)
       ->where('idEstado',4)
       ->select('idEmpleado')->pluck('idEmpleado')->toArray();
       return $lista;
  }


	 public static function getEmpleadosSolicitud($id){
     return DB::connection('sqlsrv')->table('dnm_rrhh_si.RH.empleados AS T1')
        //->join('dnm_rrhh_si.RH.jefes AS T2','T1.idPlazaFuncional','=','T2.idPlazaFuncional')
        ->leftJoin('dnm_rrhh_si.RH.plazasFuncionales AS T3', 'T1.idPlazaFuncional', '=','T3.idPlazaFuncional')
        ->leftJoin('dnm_rrhh_si.RH.unidades AS T4', 'T3.idUnidad', '=','T4.idUnidad')
        ->join('dnm_correspondencia_si.COR.solicitudParticipantes AS T5', 'T1.idEmpleado','=','T5.idEmpleado')
        ->join('dnm_correspondencia_si.COR.participantes as T6','T1.idEmpleado','=','T6.idEmpleado')
		->select( 'T1.idEmpleado as idEmp', 'T1.nombresEmpleado', 'T1.apellidosEmpleado', 'T4.nombreUnidad','T4.prefijo','T5.idParticipante','T5.fechaRespuesta','T5.fechaCreacion','T5.caso','T5.idEstado')
		->distinct()
		->where('T5.idSolicitud','=',$id)
		->where('T5.idEstado','=',1)
		->get();

	}
	 public static function fechaRespuestaParticipante($id){
     return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudes AS T1')
        ->join('dnm_correspondencia_si.COR.solicitudParticipantes AS T2', 'T1.idSolicitud','=','T2.idSolicitud')
		->where('T2.idSolicitud','=',$id)
		->min('T2.fechaRespuesta');
	}

    public static function getEstado($idSolicitud){
      $permisos = UserOptions::getAutUserOptions();

      if(in_array(497, $permisos, true)){
          //consultamos al jefe del asistente
           $idJefe = PersonaParticipante::where('idEmpleadoAsistente',Auth::user()->idEmpleado)->first();
           $estadoPart = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudParticipantes')
           ->where('idSolicitud','=',$idSolicitud)
           ->where('idEmpleado','=',$idJefe->idEmpleado)
           ->select('idEstado')->pluck('idEstado')->toArray();
     }else{
        $estadoPart = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudParticipantes')
       ->where('idSolicitud','=',$idSolicitud)
       ->where('idEmpleado','=',Auth::user()->idEmpleado)
       ->select('idEstado')->pluck('idEstado')->toArray();

     }
      return $estadoPart;
    }
     public static function getCaso($idSolicitud){
      $permisos = UserOptions::getAutUserOptions();

      if(in_array(497, $permisos, true)){
        //consultamos al jefe del asistente
          $idJefe = PersonaParticipante::where('idEmpleadoAsistente',Auth::user()->idEmpleado)->first();
          $casoPart = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudParticipantes')
           ->where('idSolicitud','=',$idSolicitud)
           ->where('idEmpleado','=',$idJefe->idEmpleado)
            ->select('caso')->pluck('caso')->toArray();

     }else{
         $casoPart = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudParticipantes')
        ->where('idSolicitud','=',$idSolicitud)
        ->where('idEmpleado','=',Auth::user()->idEmpleado)
        ->select('caso')->pluck('caso')->toArray();

     }
      return $casoPart;

    }
      public static function getPermiso($idSolicitud){
      $permisoPart = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudParticipantes')
       ->where('idSolicitud','=',$idSolicitud)
       ->where('idEmpleado','=',Auth::user()->idEmpleado)
      ->select('permiso')->pluck('permiso')->toArray();
       return $permisoPart;

    }


}

