<?php namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;
use App\UserOptions;
use Debugbar;
class VerifyPermissions {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$permissions = $this->getPermissions($request);
		$hasPermission = false;
		
		if (count($permissions)	< 2) {
			$hasPermission = $this->compareOneSinglePermission($permissions[0]);
		}else{

			$hasPermission = $this->compareMultiplePermission($permissions);

		}
		
		//Si el usuario es Admin Desarrollo permite el acceso
		if ( (!$hasPermission) && !(UserOptions::verifyOption(Auth::user()->idUsuario,0)) ) {
			if($request->ajax())
			{
				return response()->json(['status' => 401, 'message' => "No posee los privilegios suficientes para realizar esta acción", "redirect" => '']);
			}
			else
			{
				
				return response()->view('errors.401');
			}
			            
        }
        else
        {
        	return $next($request);		
        }
        
	}

	private function getPermissions($request)
	{
	    $actions = $request->route()->getAction();

	    return $actions['permissions'];
	}

	private function compareOneSinglePermission($one_permission)
	{	
			 
		$user_permission = UserOptions::verifyOption(Auth::user()->idUsuario,$one_permission);
		if($user_permission)
			return true;
		else
			return false;
	}
	private function compareMultiplePermission($permissions)
	{	
		$validate = false;		

		foreach ($permissions as $key => $value) {
			$user_permission = UserOptions::verifyOption(Auth::user()->idUsuario,$value);
			if($user_permission)
				$validate = true;
			else
				$validate = false;		
		}
		
		return $validate;
	}
}
