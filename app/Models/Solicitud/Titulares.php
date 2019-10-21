<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class Titulares extends Model {

	protected $table = 'dnm_correspondencia_si.COR.Titulares';
    protected $primaryKey = 'idTitular';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}

