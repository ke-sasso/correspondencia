<?php
/*
	This method include all Routes archives from Routes Directory
	By Kevin Alvarez
*/
foreach (new DirectoryIterator(__DIR__.'/Routes') as $file)
{
    if (!$file->isDot() && !$file->isDir() && $file->getFilename() != '.gitignore')
    {
        require_once __DIR__.'/Routes/'.$file->getFilename();
        //require_once __DIR__.'/Routes/'.$file->getFilename();
    }
}


Route::get('/', ['as' => 'doLogin', 'uses' => 'CustomAuthController@getLogin']);
Route::post('/login', ['as' => 'login', 'uses' =>'CustomAuthController@postLogin']); 
Route::get('/logout', 'CustomAuthController@getLogout'); 
Route::get('cfg/menu', 'InicioController@cfgHideMenu');

Route::get('/auth/solicitud/{idSolicitud}/{idComentario}', ['uses' => 'autorizadorController@autorizarComentario']);
Route::post('/comentario/solicitud', ['uses' => 'autorizadorController@enviarComentario']);

Route::group(['middleware' => ['auth' , 'verifypermissions'],
	'permissions' => [469]], function() {

	Route::get('/inicio',[
	'as' => 'doInicio', 
	'uses' => 'InicioController@index']);    

});

   
