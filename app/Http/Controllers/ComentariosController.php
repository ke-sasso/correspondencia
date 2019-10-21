<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use DB;
use Session;
use Redirect;
use File;

use GuzzleHttp\Client;
use Crypt;
use Response;
use DateTime;
use Config;
use Mail;
use Debugbar;
use Exception;
use App\UserOptions;
use App\User;
use App\Http\Requests;
use App\Http\Requests\solicitudRequest;
USE App\Models\Solicitud\EstadoSolicitud;
use App\Http\Requests\titularRequest;
use App\Http\Requests\personaRequest;
use App\Http\Requests\comentarioRequest;
use App\Http\Controllers\PdfController;
use App\Models\Catalogo\PersonaNatural;
use App\Models\Solicitud\Solicitudes;
use App\Models\Solicitud\Comentarios;
use App\Models\Solicitud\Participantes;
use App\Models\Solicitud\vwSolicitudes;
use App\Models\Solicitud\Adjunto;
use App\Models\Solicitud\Titulares;
use App\Models\Solicitud\Historial;
use App\Models\Solicitud\SolicitudesTitular;
use App\Models\Solicitud\Clasificacion;
use App\Models\Solicitud\Acciones;
use App\Models\Solicitud\FechaRespuesta;
use App\Models\Solicitud\SolicitudAcciones;
use App\Models\Solicitud\ComentarioDestino;
use App\Http\Controllers\Controller;
use App\Models\Solicitud\SolicitudSeguimiento;
use App\Models\Solicitud\PersonaParticipante;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;


class ComentariosController extends Controller{


 public function comentarioAsistencia($idSolicitud)
	{
		$data = ['title' 			=> 'Comentario asistencia'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
			 		['nom'	=>	'', 'url' => '#'],
				]];
        $id = Crypt::decrypt($idSolicitud);

