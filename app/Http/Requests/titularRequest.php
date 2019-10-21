<?php namespace App\Http\Requests;
use App\Http\Requests\Request;

class titularRequest extends Request {


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
		    'nombreTitular' => 'required',
		    'telefono1' => 'required|min:9',

		    ];

	}
	public function attributes()
	{
    	return[
        	'nombreTitular' 			=> 'Nombre',
			'telefono1' 			=> 'Telefono',
			
    	];
    }


}