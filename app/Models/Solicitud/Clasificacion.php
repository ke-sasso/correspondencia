<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class Clasificacion extends Model {

	protected $table = 'dnm_correspondencia_si.COR.clasificacion';
    protected $primaryKey = 'idClasificacion';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}
