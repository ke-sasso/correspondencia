<?php namespace App\models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class MunicipiosCat extends Model {

	protected $table = 'dnm_catalogos.cat_municipios';
	public $incrementing = false;
    protected $primaryKey = 'idMunicipio';
    public $timestamps = false;
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

}
