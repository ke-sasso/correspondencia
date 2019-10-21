<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
use DB;
 
class Comentarios extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudComentarios';
    protected $primaryKey = 'idComentario';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';


	 public static function listComentarios($idSoli){
	 	return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudComentarios AS T1')
		  ->join('dnm_correspondencia_si.COR.solicitudParticipantes as T2','T1.idParticipante','=','T2.idParticipante')
		  ->join('dnm_rrhh_si.RH.empleados AS T3','T2.idEmpleado','=','T3.idEmpleado')
	     ->where('T1.idSolicitud','=',$idSoli)
	     ->where('T1.idPadre','=',NULL)
	     ->orwhere('T1.idPadre','=','')
	     ->select('idComentario','comentario','T1.fechaCreacion','archivo','tipoArchivo','nombresEmpleado','apellidosEmpleado','avatar','tipoImagen')
	     ->orderBy('T1.fechaCreacion', 'asc')
	     ->get();
	 }

	 public static function listComentariosColaboradores($idSoli){

	 	$comen1 = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.comentarioDestino')->where('idSolicitud','=',$idSoli)->select('idComentario')->pluck('idComentario');

	 	return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudComentarios AS T1')
		  ->join('dnm_correspondencia_si.COR.solicitudParticipantes as T2','T1.idParticipante','=','T2.idParticipante')
		  ->join('dnm_rrhh_si.RH.empleados AS T3','T2.idEmpleado','=','T3.idEmpleado')
	     ->whereIn('T1.idComentario',$comen1)
	     ->select('T1.idComentario','comentario','T1.fechaCreacion','archivo','tipoArchivo','nombresEmpleado','apellidosEmpleado','avatar','tipoImagen')
	     ->orderBy('T1.fechaCreacion', 'asc')
	     ->get();
	 }


 public static function comentariosHijos($idComentario){
	 	return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudComentarios AS T1')
		  ->join('dnm_correspondencia_si.COR.solicitudParticipantes as T2','T1.idParticipante','=','T2.idParticipante')
		  ->join('dnm_rrhh_si.RH.empleados AS T3','T2.idEmpleado','=','T3.idEmpleado')
	      ->where('T1.idPadre','=',$idComentario)
	     ->select('T1.idComentario','T1.comentario','T1.fechaCreacion','archivo','tipoArchivo','nombresEmpleado','apellidosEmpleado','avatar','tipoImagen')
	     ->orderBy('T1.fechaCreacion', 'asc')
	     ->get();
	 }



	 public static function comentarioParticipante($id){
	 	return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudComentarios AS T1')
		  ->join('dnm_correspondencia_si.COR.solicitudParticipantes as T2','T1.idParticipante','=','T2.idParticipante')
		  ->join('dnm_rrhh_si.RH.empleados AS T3','T2.idEmpleado','=','T3.idEmpleado')
	     ->where('T1.idComentario','=',$id)
	     ->select('T1.idComentario','T1.comentario','T1.fechaCreacion','T1.archivo','T1.tipoArchivo','nombresEmpleado','apellidosEmpleado','T1.idEstado')->first();
	 }


	  public static function responsableSolicitud($idSolicitud,$idParticipante){
	  	return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.comentarioDestino as T1')
	      ->whereIn('T1.idComentario',$idSolicitud)
	     ->where('T1.idParticipante','=',$idParticipante)
	     ->select('T1.idComentario')->first();
	 	/*return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudComentarios AS T1')
		  ->join('dnm_correspondencia_si.COR.comentarioDestino as T2','T1.idComentario','=','T2.idComentario')
		  ->join('dnm_correspondencia_si.COR.solicitudParticipantes  AS T3','T1.idParticipante','=','T3.idParticipante')
	      ->where('T1.idSolicitud','=',$idSolicitud)
	     ->where('T2.idParticipante','=',$idParticipante)
	     ->select('T3.idEmpleado')->first();*/
	 }

	 public static function comentarioParticipante2($id){
	 	return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudParticipantes as T2')
		 ->join('dnm_rrhh_si.RH.empleados AS T3','T2.idEmpleado','=','T3.idEmpleado')
		 ->join('dnm_correspondencia_si.COR.participantes as T4','T2.idEmpleado','=','T4.idEmpleado')
	     ->whereIn('T2.idParticipante',$id)
	     ->select('nombresEmpleado','apellidosEmpleado','T2.idEmpleado','T4.idEmpleadoAsistente')->get();
	 }


}

