<?php namespace App\models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class DepartamentosCat extends Model {

	protected $table = 'dnm_catalogos.cat_departamentos';
	public $incrementing = false;
    protected $primaryKey = 'idDepartamento';
    public $timestamps = false;
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

}
