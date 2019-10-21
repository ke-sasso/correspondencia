<?php namespace App\models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model {

	protected $table = 'dnm_catalogos.cat_documentos_id';
	public $incrementing = false;
    protected $primaryKey = 'idTipoDocumento';
    public $timestamps = false;
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

}
