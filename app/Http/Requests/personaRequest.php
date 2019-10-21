<?php namespace App\Http\Requests;
use App\Http\Requests\Request;

class personaRequest extends Request {


	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{

		return [
		    'nit' => 'required|min:17',
		    'tipoDoc' => 'required', 
		    'nombres' => 'required',
		    'apellidos' => 'required',
		    'tel1' => 'required'
		    
		  

		    ];

	}
	public function attributes()
	{
    	return[
        	'nit' 			=> 'nit',
        	'tipoDoc' => 'tipo de documento',
        	 'nombres' => 'nombres',
		    'apellidos' => 'apellidos',
		    'tel1' => 'telefono'
			
    	];
    }


}