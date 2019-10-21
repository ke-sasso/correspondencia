<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class FechaRespuesta extends Model {

	protected $table = 'dnm_correspondencia_si.COR.fechaRespuesta';
    protected $primaryKey = 'idfechaRespuesta';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}