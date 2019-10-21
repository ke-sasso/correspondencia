<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
use DB;
 
class UsuarioEntrega extends Model {

	protected $table = 'dnm_correspondencia_si.COR.usuarioEntregado';
    protected $primaryKey = 'idEntregado';
	public $timestamps = false;
	protected $connection = 'sqlsrv';

}
