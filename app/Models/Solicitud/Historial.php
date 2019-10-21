<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class Historial extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudHistorial';
    protected $primaryKey = 'idHistorial';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}

