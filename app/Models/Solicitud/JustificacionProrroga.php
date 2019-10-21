<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class JustificacionProrroga extends Model {

	protected $table = 'dnm_correspondencia_si.COR.justificacionesProrroga';
    protected $primaryKey = 'idItem';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}

