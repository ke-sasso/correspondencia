<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class Acciones extends Model {

	protected $table = 'dnm_correspondencia_si.COR.accion';
    protected $primaryKey = 'idAccion';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}
