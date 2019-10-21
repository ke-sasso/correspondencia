<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class EstadoSolicitud extends Model {

	protected $table = 'dnm_correspondencia_si.COR.estadoSolicitud';
    protected $primaryKey = 'idEstado';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}
