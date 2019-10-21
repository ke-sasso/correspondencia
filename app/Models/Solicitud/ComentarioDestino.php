<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
use DB;
 
class ComentarioDestino extends Model {

	protected $table = 'dnm_correspondencia_si.COR.comentarioDestino';
    protected $primaryKey = 'idDestino';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';


	 public static function getComentariosDest($idSolicitud){
      $permisoPart = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.comentarioDestino')
       ->where('idSolicitud','=',$idSolicitud)
      ->select('idComentario')->pluck('idComentario')->toArray();
       return $permisoPart;
    }

     public static function getPartDestino($idComentario){
      $part = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.comentarioDestino')
       ->where('idComentario','=',$idComentario)
       ->select('idParticipante')->pluck('idParticipante')->toArray();
       return $part;
    }


}