	   $data['empleados']=Participantes::getEmpleadosJefe();
	   $data['responsablesAsesores']=Participantes::getResponsablesAsesores($id);
	   $data['idSolicitud'] = $id;
	   $data['tipo'] = 1; //PUEDE SUBIR ARCHIVO
		return view('solicitudes.comentarios.asistencia',$data);
	}
	 public function comentarioOpinion($idSolicitud)
	{
		$data = ['title' 			=> 'Comentario opinión'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
			 		['nom'	=>	'', 'url' => '#'],
				]];
        $id = Crypt::decrypt($idSolicitud);

	   $data['empleados']=Participantes::getEmpleadosJefe();
	   $data['responsablesAsesores']=Participantes::getResponsablesAsesores($id);
	   $data['idSolicitud'] = $id;
	   $data['tipo'] = 2; //SOLO PUEDE COMENTAR
		return view('solicitudes.comentarios.asistencia',$data);
	}

	   public function storeAsistencia(Request $request){


			$v = Validator::make($request->all(),[
	        	'emple'=>'required',
	        	'comentario'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'emple'=>'colaboradores'

		    ]);
			if ($v->fails())
		    {
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		        return $msg;
		    }
		    try {

		    	    $idPar = Participantes::where('idEmpleado','=',Auth::user()->idEmpleado)
		    	    ->where('idSolicitud','=',$request->idSoliAsistencia)->first();
                    if(!empty($idPar)){
                    	  $cod = $idPar->idParticipante;
                    }else{
                    			//CREAMOS UN PARTICIPANTE CON EL ROL DE ASISTENTE DE JEFE
                    	 	    $iddd = Auth::user()->idEmpleado;
                    	      	$part =  new Participantes();
						    	$part->idSolicitud = $request->idSoliAsistencia;
				                $part->idEmpleado = $iddd;
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=7;
								$part->caso=0;
						    	$part->save();

						    	$cod = $part->idParticipante;
                    }

                	$comen = new Comentarios();
                	$comen->comentario = $request->comentario;
                	$comen->idSolicitud = $request->idSoliAsistencia;
                	$comen->idParticipante = $cod;
                	$comen->tipoComentario=1;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();

				 $soli = Solicitudes::Find($request->idSoliAsistencia);
				 $soli->idEstado=3;
				 $soli->save();

				 $soliSegui = new  SolicitudSeguimiento();
				 $soliSegui->idSolicitud=$request->idSoliAsistencia;
				 $soliSegui->estadoSolicitud=3;
		         $soliSegui->observaciones = 'Nuevo comentario:'.$request->comentario;
			     $soliSegui->fechaCreacion=Carbon::now();
				 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 $soliSegui->save();

				  //-------------SUBIR ARCHIVO COMENTARIO-----------
		    	    $idComentario = $comen->idComentario;
		    	    $idSolicitud = $comen->idSolicitud;
				    $nombre= 'comentarios';
		      		$urlPrincipal = Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$idSolicitud;
				    $file= $request->file('fileAsistencia');

				    if(!empty($file)){
					$filesystem= new Filesystem();
				    if($filesystem->exists($path)){

					    if($filesystem->isWritable($path)){
						$carpeta=$path.'\\'.$nombre;
						File::makeDirectory($carpeta, 0777, true, true);
						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);


						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();
					   }else{
					   		DB::rollback();
							return " Error al subir el archivo ";
					   }


				  }else{

				  	    $carpeta=$path.'\\'.$idSolicitud.'\\'.$nombre;
						File::makeDirectory($carpeta, 0777, true, true);
						$name= $file->getClientOriginalName(); ;
						$type = $file->getMimeType();
						$file->move($carpeta,$name);
						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();
				  }
				}
				//----------------------------------

				$listPart = $request->emple;
		    	for($i=0;$i<count($listPart); $i++){

                if(Participantes::where('idSolicitud',$request->idSoliAsistencia)->where('idEmpleado',$listPart[$i])->whereIn('idEstado',[1,2])->count()==0){
		    	$part =  new Participantes();
		    	$part->idSolicitud = $request->idSoliAsistencia;
                $part->idEmpleado = $listPart[$i];
                $part->usuarioCreacion = Auth::user()->idUsuario;
				$part->fechaCreacion = Carbon::now();
				$part->idEstado=2;
				$part->caso=0;
				$part->permiso=0;
		    	$part->save();
		        }else{
                $part =  Participantes::where('idSolicitud',$request->idSoliAsistencia)->where('idEmpleado',$listPart[$i])->first();
               //$part->idEstado=2;
				$part->caso=0;
				$part->permiso=0;
				$part->usuarioModificacion = Auth::user()->idUsuario;
				$part->fechaModificacion = Carbon::now();
		    	$part->save();
		    	}


                $dest = new ComentarioDestino();
                $dest->idComentario = $comen->idComentario;
                $dest->idParticipante = $part->idParticipante;
                $dest->idPadre = $cod;
                $dest->idSolicitud= $request->idSoliAsistencia;
                $dest->usuarioCreacion = Auth::user()->idUsuario;
				$dest->fechaCreacion = Carbon::now();
                $dest->save();

		    	 $empCorreo = User::where('idEmpleado',$listPart[$i])->first();
		    	 $correo = $empCorreo->correo;
		    	 $data['soli'] = Solicitudes::find($request->idSoliAsistencia);
		    	 if(!empty($correo)){
		    	 	                 if($request->tipo==1){
				                     Mail::send('emails.asistencia',$data,function($msj) use ($data,$correo){
		                             $msj->subject('Nueva asistencia en solicitud de correspondencia');
					                 $msj->to($correo);
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
				                 }else{
  								    Mail::send('emails.opinion',$data,function($msj) use ($data,$correo){
		                             $msj->subject('Nueva asistencia en solicitud de correspondencia');
					                 $msj->to($correo);
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });

				                 }
			     }
			       $asistente=PersonaParticipante::where('idEmpleado',$listPart[$i])->first();
			      if(!empty($asistente->idEmpleadoAsistente)){
			     	$asiempCorreo = User::where('idEmpleado',$asistente->idEmpleadoAsistente)->first();
			     	$correo2 = $asiempCorreo->correo;
			     		 if(!empty($correo2)){
		    	 	                 if($request->tipo==1){
				                     Mail::send('emails.asistencia',$data,function($msj) use ($data,$correo2){
		                             $msj->subject('Nueva asistencia en solicitud de correspondencia');
					                 $msj->to($correo2);
		                                });
				                 }else{
  								    Mail::send('emails.opinion',$data,function($msj) use ($data,$correo2){
		                             $msj->subject('Nueva asistencia en solicitud de correspondencia');
					                 $msj->to($correo2);
		                                });

				                 }
			    		  }
                  	}
			  }//cierre foreach
			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
		    return response()->json(['state' => 'success']);

	}

	public function storeComentarioColaborador(Request $request){
		$v = Validator::make($request->all(),[
	        	'comentarioColaborador'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		    'comentarioColaborador' => 'comentario'

		    ]);
			if ($v->fails())
		    {
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		      Session::put('msnError1', 'El campo comentario es requerido');
		  return redirect('solicitud/ver-solicitud/'.Crypt::encrypt($request->idSoli));
		    }

        DB::connection('sqlsrv')->beginTransaction();
		try {

			    $idEmpleado = Auth::user()->idEmpleado;
			    $verficar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->count();
                    if($verficar!=0){
                    	  $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->first();
                    	  $cod = $idPar->idParticipante;
                    	   //cerramos caso al participante
				    	  $idPar->caso=1;
                          $idPar->save();

                    }else{
                    			//CREAMOS UN PARTICIPANTE CON EL ROL DE ASISTENTE DE JEFE
                    	 	    $iddd = Auth::user()->idEmpleado;
                    	      	$part =  new Participantes();
						    	$part->idSolicitud = $request->idSoli;
				                $part->idEmpleado = $iddd;
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=7;
								$part->caso=0;
						    	$part->save();
						    	$cod = $part->idParticipante;
                    }
			   // if($verficar!=0){
                	$comen = new Comentarios();
                	$comen->comentario = $request->comentarioColaborador;
                	$comen->idSolicitud = $request->idSoli;
                	$comen->idParticipante = $cod;
                	$comen->tipoComentario=1;
                	$comen->idPadre=$request->idPadre;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();



				    $soli = Solicitudes::Find($request->idSoli);
				    $es = $soli->idEstado;
				    if($es==2){
				    	$soli->idEstado=3;
				    	$soli->save();
				    	$historia = new Historial();
					    $historia->idSolicitud =  $request->idSoli;
						$historia->idEstado = 3;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();
				    }


			                $com= Comentarios::find($request->idPadre);
			                $bc = $com->idParticipante;

			                $partt = Participantes::find($bc);
			                $iddd = $partt->idEmpleado;

			                $asistente = PersonaParticipante::where('idEmpleado',$partt->idEmpleado)->first();

                            $emppCorreo = User::where('idEmpleado','=',$iddd)->first();
                            $emailem = $emppCorreo->correo;

                            $data['soli'] = Solicitudes::find($request->idSoli);
                            if(!empty($emailem)){
							                     Mail::send('emails.respuestaComentario',$data,function($msj) use ($data,$emailem){
					                             $msj->subject('Respuesta en solicitud correspondencia');
								                 $msj->to($emailem);
					                                });
						      }
						    if(!empty($asistente->idEmpleadoAsistente)){
						    		  $emppCorreo2 = User::where('idEmpleado','=',$asistente->idEmpleadoAsistente)->first();
						    		  $emailem2 = $emppCorreo2->correo;
						    		   if(!empty($emailem2)){
							                     Mail::send('emails.respuestaComentario',$data,function($msj) use ($data,$emailem2){
					                             $msj->subject('Respuesta en solicitud correspondencia');
								                 $msj->to($emailem2);
					                                });
						     		 }
						    }


			     $soliSegui = new  SolicitudSeguimiento();
				 $soliSegui->idSolicitud=$request->idSoli;
				 $soliSegui->estadoSolicitud=3;
                $soliSegui->observaciones ='Respondió al comentario de <b>'.$emppCorreo->nombresUsuario.' '.$emppCorreo->apellidosUsuario.'</b>:'.$request->comentarioColaborador;
			     $soliSegui->fechaCreacion=Carbon::now();
				 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 $soliSegui->save();



				     //-------------SUBIR ARCHIVO COMENTARIO-----------
		    	    $idComentario = $comen->idComentario;
		    	    $idSolicitud = $comen->idSolicitud;
				    $nombre= 'comentarios';
		      		$urlPrincipal = Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$idSolicitud;
				    $file= $request->file('fileColaborador');

				    if(!empty($file)){
					$filesystem= new Filesystem();
				    if($filesystem->exists($path)){

					    if($filesystem->isWritable($path)){
						$carpeta=$path.'\\'.$nombre;
						File::makeDirectory($carpeta, 0777, true, true);
						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);


						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();
					   }else{
					   		DB::rollback();
							return " Error al subir el archivo ";
					   }


				  }else{

				  	    $carpeta=$path.'\\'.$idSolicitud.'\\'.$nombre;
						File::makeDirectory($carpeta, 0777, true, true);
						$name= $file->getClientOriginalName(); ;
						$type = $file->getMimeType();
						$file->move($carpeta,$name);
						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();
				  }
				}
				//----------------------------------
			//}else{
				//Session::put('msnError1', 'No estas registrado como participante');
		        // return redirect('solicitud/ver-solicitud/'.Crypt::encrypt($request->idSoli));
			//}

		  DB::connection('sqlsrv')->commit();
		   Session::put('msnExito1', 'Se a registrado con exito el comentario');
		  return redirect('solicitud/ver-solicitud/'.Crypt::encrypt($request->idSoli));

		} catch (\Exception $e) {
			Debugbar::addException($e);
			DB::connection('sqlsrv')->rollback();
			Session::put('msnError1', 'Problemas en el servidor');
		    return redirect('solicitud/ver-solicitud/'.Crypt::encrypt($request->idSoli));
		}
		return response()->json($response);


	}

	public function storeComentarioResponsable(Request $request){

        DB::connection('sqlsrv')->beginTransaction();
		try {

             $v = Validator::make($request->all(),[

	        	'comentarioResponsable'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'comentarioResponsable'=>'comentario'

		    ]);
			if ($v->fails())
		    {
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		        return $response = ['status' => 500, 'message' => $msg, "redirect" => ''];
		    }

			     $idEmpleado = Auth::user()->idEmpleado;
                 //CONSULTAMOS A LOS PARTICIPANTES Y A LOS ASISTENTES DE LOS JEFES
                 $idPar=Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->whereIn('idEstado',[1,7])->first();
                    if(!empty($idPar)){
                    	  $cod = $idPar->idParticipante;
                    	   //cerramos caso al participante
				    	  $idPar->caso=1;
                          $idPar->save();

                    }else{
                    			//CREAMOS UN PARTICIPANTE CON EL ROL DE ASISTENTE DE JEFE
                    	 	    $iddd = Auth::user()->idEmpleado;
                    	      	$part =  new Participantes();
						    	$part->idSolicitud = $request->idSoli;
				                $part->idEmpleado = $iddd;
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=7;
								$part->caso=0;
						    	$part->save();
						    	$cod = $part->idParticipante;
                    }

				    //tipoComen 1.COMENTAR 2.CERRAR CASO
				    if($request->tipoComen==2){

				    	   $comen = new Comentarios();
                	$comen->comentario = $request->comentarioResponsable;
                	$comen->idSolicitud = $request->idSoli;
                	$comen->idParticipante = $cod;
                	$comen->tipoComentario=3;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();

                        $file= $request->file('file');
                        if(!empty($file)){
                        	//SI EXISTE ARCHIVOS CAMBIAMOS A ESTADO PARA FIRMA
				    	$soliEstado= Solicitudes::find($request->idSoli);
				    	$soliEstado->idEstado=8;
				    	$soliEstado->save();
				    	}else{
				    	   //SINO EXISTE ARCHIVOS CAMBIAMOS A ESTADO REVISIÓN
				    	$soliEstado= Solicitudes::find($request->idSoli);
				    	$soliEstado->idEstado=7;
				    	$soliEstado->save();
				    	}

                 		$soliSegui = new  SolicitudSeguimiento();
				 		$soliSegui->idSolicitud=$request->idSoli;
				 		$soliSegui->estadoSolicitud=7;
		         		$soliSegui->observaciones = '<b>Cerrado Caso</b>, nuevo comentario:'.$request->comentarioResponsable;
			     		$soliSegui->fechaCreacion=Carbon::now();
				 		$soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 		$soliSegui->save();

				        $historia = new Historial();
					    $historia->idSolicitud =  $request->idSoli;
						$historia->idEstado = 7;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();
						//ENVIAMOS CORREO A LOS ASESORES
				    	/*	$part= Participantes::where('idSolicitud',$request->idSoli)->whereIn('idEstado',[3,4])->pluck('idEmpleado');
                            $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');
                            $data['soli'] = Solicitudes::find($request->idSoli);

			                        foreach($empCorreo as $a){
			                    	if(!empty($a)){
							                     Mail::send('emails.asesores',$data,function($msj) use ($data,$a){
					                             $msj->subject('Revisión de solicitud correspondencia');
								                 $msj->to($a);
					                                });
						              }
								}*/

						 //CERRAMOS CASO A LOS COLABORADORES Y RESPONSABLE
					      $casoColaboradores = Participantes::where('idSolicitud','=',$request->idSoli)->whereIn('idEstado',[1,2])->pluck('idParticipante');
		                   if(!empty($casoColaboradores)){
		                   for($j=0;$j<count($casoColaboradores);$j++){
		                   $casoCo = Participantes::find($casoColaboradores[$j]);
		                   $casoCo->caso=1;
		                   $casoCo->save();
		                   }
		                   }
		            }else{
		            $comen = new Comentarios();
                	$comen->comentario = $request->comentarioResponsable;
                	$comen->idSolicitud = $request->idSoli;
                	$comen->idParticipante = $cod;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();
				   //ENVIAMOS CORREO A LOS RESPONSABLES PORQUE EXISTE UN NUEVO COMENTARIO
				    	$part= Participantes::where('idSolicitud',$request->idSoli)->where('idEstado',1)->pluck('idEmpleado');
				    	$asistentes = PersonaParticipante::whereIn('idEmpleado',$part)->pluck('idEmpleadoAsistente');

                        $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');
                        $empasiste= User::whereIn('idEmpleado',$asistentes)->pluck('correo');

                            $data['soli'] = Solicitudes::find($request->idSoli);

			                        foreach($empCorreo as $a){
			                    	if(!empty($a)){
							                     Mail::send('emails.comentarioParticipante',$data,function($msj) use ($data,$a){
					                             $msj->subject('Nuevo comentario en correspondencia');
								                 $msj->to($a);
					                                });
						              }
								}

								 if(count($empasiste)>0){
									foreach($empasiste as $b){
					                    	if(!empty($b)){
									                     Mail::send('emails.comentarioParticipante',$data,function($msj) use ($data,$b){
							                             $msj->subject('Nuevo comentario en correspondencia');
										                 $msj->to($b);
							                                });
								            }
								          }
					    		}
						$soliEstado= Solicitudes::find($request->idSoli);
				    	$soliEstado->idEstado=3;
				    	$soliEstado->save();
		            }//cierre de if tipoComen



				     //-------------SUBIR ARCHIVO COMENTARIO-----------
		    	    $idComentario = $comen->idComentario;
		    	    $idSolicitud = $comen->idSolicitud;
				    $nombre= 'comentarios';
		      		$urlPrincipal = Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$idSolicitud;
				    $file= $request->file('file');

				    if(!empty($file)){


					$filesystem= new Filesystem();
				    if($filesystem->exists($path)){

					    if($filesystem->isWritable($path)){
						$carpeta=$path.'\\'.$nombre;
						File::makeDirectory($carpeta, 0777, true, true);
						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);


						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();

						if($request->tipoComen==2){
						$archivo = new Adjunto();
						$archivo->idSolicitud=$idSolicitud;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->idEstado=3;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();
						}

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$idSolicitud;
				         $soliSegui->estadoSolicitud=8;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();
					   }else{
					   		DB::rollback();
							return " Error al subir el archivo ";
					   }


				  }else{

				  	    $carpeta=$path.'\\'.$idSolicitud.'\\'.$nombre;
						File::makeDirectory($carpeta, 0777, true, true);
						$name= $file->getClientOriginalName(); ;
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();

						if($request->tipoComen==2){
						$archivo = new Adjunto();
						$archivo->idSolicitud=$idSolicitud;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->idEstado=3;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();
					    }

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$idSolicitud;
				         $soliSegui->estadoSolicitud=8;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();
				  }


				}
				//----------------------------------

		  if($request->tipoComen==2){
	     		 $response = ['status' => 200, 'message' => '¡La solicitud se a cerrado con exito!', "redirect" => ''];
	      }else{
		  		$response = ['status' => 200, 'message' => '¡Comentario ingresado con exito!', "redirect" => ''];
		   }
		    DB::connection('sqlsrv')->commit();

		} catch (\Exception $e) {
			Debugbar::addException($e);
			$response = ['status' => 500, 'message' => 'Se produjo una excepción en el servidor', "redirect" => ''];
			DB::connection('sqlsrv')->rollback();
		}
		return response()->json($response);


	}
	public function storeArchivoRespFinal(Request $request){

        DB::connection('sqlsrv')->beginTransaction();
		try {

             $v = Validator::make($request->all(),[

	        	'fileRespuesta'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'fileRespuesta'=>'archivo'

		    ]);
			if ($v->fails())
		    {
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		        return $msg;
		    }
		   // dd("simon limon");

			    $idEmpleado = Auth::user()->idEmpleado;
			    $verficar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->count();
			    if($verficar!=0){

                 $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->where('idEstado','=',1)->first();

                    $cod = $idPar->idParticipante;


				          //CERRAR CASO
				    	  //cerramos caso al participante
				    	  $idPar->caso=1;
                          $idPar->save();

                         //LA SOLICITUD CAMBIA A ESTADO PARA FIRMA
				    	$soliEstado= Solicitudes::find($request->idSoli);
				    	$soliEstado->idEstado=8;
				    	$soliEstado->save();

                 		$soliSegui = new  SolicitudSeguimiento();
				 		$soliSegui->idSolicitud=$request->idSoli;
				 		$soliSegui->estadoSolicitud=8;
		         		$soliSegui->observaciones = '<b>Cerrado Caso</b>, nuevo comentario: Se adjunto documento final usuario';
			     		$soliSegui->fechaCreacion=Carbon::now();
				 		$soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 		$soliSegui->save();

				        $historia = new Historial();
					    $historia->idSolicitud =  $request->idSoli;
						$historia->idEstado = 8;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();
						//ENVIAMOS CORREO A LOS ASESORES
				    		/*$part= Participantes::where('idSolicitud',$request->idSoli)->whereIn('idEstado',[3,4])->pluck('idEmpleado');
                            $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');
                            $data['soli'] = Solicitudes::find($request->idSoli);

			                        foreach($empCorreo as $a){
			                    	if(!empty($a)){
							                     Mail::send('emails.asesores',$data,function($msj) use ($data,$a){
					                             $msj->subject('Revisión de solicitud correspondencia');
								                 $msj->to($a);
					                                });
						              }
								}*/

						 //CERRAMOS CASO A LOS COLABORADORES Y RESPONSABLE
					      $casoColaboradores = Participantes::where('idSolicitud','=',$request->idSoli)->whereIn('idEstado',[1,2])->pluck('idParticipante');
		                   if(!empty($casoColaboradores)){
		                   for($j=0;$j<count($casoColaboradores);$j++){
		                   $casoCo = Participantes::find($casoColaboradores[$j]);
		                   $casoCo->caso=1;
		                   $casoCo->save();
		                   }
		                   }





		    		//-------------SUBIR ARCHIVOS CORRESPONDENCIA-----------
		    	     $newId=$request->idSoli;
		      		$urlPrincipal =Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$newId;
		      	    $file = $request->file('fileRespuesta');
					$filesystem= new Filesystem();
				    if($filesystem->exists($path)){
				    	$carpeta=$path;
				    	File::makeDirectory($carpeta, 0777, true, true);


						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->idEstado=3;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$newId;
				         $soliSegui->estadoSolicitud=8;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();

				  }else{
				  	$carpeta=$path;
				    	File::makeDirectory($carpeta, 0777, true, true);


						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->idEstado=3;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$newId;
				         $soliSegui->estadoSolicitud=8;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();


				  }

			}else{
				return 'No se encuentra como participante de esta solicitud';
			}

		    DB::connection('sqlsrv')->commit();

		} catch (\Exception $e) {
			Debugbar::addException($e);
			DB::connection('sqlsrv')->rollback();
			return  'Se produjo una excepción en el servidor';
		}
		return response()->json(['state' => 'success']);


	}

	public function storeComentarioAsesor(Request $request){
		//dd($request->idSoli);
        /*idTipo 1.Observada 2.Favorable 3.No aplica*/
        DB::connection('sqlsrv')->beginTransaction();
		try {

		if($request->idTipo==1){
			     $v = Validator::make($request->all(),[
			     	'comentarioAsesor'=>'required'
				    ]);

	   		      $v->setAttributeNames([
	   		       'comentarioAsesor'=>'comentario']);
				if ($v->fails())
		    	{
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		        return $response = ['status' => 500, 'message' => $msg, "redirect" => ''];
		   	    }
		   	 }

			    $idEmpleado = Auth::user()->idEmpleado;
			    $verficar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->count();

			    if($verficar!=0){

                  $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->first();
                  $cod = $idPar->idParticipante;


                	$comen = new Comentarios();

                	//------------------OBSERVADA-----------------------------
                	if($request->idTipo==1){
                		$comen->comentario = $request->comentarioAsesor;
                		$comen->idSolicitud = $request->idSoli;
                		$comen->idParticipante = $cod;
                		$comen->tipoComentario=0;
                		$comen->usuarioCreacion = Auth::user()->idUsuario;
				    	$comen->fechaCreacion = Carbon::now();
				    	$comen->save();

				        $soli = Solicitudes::Find($request->idSoli);
				        $soli->idEstado=11;
				        $soli->save();

				        $historia = new Historial();
					    $historia->idSolicitud =  $request->idSoli;
						$historia->idEstado =11;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();


                 $soliSegui = new  SolicitudSeguimiento();
				 $soliSegui->idSolicitud=$request->idSoli;
				 $soliSegui->estadoSolicitud=11;
		         $soliSegui->observaciones = 'Nuevo comentario:'.$request->comentarioAsesor;
			     $soliSegui->fechaCreacion=Carbon::now();
				 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 $soliSegui->save();

				 	//HABILITAMOS CASO A LOS RESPONSABLE
					$casoRes = Participantes::where('idSolicitud','=',$request->idSoli)->where('idEstado',1)->pluck('idParticipante');
		                   if(!empty($casoRes)){
		                   for($j=0;$j<count($casoRes);$j++){
		                   $casoCo = Participantes::find($casoRes[$j]);
		                   $casoCo->caso=0;
		                   $casoCo->save();
		                   }
		                   }

		              //ENVIAMOS CORREO A LOS RESPONSABLE
		             $pacorr= Participantes::where('idSolicitud',$request->idSoli)->where('idEstado',1)->pluck('idEmpleado');
                     $empaco = User::whereIn('idEmpleado',$pacorr)->pluck('correo');
                     $data['soli'] = Solicitudes::find($request->idSoli);

			                    foreach($empaco as $a){
			                    	if(!empty($a)){
							                     Mail::send('emails.observacion',$data,function($msj) use ($data,$a){
					                             $msj->subject('Observación en solicitud correspondencia');
								                 $msj->to($a);
					                                });
						              }
								}
                     }else if($request->idTipo==2){

                     	//--------------FAVORABLE------------------------
                    $idEmpleado = Auth::user()->idEmpleado;
		    	    $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->first();
                    $idPar->caso=1;
                    $idPar->save();

                    $cod = $idPar->idParticipante;
                    $estado = $idPar->idEstado;

                    $soliEstado= Solicitudes::find($request->idSoli);
				    $soliEstado->idEstado=7;
				    $soliEstado->usuarioModificacion=Auth::user()->idUsuario;
				    $soliEstado->fechaModificacion=Carbon::now();
				    $soliEstado->save();

		    		$comen = new Comentarios();
		    		if($request->comentarioAsesor!=''){
		    		   $comen->comentario=$request->comentarioAsesor;

		    		     $soliSegui = new  SolicitudSeguimiento();
				 		 $soliSegui->idSolicitud=$request->idSoli;
				 		 $soliSegui->estadoSolicitud=7;
		         		 $soliSegui->observaciones = 'Nuevo comentario:'. $request->comentarioAsesor;
			     		 $soliSegui->fechaCreacion=Carbon::now();
				 		 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 		 $soliSegui->save();
                    }else{
                    		if($estado==3){
                				$comen->comentario = 'Asesor T&eacute;cnico registr&oacute; como favorable est&aacute; solicitud';
                				$a='Asesor T&eacute;cnico registr&oacute; como favorable est&aacute; solicitud';
                    			}else{
				    				$comen->comentario = 'Asesor Jur&iacute;dico registr&oacute; como favorable est&aacute; solicitud';
				    			 $a='Asesor Jur&iacute;dico registr&oacute; como favorable est&aacute; solicitud';
                    			}
                    	 $soliSegui = new  SolicitudSeguimiento();
				 		 $soliSegui->idSolicitud=$request->idSoli;
				 		 $soliSegui->estadoSolicitud=7;
		         		 $soliSegui->observaciones = 'Nuevo comentario:'.$a;
			     		 $soliSegui->fechaCreacion=Carbon::now();
				 		 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 		 $soliSegui->save();

                    }
                	$comen->idSolicitud = $request->idSoli;
                	$comen->idParticipante = $cod;
                	$comen->tipoComentario=0;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();


                    $numAsesores = Participantes::where('idSolicitud',$request->idSoli)->whereIn('idEstado',[3,4])->count();
				    $numCerrado=Participantes::where('idSolicitud',$request->idSoli)->whereIn('idEstado',[3,4])->where('caso',1)->count();
                        if($numAsesores==$numCerrado){

                            $soliEstado= Solicitudes::find($request->idSoli);
                            $soliEstado->idEstado=8;
                            $soliEstado->save();

                            $historia = new Historial();
                            $historia->idSolicitud = $request->idSoli;
                            $historia->idEstado = 8;
                            $historia->usuarioCreacion = Auth::user()->idUsuario;
                            $historia->fechaCreacion = Carbon::now();
                            $historia->save();

                            $idd = UserOptions::getDirectora();
                            $part =  new Participantes();
                            $part->idSolicitud = $request->idSoli;
                            $part->idEmpleado = $idd->idEmpleado;
                            $part->usuarioCreacion = Auth::user()->idUsuario;
                            $part->fechaCreacion = Carbon::now();
                            $part->idEstado=5;
                            $part->caso=0;
                            $part->save();

                            $soliSegui = new  SolicitudSeguimiento();
                            $soliSegui->idSolicitud=$request->idSoli;
                            $soliSegui->estadoSolicitud=8;
                            $soliSegui->observaciones = 'Los asesores envian solicitud para firmar';
                            $soliSegui->fechaCreacion=Carbon::now();
                            $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
                            $soliSegui->save();

                        }

                    }else{
                        //-----------------NO APLICA---------------------
                        $idEmpleado = Auth::user()->idEmpleado;
                        $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->whereIn('idEstado',[3,4])->first();
                        $idPar->caso=1;
                        $idPar->save();

                        $cod = $idPar->idParticipante;
                        $estado = $idPar->idEstado;

                        $soliEstado= Solicitudes::find($request->idSoli);
                        $soliEstado->idEstado=7;
                        $soliEstado->save();

                        $comen = new Comentarios();
                        if($request->comentarioAsesor!=''){
                            $comen->comentario=$request->comentarioAsesor;

                            $soliSegui = new  SolicitudSeguimiento();
                            $soliSegui->idSolicitud=$request->idSoli;
                            $soliSegui->estadoSolicitud=7;
                            $soliSegui->observaciones = 'Nuevo comentario:'. $request->comentarioAsesor;
                            $soliSegui->fechaCreacion=Carbon::now();
                            $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
                            $soliSegui->save();
                        }else{
                            if($estado==3){
                                $comen->comentario = 'Asesor T&eacute;cnico registro como NO APLICA en estÃ¡ solicitud';
                                $b='Asesor T&eacute;cnico registro como NO APLICA en estÃ¡ solicitud';
                            }else{
                                $comen->comentario = 'Asesor JurÃ­dico registro como NO APLICA en estÃ¡ solicitud';
                                $b='Asesor JurÃ­dico registro como NO APLICA en estÃ¡ solicitud';
                            }


                            $soliSegui = new  SolicitudSeguimiento();
                            $soliSegui->idSolicitud=$request->idSoli;
                            $soliSegui->estadoSolicitud=7;
                            $soliSegui->observaciones = 'Nuevo comentario:'.$b;
                            $soliSegui->fechaCreacion=Carbon::now();
                            $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
                            $soliSegui->save();
                        }


                        $comen->idSolicitud = $request->idSoli;
                        $comen->idParticipante = $cod;
                        $comen->tipoComentario=0;
                        $comen->usuarioCreacion = Auth::user()->idUsuario;
                        $comen->fechaCreacion = Carbon::now();
                        $comen->save();

                        $numAsesores = Participantes::where('idSolicitud',$request->idSoli)->whereIn('idEstado',[3,4])->count();
                        $numCerrado=Participantes::where('idSolicitud',$request->idSoli)->whereIn('idEstado',[3,4])->where('caso',1)->count();
                        if($numAsesores==$numCerrado){

                            $soliEstado= Solicitudes::find($request->idSoli);
                            $soliEstado->idEstado=8;
                            $soliEstado->save();

                            $historia = new Historial();
                            $historia->idSolicitud = $request->idSoli;
                            $historia->idEstado = 8;
                            $historia->usuarioCreacion = Auth::user()->idUsuario;
                            $historia->fechaCreacion = Carbon::now();
                            $historia->save();

                            $idd = UserOptions::getDirectora();
                            $part =  new Participantes();
                            $part->idSolicitud = $request->idSoli;
                            $part->idEmpleado = $idd->idEmpleado;
                            $part->usuarioCreacion = Auth::user()->idUsuario;
                            $part->fechaCreacion = Carbon::now();
                            $part->idEstado=5;
                            $part->caso=0;
                            $part->save();

                            $soliSegui = new  SolicitudSeguimiento();
                            $soliSegui->idSolicitud=$request->idSoli;
                            $soliSegui->estadoSolicitud=8;
                            $soliSegui->observaciones = 'Los asesores envian solicitud para firmar';
                            $soliSegui->fechaCreacion=Carbon::now();
                            $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
                            $soliSegui->save();
                        }



                    }

                    //-------------SUBIR ARCHIVO COMENTARIO-----------
                    $idComentario = $comen->idComentario;
                    $idSolicitud = $comen->idSolicitud;
                    $nombre= 'comentarios';
                    $urlPrincipal = Config::get('app.mapeoArchivos');
                    $path= $urlPrincipal.'\\'.$idSolicitud;
                    $file= $request->file('fileAsesor');

                    if(!empty($file)){
                        $filesystem= new Filesystem();
                        if($filesystem->exists($path)){

                            if($filesystem->isWritable($path)){
                                $carpeta=$path.'\\'.$nombre;
                                File::makeDirectory($carpeta, 0777, true, true);
                                $name= $file->getClientOriginalName();
                                $type = $file->getMimeType();
                                $file->move($carpeta,$name);


                                $archivo = Comentarios::find($idComentario);
                                $archivo->archivo=$carpeta.'\\'.$name;
                                $archivo->tipoArchivo=$type;
                                $archivo->nombreArchivo = $name;
                                $archivo->save();
                            }else{
                                DB::rollback();
                                return " Error al subir el archivo ";
                            }


                        }else{

                            $carpeta=$path.'\\'.$idSolicitud.'\\'.$nombre;
                            File::makeDirectory($carpeta, 0777, true, true);
                            $name= $file->getClientOriginalName(); ;
                            $type = $file->getMimeType();
                            $file->move($carpeta,$name);
                            $archivo = Comentarios::find($idComentario);
                            $archivo->archivo=$carpeta.'\\'.$name;
                            $archivo->tipoArchivo=$type;
                            $archivo->nombreArchivo = $name;
                            $archivo->save();
                        }
                    }



				//----------------------------------
			}else{
				return $response = ['status' => 500, 'message' => '¡No estás registrado como participante!', "redirect" => ''];
			}

		  DB::connection('sqlsrv')->commit();
		  $response = ['status' => 200, 'message' => '¡Se guardo con éxito la información en la solicitud!', "redirect" => ''];

		} catch (\Exception $e) {
			Debugbar::addException($e);
			$response = ['status' => 500, 'message' => 'Se produjo una excepción en el servidor', "redirect" => ''];
			DB::connection('sqlsrv')->rollback();
		}
		return response()->json($response);


	}


		public function storeFavorable(Request $request){

			$v = Validator::make($request->all(),[
	        	'txtFavorable'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'txtFavorable'=>'id de la solicitud'

		    ]);
			if ($v->fails())
		    {
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		        return $msg;
		    }
		    try {
		    	    $idEmpleado = Auth::user()->idEmpleado;
		    	    $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->txtFavorable)->first();
                    $idPar->caso=1;
                    $idPar->save();

                    $cod = $idPar->idParticipante;
                    $estado = $idPar->idEstado;

                    $soliEstado= Solicitudes::find($request->txtFavorable);
				    $soliEstado->idEstado=7;
				    $soliEstado->save();

		    		$comen = new Comentarios();
		    		if($estado==3){
                	$comen->comentario = 'Asesor T&eacute;cnico registr&oacute; como favorable est&aacute; solicitud';
                    }else{
				    $comen->comentario = 'Asesor Jur&iacute;dico registr&oacute; como favorable est&aacute; solicitud';
                    }
                	$comen->idSolicitud = $request->txtFavorable;
                	$comen->idParticipante = $cod;
                	$comen->tipoComentario=0;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();


                    $numAsesores = Participantes::where('idSolicitud',$request->txtFavorable)->whereIn('idEstado',[3,4])->count();
				    $numCerrado=Participantes::where('idSolicitud',$request->txtFavorable)->whereIn('idEstado',[3,4])->where('caso',1)->count();
				    if($numAsesores==$numCerrado){

				    	 $soliEstado= Solicitudes::find($request->txtFavorable);
				         $soliEstado->idEstado=8;
				         $soliEstado->save();

				        $historia = new Historial();
					    $historia->idSolicitud = $request->txtFavorable;
						$historia->idEstado = 8;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();

							$idd = UserOptions::getDirectora();
                        	$part =  new Participantes();
		    				$part->idSolicitud = $request->txtFavorable;
                			$part->idEmpleado = $idd->idEmpleado;
                			$part->usuarioCreacion = Auth::user()->idUsuario;
							$part->fechaCreacion = Carbon::now();
							$part->idEstado=5;
							$part->caso=0;
		    				$part->save();

			         }


			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}


		public function storeNoAplica(Request $request){

			$v = Validator::make($request->all(),[
	        	'txtmoAplica'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'txtmoAplica'=>'id de la solicitud'

		    ]);
			if ($v->fails())
		    {
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		        return $msg;
		    }
		    try {
		    	    $idEmpleado = Auth::user()->idEmpleado;

		    	   $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->txtmoAplica)->first();
                    $idPar->caso=1;
                    $idPar->save();

                    $cod = $idPar->idParticipante;
                    $estado = $idPar->idEstado;

                    $soliEstado= Solicitudes::find($request->txtmoAplica);
				    $soliEstado->idEstado=7;
				    $soliEstado->save();

		    		$comen = new Comentarios();
		    		if($estado==3){
                	$comen->comentario = 'Asesor T&eacute;cnico registro como NO APLICA en está solicitud';
                    }else{
				    $comen->comentario = 'Asesor Jurídico registro como NO APLICA en está solicitud';
                    }
                	$comen->idSolicitud = $request->txtmoAplica;
                	$comen->idParticipante = $cod;
                	$comen->tipoComentario=0;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();

				    $numAsesores = Participantes::where('idSolicitud',$request->txtmoAplica)->whereIn('idEstado',[3,4])->count();
				    $numCerrado=Participantes::where('idSolicitud',$request->txtmoAplica)->whereIn('idEstado',[3,4])->where('caso',1)->count();
				    if($numAsesores==$numCerrado){

				    	 $soliEstado= Solicitudes::find($request->txtmoAplica);
				         $soliEstado->idEstado=8;
				         $soliEstado->save();

				        $historia = new Historial();
					    $historia->idSolicitud = $request->txtmoAplica;
						$historia->idEstado = 8;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();

						    $idd = UserOptions::getDirectora();
                        	$part =  new Participantes();
		    				$part->idSolicitud = $request->txtmoAplica;
                			$part->idEmpleado = $idd->idEmpleado;
                			$part->usuarioCreacion = Auth::user()->idUsuario;
							$part->fechaCreacion = Carbon::now();
							$part->idEstado=5;
							$part->caso=0;
		    				$part->save();
			         }

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}


		public function storeFirmaRevision(Request $request){

        DB::connection('sqlsrv')->beginTransaction();
		try {

             $v = Validator::make($request->all(),[

	        	'ids'=>'required',
	        	'tipoRevision'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		    'ids'=>'id solicitud',
	   		    'tipoRevision'=>'tipo comentario'

		    ]);
			if ($v->fails())
		    {
		    	$msg = "<ul class='text-warning'>";
		    	foreach ($v->messages()->all() as $err) {
		    	 	$msg .= "<li>$err</li>";
		    	}
		    	$msg .= "</ul>";
		        return $msg;
		    }
		    //--1.Aprobada 2.Observada

		            $idPar = Participantes::where('idEmpleado','=',Auth::user()->idEmpleado)
		    	    ->where('idSolicitud','=',$request->ids)->first();
                    if(!empty($idPar)){
                    	  $cod = $idPar->idParticipante;
                    }else{
                    	 if(Auth::user()->idEmpleado==279 || Auth::user()->idEmpleado==150){
                    	 	    //CREAMOS COMO PARTICIPANTE A gladys.castro y aleida.viera
                    	 	    $iddd = Auth::user()->idEmpleado;
                    	      	$part =  new Participantes();
						    	$part->idSolicitud = $request->ids;
				                $part->idEmpleado = $iddd;
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=6;
								$part->caso=0;
						    	$part->save();
						    	$cod = $part->idParticipante;
                    	 }else{
                    	    return ' ¡NO TIENE PERMISOS PARA REALIZAR LA ACCIÓN!';
                    	 }

                    }
		            $comen = new Comentarios();
		            if(strlen($request->txtobsAfirmar)>1){
		            	$comen->comentario = $request->txtobsAfirmar;
                	}else{
                		$comen->comentario = 'Correspondencia aprobada';
                	}
                	$comen->idSolicitud = $request->ids;
                	$comen->idParticipante =  $cod;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();


		    if($request->tipoRevision==2){

				        $soli = Solicitudes::Find($request->ids);
				        $soli->idEstado=11;
				        $soli->save();

				        $historia = new Historial();
					    $historia->idSolicitud =  $request->ids;
						$historia->idEstado =11;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();


                 $soliSegui = new  SolicitudSeguimiento();
				 $soliSegui->idSolicitud=$request->ids;
				 $soliSegui->estadoSolicitud=11;
		         $soliSegui->observaciones = 'Nuevo comentario:'.$request->txtobsAfirmar;
			     $soliSegui->fechaCreacion=Carbon::now();
				 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 $soliSegui->save();

				 	//HABILITAMOS CASO A LOS RESPONSABLE
					$casoRes = Participantes::where('idSolicitud','=',$request->ids)->where('idEstado',1)->pluck('idParticipante');
		                   if(!empty($casoRes)){
		                   for($j=0;$j<count($casoRes);$j++){
		                   $casoCo = Participantes::find($casoRes[$j]);
		                   $casoCo->caso=0;
		                   $casoCo->save();
		                   }
		                   }

		              //ENVIAMOS CORREO A LOS RESPONSABLE Y ASISTENTES DE JEFES
		             $pacorr= Participantes::where('idSolicitud',$request->ids)->where('idEstado',1)->pluck('idEmpleado');
		             $asistentes = PersonaParticipante::whereIn('idEmpleado',$pacorr)->pluck('idEmpleadoAsistente');

                     $empaco = User::whereIn('idEmpleado',$pacorr)->pluck('correo');
                     $empasiste = User::whereIn('idEmpleado',$asistentes)->pluck('correo');

                     $data['soli'] = Solicitudes::find($request->ids);
                     $data['obser'] = $request->txtobsAfirmar;
			                foreach($empaco as $a){
			                    	if(!empty($a)){
							                     Mail::send('emails.observacion',$data,function($msj) use ($data,$a){
					                             $msj->subject('Observación en solicitud correspondencia');
								                 $msj->to($a);
					                                });
						            }
							}
						 if(count($empasiste)>0){
							foreach($empasiste as $b){
			                    	if(!empty($b)){
							                     Mail::send('emails.observacion',$data,function($msj) use ($data,$b){
					                             $msj->subject('Observación en solicitud correspondencia');
								                 $msj->to($b);
					                                });
						            }
							}
					    }
           }else{
                  	    $soli = Solicitudes::Find($request->ids);
                  	     if(!empty($request->file('fileAprobar'))){
		    				//SI EXISTE DOCUMENTO FINAL USUARIO Y SE ENVIA A RECEPCIÓN CON ESTADO FIRMADA
		    				$soli->idEstado=9;
		   				 }else{
		    				//no existe documento y se procesa la solicitud a procesada
		    				$soli->idEstado=10;
		    				$soli->fechaFinalProceso=date('Y-m-d');
		    		    }
				        $soli->save();

				        $historia = new Historial();
					    $historia->idSolicitud =  $request->ids;
						$historia->idEstado =$soli->idEstado;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();


                 $soliSegui = new  SolicitudSeguimiento();
				 $soliSegui->idSolicitud=$request->ids;
				 $soliSegui->estadoSolicitud=$soli->idEstado;
		         $soliSegui->observaciones = 'Nuevo comentario:'.$request->txtobsAfirmar;
			     $soliSegui->fechaCreacion=Carbon::now();
				 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 $soliSegui->save();

				 	//CERRAMOS CASO A LOS RESPONSABLE
					$casoRes = Participantes::where('idSolicitud','=',$request->ids)->where('idEstado',1)->pluck('idParticipante');
		                   if(!empty($casoRes)){
		                   for($j=0;$j<count($casoRes);$j++){
		                   $casoCo = Participantes::find($casoRes[$j]);
		                   $casoCo->caso=1;
		                   $casoCo->save();
		                   }
		                   }

		              //ENVIAMOS CORREO A LOS RESPONSABLE
		             $pacorr= Participantes::where('idSolicitud',$request->ids)->where('idEstado',1)->pluck('idEmpleado');
                     $empaco = User::whereIn('idEmpleado',$pacorr)->pluck('correo');
                     $data['soli'] = Solicitudes::find($request->ids);
                     $data['obser'] = $request->txtobsAfirmar;

			                    foreach($empaco as $a){
			                    if(!empty($a)){
							                     Mail::send('emails.procesada',$data,function($msj) use ($data,$a){
					                             $msj->subject('Solicitud de correspondencia procesada');
								                 $msj->to($a);
					                                });
						            }
							    }
							   if(!empty($request->file('fileAprobar'))){
							            Mail::send('emails.procesada',$data,function($msj) use ($data){
					                     $msj->subject('Solicitud de correspondencia firmada');
								         $msj->to('aida.mendoza@medicamentos.gob.sv');
								          //$msj->to('kevin.sasso@medicamentos.gob.sv');
					                      });
							     }


				    $newId=$request->ids;
		      		$urlPrincipal =Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$newId;
		      	    $file = $request->file('fileAprobar');
					$filesystem= new Filesystem();
					if(!empty($request->file('fileAprobar'))){
				    if($filesystem->exists($path)){
				    	$carpeta=$path;
				    	File::makeDirectory($carpeta, 0777, true, true);


						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->idEstado=3;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$newId;
				         $soliSegui->estadoSolicitud=8;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();

				  }else{
				  	$carpeta=$path;
				    	File::makeDirectory($carpeta, 0777, true, true);


						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->idEstado=3;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$newId;
				         $soliSegui->estadoSolicitud=8;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();


				  }
				  }

                  }

		    DB::connection('sqlsrv')->commit();

		} catch (\Exception $e) {
			Debugbar::addException($e);
			DB::connection('sqlsrv')->rollback();
			return  'Se produjo una excepción en el servidor';
		}
		return response()->json(['state' => 'success']);


	}

}