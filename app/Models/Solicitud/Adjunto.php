<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class Adjunto extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudAdjunto';
    protected $primaryKey = 'idAdjunto';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}
