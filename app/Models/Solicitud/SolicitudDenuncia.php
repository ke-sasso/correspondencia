<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class SolicitudDenuncia extends Model {

	protected $table = 'dnm_correspondencia_si.COR.solicitudDenuncia';
    protected $primaryKey = 'idDenuncia';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}

