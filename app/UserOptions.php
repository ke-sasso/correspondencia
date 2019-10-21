<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class UserOptions extends Model {
	
	protected $table = 'dnm_catalogos.sys_usuario_roles';
	protected $primaryKey = 'codUsuario';
	protected $timestap = false;

	public static function vrfOpt($id_opcion){
		if (UserOptions::where('codUsuario',Auth::user()->idUsuario)->where('codOpcion',[0,$id_opcion])->count() > 0)
			return true;
		else
			return false;
	}

	public static function verifyOption($id_usuario,$id_opcion){
		if (UserOptions::where('codUsuario',$id_usuario)->where('codOpcion',0)->count() > 0)
			return true;
		elseif (UserOptions::where('codUsuario',$id_usuario)->where('codOpcion',$id_opcion)->count() > 0) {
			return true;
		}
		else
			return false;
	}

	public static function getAutUserOptions(){
		
		
		$opciones = UserOptions::join('dnm_catalogos.sys_opciones','codOpcion','=','idOpcion')
		->where('codUsuario',Auth::user()->idUsuario)
        ->where('idPerfil','=',20)
        ->select('codOpcion')->pluck('codOpcion')->toArray();
        return $opciones;
	}
    
    public static function getAsesorJuridico(){

    	$empleados = UserOptions::join('dnm_catalogos.sys_usuarios','codUsuario','=','idUsuario')
    	->where('codOpcion',478)
    	->where('idPerfil',20)
        ->select('idEmpleado')->pluck('idEmpleado')->toArray();
        return $empleados;
	}
	 public static function getAsesorDenuncia(){

    	$empleados = UserOptions::join('dnm_catalogos.sys_usuarios','codUsuario','=','idUsuario')
    	->where('codOpcion',486)
    	->where('idPerfil',20)
        ->select('idEmpleado')->pluck('idEmpleado')->toArray();
        return $empleados;
	}
	public static function getAsesorMedico(){

    	$empleados = UserOptions::join('dnm_catalogos.sys_usuarios','codUsuario','=','idUsuario')
    	->where('codOpcion',479)
    	->where('idPerfil',20)
        ->select('idEmpleado')->pluck('idEmpleado')->toArray();
        return $empleados;
	}
	public static function getcorreosSEIPS(){

    	$empleados = UserOptions::join('dnm_catalogos.sys_usuarios','codUsuario','=','idUsuario')
    	->where('codOpcion',487)
    	->where('idPerfil',20)
        ->select('correo')->pluck('correo')->toArray();
        return $empleados;
	}


	public static function getcorreosAsesorDenuncia(){

    	$empleados = UserOptions::join('dnm_catalogos.sys_usuarios','codUsuario','=','idUsuario')
    	->where('codOpcion',486)
    	->where('idPerfil',20)
        ->select('correo')->pluck('correo')->toArray();
        return $empleados;
	}

	public static function getcorreosArchivo(){

    	$empleados = UserOptions::join('dnm_catalogos.sys_usuarios','codUsuario','=','idUsuario')
    	->where('codOpcion',490)
    	->where('idPerfil',20)
        ->select('correo')->pluck('correo')->toArray();
        return $empleados;
	}
	
	public static function getDirectora(){

    	$empleados = UserOptions::join('dnm_catalogos.sys_usuarios','codUsuario','=','idUsuario')
    	->where('codOpcion',480)
    	->where('idPerfil',20)
        ->select('idEmpleado')->first();
        return $empleados;
	}
}
