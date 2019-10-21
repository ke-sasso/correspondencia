<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class SolicitudSeguimiento extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudSeguimiento';
    protected $primaryKey = 'idRequest';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}
