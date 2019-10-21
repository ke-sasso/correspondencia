<?php namespace App\models\Catalogo;

use Illuminate\Database\Eloquent\Model;

class PersonaNatural extends Model {

	protected $table = 'dnm_catalogos.dnm_persona_natural';
	public $incrementing = false;
    protected $primaryKey = 'nitNatural';
    public $timestamps = false;
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';

}
