<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class SolicitudAcciones extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudesAcciones';
    protected $primaryKey = 'idsolicitudAcciones';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}