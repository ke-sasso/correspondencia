<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
use DB;
class SolicitudesTitular extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudTitulares';
    protected $primaryKey = 'idSolicitudTit';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

	 public static function infoTitulares($idSoli){
     return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudTitulares AS T1')
        ->join('dnm_correspondencia_si.COR.titulares AS T2','T1.idTitular','=','T2.idTitular')
        ->where('T1.idSolicitud','=',$idSoli)
		->select('T1.idSolicitudTit','T2.nombreTitular','T2.emailContacto','T2.telefonosContacto','T2.idTitular')
		->get();

	}

}

