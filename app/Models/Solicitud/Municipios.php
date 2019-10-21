<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
 
class Municipios extends Model {

	protected $table = 'dnm_catalogos.cat.municipios';
    protected $primaryKey = 'idMunicipio';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';

}
