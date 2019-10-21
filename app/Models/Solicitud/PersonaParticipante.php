<?php namespace App\Models\Solicitud;
use Illuminate\Database\Eloquent\Model;
use DB;
class PersonaParticipante extends Model {

	protected $table = 'dnm_correspondencia_si.COR.participantes';
    protected $primaryKey = 'idPersonaParticipante';
	 public $timestamps = false;
	protected $connection = 'sqlsrv';


    

}

