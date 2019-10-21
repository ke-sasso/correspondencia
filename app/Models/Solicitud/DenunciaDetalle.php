<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class DenunciaDetalle extends Model {

	protected $table = 'dnm_correspondencia_si.COR.denunciaDetalle';
    protected $primaryKey = 'idDetalle';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}
