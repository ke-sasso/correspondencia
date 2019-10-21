<?php namespace App\Models\Solicitud;

use Illuminate\Database\Eloquent\Model;
use DB;

class vwSolicitudes extends Model {

	protected $table = 'dnm_correspondencia_si.COR.vwSolicitudes';
    protected $primaryKey = 'idSolicitud';
	public $timestamps = false;
	protected $connection = 'sqlsrv';

	 public static function listParticipantes($idEmpleado){
     return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.vwSolicitudes AS T1')
        ->join('dnm_correspondencia_si.COR.solicitudParticipantes AS T2','T1.idSolicitud ','=','T2.idSolicitud')
        ->where('T2.idEmpleado','=',$idEmpleado)
        ->select('T1.idSolicitud','T1.nitSolicitante','T1.fechaRecepcion','T1.asunto','T1.descripcion','T1.noPresentacion','T1.idEstado','T1.nombreEstado','T1.fechaRespuesta','T1.idfechaRespuesta','T1.fechaCreacion','T1.dias','T1.fechaDetalle','T1.idTipo','T1.idClasificacion');

	}
	public static function listAdmin(){
     return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.vwSolicitudes AS T1')
        ->select('T1.*')
        ->where('T1.idTipo',1);

	}

    public static function listDenuncia(){

     /* $ciudadana = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudes AS T1')
         ->join('dnm_correspondencia_si.COR.estadoSolicitud AS T2','T1.idEstado','T2.idEstado')
         ->join('dnm_correspondencia_si.COR.denunciaCiudana AS T3','T1.idSolicitud','T3.idDenuncia')
         ->select('T1.idSolicitud','T1.idTipo','T1.asunto','T1.descripcion','T1.idEstado','T2.nombreEstado','T3.fechaEvento','T1.noPresentacion')
          ->where('T1.idTipo',3);*/

     return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudes AS T1')
         ->join('dnm_correspondencia_si.COR.estadoSolicitud AS T2','T1.idEstado','T2.idEstado')
         ->join('dnm_correspondencia_si.COR.solicitudDenuncia AS T3','T1.idSolicitud','T3.idDenuncia')
         ->select('T1.idSolicitud','T1.idTipo','T1.asunto','T1.descripcion','T1.idEstado','T2.nombreEstado','T3.fechaEvento','T1.noPresentacion')
          ->whereIn('T1.idTipo',[2,3]);
         // ->union($ciudadana);


    }

     public static function listDenunciaParticipante($idEmpleado){

    /* $ciudadana = DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudes AS T1')
         ->join('dnm_correspondencia_si.COR.estadoSolicitud AS T2','T1.idEstado','T2.idEstado')
         ->join('dnm_correspondencia_si.COR.denunciaCiudana AS T3','T1.idSolicitud','T3.idDenuncia')
        ->join('dnm_correspondencia_si.COR.solicitudParticipantes AS T4','T1.idSolicitud ','=','T4.idSolicitud')
          ->where('T4.idEmpleado','=',$idEmpleado)
         ->select('T1.idSolicitud','T1.idTipo','T1.asunto','T1.descripcion','T1.idEstado','T2.nombreEstado','T3.fechaEvento','T1.noPresentacion')
          ->where('T1.idTipo',3);*/

     return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.solicitudes AS T1')
         ->join('dnm_correspondencia_si.COR.estadoSolicitud AS T2','T1.idEstado','T2.idEstado')
         ->join('dnm_correspondencia_si.COR.solicitudDenuncia AS T3','T1.idSolicitud','T3.idDenuncia')
        ->join('dnm_correspondencia_si.COR.solicitudParticipantes AS T4','T1.idSolicitud ','=','T4.idSolicitud')
          ->where('T4.idEmpleado','=',$idEmpleado)
         ->select('T1.idSolicitud','T1.idTipo','T1.asunto','T1.descripcion','T1.idEstado','T2.nombreEstado','T3.fechaEvento','T1.noPresentacion')
          ->whereIn('T1.idTipo',[2,3]);
         // ->union($ciudadana);


    }
}
