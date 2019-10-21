<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class Departamento extends Model {

	protected $table = 'dnm_catalogos.cat.departamento';
    protected $primaryKey = 'idDepartamento';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}
