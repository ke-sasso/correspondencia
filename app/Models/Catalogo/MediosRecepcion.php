<?php namespace App\models\Catalogo;

use Illuminate\Database\Eloquent\Model;
use DB;

class MediosRecepcion extends Model {

	protected $table = 'dnm_correspondencia_si.COR.mediosRecepcion';
	protected $primaryKey = 'idMedio';
	public $timestamps = false;
	protected $connection = 'sqlsrv';
	

}



