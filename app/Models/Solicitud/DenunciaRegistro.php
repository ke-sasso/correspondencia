<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
use DB;
class DenunciaRegistro extends Model{

	protected $table = 'dnm_correspondencia_si.COR.denunciaRegistro';
    protected $primaryKey = 'idRegistro';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

     public static function listRegistroEstablecimientos($idSoli){
	 	return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaDetalle AS T1')
		  ->join('dnm_correspondencia_si.COR.denunciaRegistro as T2','T1.idRegistro','=','T2.idRegistro')
	     ->where('T1.idDenuncia','=',$idSoli)
	     ->where('T2.tipoRegistro','=',1)
	     ->select('T1.idDetalle','T2.nombreComercial','T2.direccion')
	     ->get();
	 }

	 public static function listRegistroProductos($idSoli){
	 	return DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaDetalle AS T1')
		  ->join('dnm_correspondencia_si.COR.denunciaRegistro as T2','T1.idRegistro','=','T2.idRegistro')
	     ->where('T1.idDenuncia','=',$idSoli)
	     ->where('T2.tipoRegistro','=',2)
	     ->select('T1.idDetalle','T2.nombreComercial','T2.titular','T2.fechaVencimiento','T2.noLote')
	     ->get();
	 }

}

