<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
use DB;
class Empleados extends Model {

	protected $table = 'dnm_rrhh_si.RH.empleados';
    protected $primaryKey = 'idEmpleado';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

	public static function plazaEmpleado($id){
    $consulta = DB::connection('sqlsrv')->table('dnm_rrhh_si.RH.empleados as T1')
     ->join('dnm_rrhh_si.RH.plazasFuncionales as T2','T1.idPlazaFuncional','=','T2.idPlazaFuncional')
     ->where('T1.idEmpleado','=',$id)
     ->select('T2.nombrePlaza')->first();

     return $consulta;
	}


}
