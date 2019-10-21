<?php namespace App\models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class TipoTratamiento extends Model {

	protected $table = 'dnm_catalogos.cat_tratamiento';
	public $incrementing = false;
    protected $primaryKey = 'idTipoTratamiento';
    public $timestamps = false;
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

}
