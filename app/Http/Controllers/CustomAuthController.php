<?php namespace App\Http\Controllers;

use App;
use Auth;  
use Session; 
use App\User;
use App\UserOptions;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Debugbar;
use Config;
use Illuminate\Http\Request;

class CustomAuthController extends Controller {

	public function getLogin(){
		$data = ['title' 			=> 'Inicio'
				,'subtitle'			=> ''];
		//Verificamos si ya esta logueado de lo contrario se redirige al login
		if(Auth::check()){
			Debugbar::disable();
			return view('inicio.index',$data); 
		}else{

			Debugbar::disable();
			
			return view('users.login'); 	
		}
    }  
  	
    public function postLogin(Request $request) {  
        //Obtenemos el usuario que se loguea si es que existe
        $response = '';
    	if(!$request->txtUsuario || $request->txtUsuario == '')
    	{
    		
    		$response = ['status' => 409, 'message' => 'Debe Ingresar su Usuario', "redirect" => ''];
    	}
    	elseif (!$request->txtPwd || $request->txtPwd == '') {
    		
    		$response = ['status' => 409, 'message' => 'Debe Ingresar su Constraseña', "redirect" => ''];
    	}
    	else
    	{
		    $user = User::where('idUsuario', $request->txtUsuario)
    		->where('password', md5($request->txtPwd))
    		->where('activo', 'A')->first();
   	
	        //Verificamos si el usuario existe y cumple las condiciones
	        if($user){
	            if(UserOptions::verifyOption($request->txtUsuario,469)){
			    	//Guardamos Logueamos al usuario			    	
			    	
				    Auth::login($user);
				    $response = ['status' => 200, 'message' => 'ok', "redirect" => route('doInicio')];
				    
			    }else{
					$response = ['status' => 409, 'message' => 'No posee los privilegios para el ingreso al sistema', "redirect" => ''];
			    }
			}else{
				$response = ['status' => 409, 'message' => 'Las Credenciales de Usuario o Contraseña son incorrectas', "redirect" => ''];
			}
    	}

    	return response()->json($response);

    }  
    public function getLogout()
	{
		//Deslogueamos al usuario
		Auth::logout();
		//Eliminamos de session la variable
		Session::forget('PERMISOS');
		//Redireccion a ruta inicial
		return redirect()->route('doLogin');
	}
	
}
