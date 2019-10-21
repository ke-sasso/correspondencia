<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class Solicitudes extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudes';
    protected $primaryKey = 'idSolicitud';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';
	
}

