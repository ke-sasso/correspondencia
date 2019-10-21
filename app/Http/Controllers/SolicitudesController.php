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
use App\Models\Solicitud\SolicitudSeguimiento;
use App\Models\Solicitud\UsuarioEntrega;
use App\Models\Solicitud\PersonaParticipante;
use App\Models\Solicitud\JustificacionProrroga;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;


class SolicitudesController extends Controller{

	  public function nuevaSolicitud()
	{
		$data = ['title' 			=> 'Nueva solicitud de corresponedencia'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitud de corresponedencia', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		return view('solicitudes.nuevaSolicitud',$data);
	}

	  public function listaSolicitud(Request $req)
	{
		$data = ['title' 			=> 'Solicitudes de corresponedencia'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes de corresponedencia', 'url' => route('lista.solicitud')],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::select('idEstado','nombreEstado')->whereIn('idEstado',[1,2,3,5,7,8,9,10,11])->get();
		$data['estado'] = $req->idEstado;
		$permisos = UserOptions::getAutUserOptions();
		 if(in_array(497,$permisos)){
         	$idJefe = PersonaParticipante::where('idEmpleadoAsistente',Auth::user()->idEmpleado)->select('idEmpleado')->first();
         	if(empty($idJefe)){
             Session::flash('msnError', '¡NO TIENE PERMISOS PARA VER NINGUNA SOLICITUD, POR FAVOR CONTACTAR CON INFORMÁTICA!');
				      $data = ['title' 			=> 'Inicio.'
						,'subtitle'			=> ''
						,'breadcrumb' 		=> [
					 		['nom'	=>	'', 'url' => '#']
						]];
		     return view('inicio.index',$data);
		   }
         }


		return view('solicitudes.lista',$data);
	}

	  public function listaSolicitudEnRevision()
	{
		$data = ['title' 			=> 'Solicitudes de corresponedencia'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes de corresponedencia', 'url' => route('lista.solicitud')],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::all();
		return view('solicitudes.listaRevision',$data);
	}
	  public function listaAsignadasAsesor()
	{
		$data = ['title' 			=> 'Solicitudes de corresponedencia'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes de corresponedencia', 'url' => ''],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::all();
		return view('solicitudes.listaAsignadaAse',$data);
	}


	  public function   getDataRowsPersonasNatural(Request $request){

		$personas = PersonaNatural::where(function($query) use ($request)
			{
				if($request->has('busqueda') && (strlen($request->busqueda) > 0) )
				{
					$query->whereRaw(' (nitNatural like \'%'.$request->busqueda.'%\') or ( numeroDocumento like \'%'.$request->busqueda.'%\') or ( nombres like \'%'.$request->busqueda.'%\') or ( apellidos like \'%'.$request->busqueda.'%\')');
				}
			})->get();

		return Datatables::of($personas)

		 ->addColumn('detalle', function ($dt) {
		 	 	   $tel1='';
                 	$tel2='';
	             return '<a class="btn btn-xs btn-success btn-perspective" onclick="asignarVisitante(\''.$dt->nitNatural.'\',\''.$dt->numeroDocumento.'\',\''.$dt->emailsContacto.'\', \''.$tel1.'\', \''.$tel2.'\', \''.$dt->conocidoPor.'\',\''.$dt->nombres.'\',\''.$dt->apellidos.'\');" ><i class="fa fa-check-square-o"></i></a>';
					})

			->make(true);

	}


	  public function   getDataRowsTitular(){

		$titular = Titulares::all();

		return Datatables::of($titular)
			->make(true);

	}
	public function storeSolicitud(solicitudRequest $request){
        //dd($request->all());
		$soli = null;
		$newId=0;
		$idSolicitud;
		DB::connection('sqlsrv')->beginTransaction();
			try {

				$lastSoli= Solicitudes::all();
                $dad=$lastSoli->last();

                $newId=($dad->idSolicitud)+1;

		    	$soli = new Solicitudes();
                $soli->idSolicitud= $newId;
				$soli->nitSolicitante = $request->nitSolicitante;
				$soli->fechaRecepcion = Carbon::now();
				$soli->asunto = mb_strtoupper($request->asunto, 'UTF-8');
				$soli->descripcion = mb_strtoupper( $request->descripcion, 'UTF-8');
				$soli->idEstado = 1;
				$soli->correoNotificar = $request->correoNotificar;
				$soli->usuarioCreacion = Auth::user()->idUsuario;
				$soli->fechaCreacion = Carbon::now();
				$soli->idTipo=1;
				$soli->save();
				DB::connection('sqlsrv')->commit();

				$a = date("Y"); $m=date("m");$d=date("d");
                $newff = Solicitudes::where('idTipo',1)->count();
                $fid =$a.$m.$d.$newff;
				$soliModifi = Solicitudes::find($newId);
				$soliModifi->noPresentacion = $fid;
				$soliModifi->save();

		} catch (\Exception $e) {

			Debugbar::addException($e);
			$response = ['status' => 500, 'message' => 'Problemas en la tabla principal', "redirect" => ''];
			DB::connection('sqlsrv')->rollback();
		}

		try {



			     DB::connection('sqlsrv')->beginTransaction();
				 $asesoresMedicos =UserOptions::getAsesorMedico();
				 $asesoresJuridicos =UserOptions::getAsesorJuridico();

                if(count($asesoresMedicos)>0){
				      for($a=0;$a<count($asesoresMedicos);$a++){
							   	$part =  new Participantes();
						    	$part->idSolicitud = $newId;
				                $part->idEmpleado = $asesoresMedicos[$a];
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=3;
								$part->caso=0;
						    	$part->save();
		             }
		         }
		          if(count($asesoresJuridicos)>0){
				      for($b=0;$b<count($asesoresJuridicos);$b++){
							   	$part =  new Participantes();
						    	$part->idSolicitud = $newId;
				                $part->idEmpleado = $asesoresJuridicos[$b];
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=4;
								$part->caso=0;
						    	$part->save();
		             }
		         }
		                        //agregamos a monica.ayala
		         	            $part =  new Participantes();
						    	$part->idSolicitud = $newId;
				                $part->idEmpleado = 431;
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=6;
								$part->caso=0;
						    	$part->save();
						    	//agregamos a gladys.castro
						    	$part =  new Participantes();
						    	$part->idSolicitud = $newId;
				                $part->idEmpleado = 279;
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=6;
								$part->caso=0;
						    	$part->save();
						    	//agregamos a aleida.viera
						    	$part =  new Participantes();
						    	$part->idSolicitud = $newId;
				                $part->idEmpleado = 150;
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=6;
								$part->caso=0;
						    	$part->save();


			    $telefonosPN=[];
		        if($request->tel1PN!=null){
		            $telefonosPN[0]=$request->tel1PN;
		        }
		        else{
		            $telefonosPN[0]="";
		        }
		        if($request->tel2PN!=null){
		            $telefonosPN[1]=$request->tel2PN;
		        }
		        else{
		            $telefonosPN[1]="";
		        }
				$pn = PersonaNatural::find($request->nitSolicitante);
				$pn->nombres = mb_strtoupper($request->nombresSolicitante, 'UTF-8');
                $pn->apellidos = mb_strtoupper($request->apellidosSolicitante, 'UTF-8');
				//$pn->conocidoPor = $request->conocidoPN;
				$pn->emailsContacto = $request->correoPN;
				$pn->telefonosContacto = json_encode($telefonosPN);
				$pn->save();

                //$newId = $soli->idSolicitud;


                $listTitular = $request->titular;
				for($i=0; $i<count($listTitular); $i++){

                  $nTitular = new SolicitudesTitular();
                  $nTitular->idSolicitud = $newId;
                  $nTitular->idTitular = $listTitular[$i];
                  $nTitular->usuarioCreacion = Auth::user()->idUsuario;
				  $nTitular->fechaCreacion = Carbon::now();
				  $nTitular->save();
				}

				$historial = new Historial();
				$historial->idSolicitud = $newId;
				$historial->idEstado = 1;
				$historial->usuarioCreacion = Auth::user()->idUsuario;
				$historial->fechaCreacion = Carbon::now();
				$historial->save();



				 //-------------SUBIR ARCHIVOS CORRESPONDENCIA-----------
		    	    $listArchivos = $request->file('fileA');
		      		$urlPrincipal =Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal;
					$filesystem= new Filesystem();
				    if($filesystem->exists($path)){
				    	$carpeta=$path.'\\'.$newId;
				    	File::makeDirectory($carpeta, 0777, true, true);
				    	for($a=0;$a<count($listArchivos);$a++){

						$name= $listArchivos[$a]->getClientOriginalName();
						$type = $listArchivos[$a]->getMimeType();
						$listArchivos[$a]->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();
                        }

				  }else{
				  		$carpeta=$path.'\\'.$newId;
				    	File::makeDirectory($carpeta, 0777, true, true);
				    	for($a=0;$a<count($listArchivos);$a++){

						$name= $listArchivos[$a]->getClientOriginalName();
						$type = $listArchivos[$a]->getMimeType();
						$listArchivos[$a]->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();
                        }

				  }


				//----------------------------------------------------------------------------------
                //------------CREAMOS VARIABLE DE SESSION PARA EL PDF-------------------------------
			    Session::put('idSolicitudPdf', $newId);
			    $response = ['status' => 200, 'message' => '¡Se registro la nueva solicitud!', "redirect" => ''];
				DB::connection('sqlsrv')->commit();

			$soliSegui = new  SolicitudSeguimiento();
			$soliSegui->idSolicitud=$newId;
			$soliSegui->estadoSolicitud=1;
			$soliSegui->observaciones = 'Solicitud Ingresada';
			$soliSegui->fechaCreacion=Carbon::now();
			$soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
			$soliSegui->save();


		} catch (\Exception $e) {
			Debugbar::addException($e);
			$response = ['status' => 500, 'message' => 'Se produjo una excepción en el servidor', "redirect" => ''];
			DB::connection('sqlsrv')->rollback();
		}

		return response()->json($response);

	}
	public function storeEditSolicitud(Request $request){
		$id = $request->idSolicitudEditar;
         DB::connection('sqlsrv')->beginTransaction();
		$soli = null;
		try {
				$soliAnt = Solicitudes::find($id);
            	$antNit= $soliAnt->nitSolicitante;
				$antAsunto=$soliAnt->asunto;
				$antDescripcion=$soliAnt->descripcion;

            	$soli = Solicitudes::find($id);
            	$soli->nitSolicitante = $request->nitSolicitante;
				$soli->asunto = mb_strtoupper($request->asunto,'UTF-8');
				$soli->descripcion = mb_strtoupper( $request->descripcion,'UTF-8');
				$soli->usuarioModificacion = Auth::user()->idUsuario;
				$soli->fechaModificacion = Carbon::now();
				$soli->save();

                $telefonosPN=[];
		        if($request->tel1PN!=null){
		            $telefonosPN[0]=$request->tel1PN;
		        }
		        else{
		            $telefonosPN[0]="";
		        }
		        if($request->tel2PN!=null){
		            $telefonosPN[1]=$request->tel2PN;
		        }
		        else{
		            $telefonosPN[1]="";
		        }
				$pn = PersonaNatural::find($request->nitSolicitante);
			    $pn->nombres = mb_strtoupper($request->nombresSolicitante, 'UTF-8');
                $pn->apellidos = mb_strtoupper($request->apellidosSolicitante, 'UTF-8');
				//$pn->conocidoPor = $request->conocidoPN;
				$pn->emailsContacto = $request->correoPN;
				$pn->telefonosContacto = json_encode($telefonosPN);
				$pn->save();



                $newId =  $request->idSolicitudEditar;
                $listTitular = $request->titular;
                if(count($listTitular)!=0){

				for($i=0; $i<count($listTitular); $i++){
                  if(SolicitudesTitular::where('idSolicitud','=',$newId)->where('idTitular','=',$listTitular[$i])->count()==0){
                  $nTitular = new SolicitudesTitular();
                  $nTitular->idSolicitud = $newId;
                  $nTitular->idTitular = $listTitular[$i];
                  $nTitular->usuarioCreacion = Auth::user()->idUsuario;
				  $nTitular->fechaCreacion = Carbon::now();
				  $nTitular->save();

                   $nomT = Titulares::find($listTitular[$i]);
                   $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$newId;
				   $soliSegui->estadoSolicitud=$soli->idEstado;
				   $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo titular: '.$nomT->nombreTitular;
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();

				}else{
					$idT = Titulares::find($listTitular[$i]);
					$nom = $idT->nombreTitular;
					$error = 'Ya existe el titular '.$nom.' en la solicitud';
				    $response = ['status' => 500, 'message' => $error, "redirect" => ''];
				     return response()->json($response);
				}
				}
			    }

				 //-------------SUBIR ARCHIVOS CORRESPONDENCIA-----------
		    	    $listArchivos = $request->file('fileA');
		      		$urlPrincipal =Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$newId;
					$filesystem= new Filesystem();
					if(count($listArchivos)!=0){
				    if($filesystem->exists($path)){
				    	$carpeta=$path;
				    	File::makeDirectory($carpeta, 0777, true, true);
				    	for($a=0;$a<count($listArchivos);$a++){

						$name= $listArchivos[$a]->getClientOriginalName();
						$type = $listArchivos[$a]->getMimeType();
						$listArchivos[$a]->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$newId;
				         $soliSegui->estadoSolicitud=$soli->idEstado;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();
                        }

				  }else{
				  	$carpeta=$path;
				    	File::makeDirectory($carpeta, 0777, true, true);
				    	for($a=0;$a<count($listArchivos);$a++){

						$name= $listArchivos[$a]->getClientOriginalName();
						$type = $listArchivos[$a]->getMimeType();
						$listArchivos[$a]->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$newId;
				         $soliSegui->estadoSolicitud=$soli->idEstado;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();
                        }

				  }
				 }
				//----------------------------------
				Session::put('idSolicitudPdf', $newId);
			    $response = ['status' => 200, 'message' => '¡Se editó con exito la información de la solicitud!', "redirect" => ''];
				DB::connection('sqlsrv')->commit();

	      //------------------------------------INGRESAR SEGUIMIENTO------------------------------------------

			    $soliConsulta = Solicitudes::find($id);
				if($antNit!=$soliConsulta->nitSolicitante){
			       $a='<br><b>Nit Solicitante:</b> '.$soliConsulta->nitSolicitante;
				}else{
					$a='';
				}
				if($antAsunto!=$soliConsulta->asunto){
				  $b='<br><b>Asunto:</b> '.$soliConsulta->asunto;
				}else{
					$b='';
				}
			   if($antDescripcion!=$soliConsulta->descripcion){
				   $c='<br><b>Descripción:</b> '.$soliConsulta->descripcion;
				}else{
					$c='';
				}
                   if(strlen($a)>0 || strlen($b)>0 || strlen($c)>0){

				        $soliSegui = new  SolicitudSeguimiento();
						$soliSegui->idSolicitud=$id;
						$soliSegui->estadoSolicitud=$soliConsulta->idEstado;
						$soliSegui->observaciones = 'Solicitud Editada, los campos:'.$a.''.$b.''.$c;
						$soliSegui->fechaCreacion=Carbon::now();
						$soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
						$soliSegui->save();
				  }

		} catch (\Exception $e) {
			Debugbar::addException($e);
			$response = ['status' => 500, 'message' => 'Se produjo una excepción en el servidor', "redirect" => ''];
			DB::connection('sqlsrv')->rollback();
		}

		return response()->json($response);

	}
	public function storeArchivosAnex(Request $request){

	  	$v = Validator::make($request->all(),[
	        	'fileAnexos'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'fileAnexos' => 'Archivos',
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
		    	      $newId=$request->idSoli;

		    			 //-------------SUBIR ARCHIVOS CORRESPONDENCIA-----------
		    	    $listArchivos = $request->file('fileAnexos');
		      		$urlPrincipal =Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$newId;
					$filesystem= new Filesystem();
					if(count($listArchivos)!=0){
				    if($filesystem->exists($path)){
				    	$carpeta=$path;
				    	File::makeDirectory($carpeta, 0777, true, true);
				    	for($a=0;$a<count($listArchivos);$a++){

						$name= $listArchivos[$a]->getClientOriginalName();
						$type = $listArchivos[$a]->getMimeType();
						$listArchivos[$a]->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->idEstado=2;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$newId;
				         $soliSegui->estadoSolicitud=2;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();
                        }

				  }else{
				  	$carpeta=$path;
				    	File::makeDirectory($carpeta, 0777, true, true);
				    	for($a=0;$a<count($listArchivos);$a++){

						$name= $listArchivos[$a]->getClientOriginalName();
						$type = $listArchivos[$a]->getMimeType();
						$listArchivos[$a]->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = new Adjunto();
						$archivo->idSolicitud=$newId;
						$archivo->urlArchivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->idEstado=2;
						$archivo->nombreArchivo = $name;
						$archivo->usuarioCreacion = Auth::user()->idUsuario;
				        $archivo->fechaCreacion = Carbon::now();
						$archivo->save();

						 $soliSegui = new  SolicitudSeguimiento();
				         $soliSegui->idSolicitud=$newId;
				         $soliSegui->estadoSolicitud=2;
				         $soliSegui->observaciones = 'Solicitud Editada, se adicionó un nuevo archivo: '.$name;
				         $soliSegui->fechaCreacion=Carbon::now();
				         $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				         $soliSegui->save();
                        }

				  }
				 }
				//----------------------------------


			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);
		}



	public function storeComentario(comentarioRequest $request){
         DB::connection('sqlsrv')->beginTransaction();

		try {

			     $idEmpleado = Auth::user()->idEmpleado;
			    $verficar = Participantes::where('idEmpleado','=',$idEmpleado)->count();
			    if($verficar!=0){
                    $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->first();

                    $cod = $idPar->idParticipante;

                	$comen = new Comentarios();
                	$comen->comentario = $request->comentario;
                	$comen->idSolicitud = $request->idSoli;
                	$comen->idParticipante = $cod;
                	$comen->idEstado=0;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();

                    $part= Participantes::where('idSolicitud',$comen->idSolicitud)->pluck('idEmpleado');
                    $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');





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

				     //-------------SUBIR ARCHIVO COMENTARIO-----------
		    	    $idComentario = $comen->idComentario;
		    	    $idSolicitud = $comen->idSolicitud;
				    $nombre= 'comentarios';
		      		$urlPrincipal = Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$idSolicitud;
				    $file= $request->file('file');


				    if(!empty($file)){
					//si hay archivos crear la ruta con el id del usuario
					$filesystem= new Filesystem();
				    if($filesystem->exists($path)){

					    if($filesystem->isWritable($path)){
						$carpeta=$path.'\\'.$nombre;
						//crea la nueva carpeta
						File::makeDirectory($carpeta, 0777, true, true);
						// se guadarn en el disco
						//dd($file->getClientOriginalName());
						//$name= Auth::user()->apellidosUsuario .$file1->getClientOriginalName();

						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();
					   }else{
					   		DB::rollback();
							return " Error al subir la fotorafía";
					   }


				  }else{

				  	    $carpeta=$path.'\\'.$idSolicitud.'\\'.$nombre;
						//crea la nueva carpeta
						File::makeDirectory($carpeta, 0777, true, true);
						// se guadarn en el disco
						//dd($file->getClientOriginalName());
						//$name= Auth::user()->apellidosUsuario .$file1->getClientOriginalName();
						$name= $file->getClientOriginalName(); ;
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora


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

               /* $data['soli'] = Solicitudes::find($comen->idSolicitud);
                $data['comentario'] = Comentarios::comentarioParticipante($comen->idComentario);
                $data['comEstado'] = Comentarios::where('idSolicitud','=',$comen->idSolicitud)->where('idEstado','=',1)->count();

                  foreach($empCorreo as $a){

                    	if(!empty($a)){
                    		if($a!=env('MAIL_ADMINPARTICIPANTES')){
				                     Mail::send('emails.comentarioParticipante',$data,function($msj) use ($data,$a){
		                             $msj->subject('Nueva comentario en solicitud correspondencia');
		                             if($data['comentario']->archivo!='' || $data['comentario']->archivo!=NULL){
		                                 $msj->attach($data['comentario']->archivo);
		                             }
					                 $msj->to($a);
					                $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
				                 }
			              }
					 }

				   Mail::send('emails.comentarioAdminParticipante',$data,function($msj) use ($data){
		                             $msj->subject('Nueva comentario en solicitud correspondencia');
		                             if($data['comentario']->archivo!='' || $data['comentario']->archivo!=NULL){
		                                 $msj->attach($data['comentario']->archivo);
		                             }
					                 $msj->to(env('MAIL_ADMINPARTICIPANTES'));
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
                  */
	      $response = ['status' => 200, 'message' => '¡Se registro tu nuevo comentario!', "redirect" => ''];
		  DB::connection('sqlsrv')->commit();




		} catch (\Exception $e) {
			Debugbar::addException($e);
			$response = ['status' => 500, 'message' => 'Se produjo una excepción en el servidor', "redirect" => ''];
			DB::connection('sqlsrv')->rollback();
		}

		return response()->json($response);

	}
	public function storeComentarioAsistente(Request $request){
         DB::connection('sqlsrv')->beginTransaction();

		try{

                    $idPar = Participantes::where('idEmpleado','=',7)->where('idSolicitud','=',$request->idSoli)->first();
                    $cod = $idPar->idParticipante;
                    $comen = new Comentarios();
                	$comen->comentario = $request->comentario;
                	$comen->idSolicitud = $request->idSoli;
                	$comen->idParticipante = $cod;
                	$comen->idEstado=0;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();

                    $part= Participantes::where('idSolicitud',$comen->idSolicitud)->pluck('idEmpleado');
                    $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');

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

				     //-------------SUBIR ARCHIVO COMENTARIO-----------
		    	    $idComentario = $comen->idComentario;
		    	    $idSolicitud = $comen->idSolicitud;
				    $nombre= 'comentarios';
		      		$urlPrincipal = Config::get('app.mapeoArchivos');
		      	    $path= $urlPrincipal.'\\'.$idSolicitud;
				    $file= $request->file('fileA');


				    if(!empty($file)){
					//si hay archivos crear la ruta con el id del usuario
					$filesystem= new Filesystem();
				    if($filesystem->exists($path)){

					    if($filesystem->isWritable($path)){
						$carpeta=$path.'\\'.$nombre;
						//crea la nueva carpeta
						File::makeDirectory($carpeta, 0777, true, true);
						// se guadarn en el disco
						//dd($file->getClientOriginalName());
						//$name= Auth::user()->apellidosUsuario .$file1->getClientOriginalName();

						$name= $file->getClientOriginalName();
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora

						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();
					   }else{
					   		DB::rollback();
							return " Error al subir la fotorafía";
					   }


				  }else{

				  	    $carpeta=$path.'\\'.$idSolicitud.'\\'.$nombre;
						//crea la nueva carpeta
						File::makeDirectory($carpeta, 0777, true, true);
						// se guadarn en el disco
						//dd($file->getClientOriginalName());
						//$name= Auth::user()->apellidosUsuario .$file1->getClientOriginalName();
						$name= $file->getClientOriginalName(); ;
						$type = $file->getMimeType();
						$file->move($carpeta,$name);

						//se enlanza cada archivo a su bitacora en la tabla ArchivoBitacora


						$archivo = Comentarios::find($idComentario);
						$archivo->archivo=$carpeta.'\\'.$name;
						$archivo->tipoArchivo=$type;
						$archivo->nombreArchivo = $name;
						$archivo->save();
				  }
				}
				//----------------------------------
				/* $data['soli'] = Solicitudes::find($comen->idSolicitud);
                 $data['comentario'] = Comentarios::comentarioParticipante($comen->idComentario);
                 $data['comEstado'] = Comentarios::where('idSolicitud','=',$comen->idSolicitud)->where('idEstado','=',1)->count();
                  foreach($empCorreo as $a){
                    	if(!empty($a)){
                    		if($a!=env('MAIL_ADMINPARTICIPANTES')){
				                     Mail::send('emails.comentarioParticipante',$data,function($msj) use ($data,$a){
		                             $msj->subject('Nueva comentario en solicitud correspondencia');
		                            if($data['comentario']->archivo!='' || $data['comentario']->archivo!=NULL){
		                                 $msj->attach($data['comentario']->archivo);
		                             }
					                 $msj->to($a);
					                $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
				                 }
			              }
					}
					Mail::send('emails.comentarioAdminParticipante',$data,function($msj) use ($data){
		                             $msj->subject('Nueva comentario en solicitud correspondencia');
		                             $msj->attach($data['comentario']->archivo);
		                             if($data['comentario']->archivo!='' || $data['comentario']->archivo!=NULL){
		                                 $msj->attach($data['comentario']->archivo);
		                             }
					                 $msj->to(env('MAIL_ADMINPARTICIPANTES'));
		                                });
                     */
	      $response = ['status' => 200, 'message' => '¡Se registro tu nuevo comentario!', "redirect" => ''];
		  DB::connection('sqlsrv')->commit();




		} catch (\Exception $e) {
			Debugbar::addException($e);
			$response = ['status' => 500, 'message' => 'Se produjo una excepción en el servidor', "redirect" => ''];
			DB::connection('sqlsrv')->rollback();
		}

		return response()->json($response);

	}

	public function listatodasAsistente()
 	{
		$data = ['title' 			=> 'Solicitudes correspondencia'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes', 'url' => ''],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::all();
		return view('solicitudes.listaTodasAsistente',$data);
	}
	 public function  getDataRowsTodasAsistente(Request $request){
        $permisos = UserOptions::getAutUserOptions();
        $sol =  vwSolicitudes::listAdmin();
        	return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {
		 	        if($dt->idEstado==1){
	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==2){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==3){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==4){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==5){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==6){
	                	$fechaE = Historial::where('idSolicitud','=',$dt->idSolicitud)->where('idEstado','=',6)->first();
	                	  $f=$fechaE->fechaCreacion;
	                	return '<span class="label label-primary">'.$dt->nombreEstado." <br><br> ".$f.'</span>';
	                	return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==11){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) use($permisos) {
       				if(in_array(491, $permisos)){
       							return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								 <li><a href="'.route('verSolicitudLectura',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
								  </ul>
								</div> ';

       				}else{
       						return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								 <li><a href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
								  </ul>
								</div> ';

       				}
					})
		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}
	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}



	        			})


			->make(true);
    }

   public function listapendientesAsignar()
 	{
		$data = ['title' 			=> 'Solicitudes pendientes de asignar'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes pendientes de asignar', 'url' => route('lista.pendientes.asginar')],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::all();
		return view('solicitudes.listaPendientesAsisente',$data);
	}
	 public function  getDataRowsPendientesAsignar(Request $request){

        $sol =  vwSolicitudes::listAdmin()->where('idEstado',1);
        	return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {

	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	            		})

		 ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								  <li><a  href="'.route('verSolicitudLectura',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>ASIGNAR</a></li>
								  </ul>
								</div> ';
					})
		     ->addColumn('dias', function ($dt) {


	                		//SE COMPARA CON LA FECHA DE CREACIÓN CON LA ACTUAL
						    $d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=date("Y-m-d");
	                		/*$datetime1 = date_create($d1);
							$datetime2 = date_create($d2);
							$interval = $datetime1-$datetime2;
							$ddias=$interval->format('%d días');
						    $ddias= $ddias+1;*/

						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);

						    return '<span class="label label-success">'.$dias.' días</span>';

	            })

		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaRecepcion','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}
	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}



	        			})


			->make(true);
    }

      public function listaNuevasRecepcion()
 	{
		$data = ['title' 			=> 'Solicitudes nuevas'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes nuevas', 'url' =>''],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::all();
		return view('solicitudes.listaNuevasRecepcion',$data);
	}
	 public function  getDataRowsNuevasRecepcion(Request $request){

        $sol =  vwSolicitudes::listAdmin()->where('idEstado',1);
        	return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {

	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	            		})

		 ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								<li><a  href="'.route('editarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'"  ><i class="fa fa-edit"></i>EDITAR</a></li>
								  </ul>
								</div> ';
					})
		     ->addColumn('dias', function ($dt) {


	                		//SE COMPARA CON LA FECHA DE CREACIÓN CON LA ACTUAL
						    $d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=date("Y-m-d");
	                		/*$datetime1 = date_create($d1);
							$datetime2 = date_create($d2);
							$interval = $datetime1-$datetime2;
							$ddias=$interval->format('%d días');
						    $ddias= $ddias+1;*/

						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);

						    return '<span class="label label-success">'.$dias.' días</span>';

	            })

		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaRecepcion','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}
	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}



	        			})


			->make(true);
    }




    public function verFirmarRevision($idEstado)
 	{
 		if($idEstado==7){
 			$bar='Solicitudes en revisión';
 		}elseif($idEstado==8){
 			$bar='Solicitudes para firma';
 		}else{
 			$bar='Solicitudes correspondencia';
 		}
		$data = ['title' 			=> $bar
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	$bar, 'url' => ''],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		//$data['est'] = EstadoSolicitud::all();
		$data['estado']=$idEstado;
		return view('solicitudes.listaRevisionFirma',$data);
	}
	public function  getDataRowsRevisionFirma(Request $request){

        $sol =  vwSolicitudes::listAdmin()->where('idEstado',$request->get('idEstado'));
        	return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {

	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	            		})

		 ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								 <li><a href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
								</div> ';
					})
		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaRecepcion','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('fechaDetalle')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaDetalle))."%");
	        				}
	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}



	        			})


			->make(true);
    }




    public function asignadasAsistente()
 	{
		$data = ['title' 			=> 'Solicitudes asignadas'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes asignadas', 'url' => route('lista.asignadas.asistente')],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::all();
		return view('solicitudes.listaAsignadaAsistente',$data);
	}
	public function  getDataRowsAsignadasAsistente(Request $request){

        $sol =  vwSolicitudes::listAdmin()->where('idEstado',2);
        	return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {

	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	            		})

		 ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								 <li><a href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
								</div> ';
					})
		 	 ->addColumn('dias', function ($dt) {
		      	 if(!empty($dt->dias)){
		      	 	return '<span class="label label-info">'.$dt->dias.' días</span>';

		      	 }else{
		      	 			      return '<span class="label label-info">2 días</span>';
		      	 }
	            })
		     ->addColumn('diasproceso', function ($dt) {


	                		//SE COMPARA CON LA FECHA DE CREACIÓN CON LA ACTUAL
						    $d1= date('Y-m-d',strtotime($dt->fechaDetalle));
	                		$d2=date('Y-m-d');

						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);
							if(!empty($dt->dias)){
								$diaresolver=$dt->dias;
							}else{
								$diaresolver ='2';
							}
							if($dias <= $diaresolver){
								return '<span class="label label-success">'.$dias.' días</span>';
							}else{
								return '<span class="label label-danger">'.$dias.' días</span>';
							}



	            })


		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}
	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}



	        			})


			->make(true);
    }

      public function listaPendienParti()
 	{
		$data = ['title' 			=> 'Solicitudes asignadas'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes asignadas', 'url' => ''],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::all();
        $permisos = UserOptions::getAutUserOptions();

         if(in_array(497,$permisos)){
         	$idJefe = PersonaParticipante::where('idEmpleadoAsistente',Auth::user()->idEmpleado)->select('idEmpleado')->first();
         	if(empty($idJefe)){
             Session::flash('msnError', '¡NO TIENE PERMISOS PARA VER NINGUNA SOLICITUD, POR FAVOR CONTACTAR CON INFORMÁTICA!');
				      $data = ['title' 			=> 'Inicio.'
						,'subtitle'			=> ''
						,'breadcrumb' 		=> [
					 		['nom'	=>	'', 'url' => '#']
						]];
		     return view('inicio.index',$data);
		   }
         }

		return view('solicitudes.pendientesParticipante',$data);
	}

    public function   getDataRowsPendiePartici(Request $request){
    	         $permisos = UserOptions::getAutUserOptions();
    	         $idpartjefe='';


    	      	            //SI EL USUARIO ES PARTICIPANTE CONSULTAMOS LAS SOLICITUDES ASIGNADAS
					      	if(Auth::user()->idEmpleado=='321'){
					      		      //LISTAR CORREPSONDIENTES SOLO ASIGNADAS YA QUE ESTE USUARIO ERA ASESOR
					        	      $sol = vwSolicitudes::listParticipantes(Auth::user()->idEmpleado)
					        		   ->whereIn('T1.idEstado',[2,3,11])
					        	       ->where('T1.idTipo',1)
					        	       ->where('T2.idEstado',1)
					        	       ->distinct();
					         }else{
					         	   if(in_array(497, $permisos)){
					         	               //EL USUARIO ES ASESOR DE UN JEFE
		        	                          //CONSULTAMOS EL ID DEL JEFE PARA MOSTRAR LAS SOLICITUDES ASIGNADAS
						                $idJefe = PersonaParticipante::where('idEmpleadoAsistente',Auth::user()->idEmpleado)->select('idEmpleado')->first();
						                $idpartjefe=$idJefe->idEmpleado;
					         	   }else{
					         	   		$idpartjefe=Auth::user()->idEmpleado;
					         	   }

							       $sol = vwSolicitudes::listParticipantes($idpartjefe)
							        ->whereIn('T1.idEstado',[2,3,11])
							        ->where('T1.idTipo',1)
							        ->where('T2.idEstado',1)
							         ->distinct();

					         }

            return Datatables::of($sol)
            		 ->addColumn('nombreEstado', function ($dt) {

	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	            		})
		    ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								   <li><a  href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-eye"></i>VER DETALLE</a></li>

								  </ul>
								</div> ';




					})
             //-------------------------------------AQUI TRABAJAR FECHA
		      ->addColumn('dias', function ($dt) {
		      	 if(!empty($dt->dias)){
		      	 	return '<span class="label label-info">'.$dt->dias.' días</span>';

		      	 }else{
		      	 			      return '<span class="label label-info">2 días</span>';
		      	 }
	            })
		        ->addColumn('diasproceso', function ($dt) {
		        	        $d1= date('Y-m-d',strtotime($dt->fechaDetalle));
	                		$d2=date('Y-m-d');
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);
							if(!empty($dt->dias)){
								$diaresolver=$dt->dias;
							}else{
								$diaresolver ='2';
							}
							if($dias <= $diaresolver){
								return '<span class="label label-success">'.$dias.' días</span>';
							}else{
								return '<span class="label label-danger">'.$dias.' días</span>';
							}
	            })

		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}

	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}




	        			})


			->make(true);



	}

	 public function listaInformativaParti()
 	{
		$data = ['title' 			=> 'Solicitudes informativas'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes informativas', 'url' => ''],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['tit'] = Titulares::all();
		$data['est'] = EstadoSolicitud::all();
        $permisos = UserOptions::getAutUserOptions();

         if(in_array(497,$permisos)){
         	$idJefe = PersonaParticipante::where('idEmpleadoAsistente',Auth::user()->idEmpleado)->select('idEmpleado')->first();
         	if(empty($idJefe)){
             Session::flash('msnError', '¡NO TIENE PERMISOS PARA VER NINGUNA SOLICITUD, POR FAVOR CONTACTAR CON INFORMÁTICA!');
				      $data = ['title' 			=> 'Inicio.'
						,'subtitle'			=> ''
						,'breadcrumb' 		=> [
					 		['nom'	=>	'', 'url' => '#']
						]];
		     return view('inicio.index',$data);
		   }
         }

		return view('solicitudes.listaInformativa',$data);
	}


    public function   getDataRowsInformaPartici(Request $request){
    	         $permisos = UserOptions::getAutUserOptions();
    	         $idpartjefe='';
    	      	            //SI EL USUARIO ES PARTICIPANTE CONSULTAMOS LAS SOLICITUDES ASIGNADAS
					         	   if(in_array(497, $permisos)){
					         	               //EL USUARIO ES ASESOR DE UN JEFE
		        	                          //CONSULTAMOS EL ID DEL JEFE PARA MOSTRAR LAS SOLICITUDES ASIGNADAS
						                $idJefe = PersonaParticipante::where('idEmpleadoAsistente',Auth::user()->idEmpleado)->select('idEmpleado')->first();
						                $idpartjefe=$idJefe->idEmpleado;
					         	   }else{
					         	   		$idpartjefe=Auth::user()->idEmpleado;
					         	   }

							       $sol = vwSolicitudes::listParticipantes($idpartjefe)
							        ->where('T1.idClasificacion',2)
							        ->distinct();

            return Datatables::of($sol)
		    ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								   <li><a  href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-eye"></i>VER DETALLE</a></li>

								  </ul>
								</div> ';




					})


		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}

	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}

	        			})

			->make(true);



	}

	 public function  getDataRowsSolicitudesAsiAsesore(Request $request){

        $sol = vwSolicitudes::listParticipantes(Auth::user()->idEmpleado)
                ->where('T2.idEstado',1)
                ->whereIn('T1.idEstado',[2,3])
                ->distinct();
        	return Datatables::of($sol)

		 ->addColumn('detalle', function ($dt) {

	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								  <li><a  href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-eye"></i>VER DETALLE</a></li>


								  </ul>
								</div> ';




					})
		     ->addColumn('dias', function ($dt) {
		 	  if(!empty($dt->fechaFinalProceso)){
		      	 	        $d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=  $dt->fechaFinalProceso;
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);

		      	 	       return '<span class="label label-info">'.$dt->dias.' días</span>';

		      	 }else{
		      	 			$d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=date("Y-m-d");
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);
						     return '<span class="label label-info">'.$dias.' días</span>';
		      	 }
	            })

		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}
	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}



	        			})


			->make(true);
    }
     public function  getDataRowsSolicitudesRevision(Request $request){

        $sol = vwSolicitudes::listParticipantes(Auth::user()->idEmpleado)
                ->where('T1.idEstado',7)
                ->where('T1.idTipo',1)
                ->distinct();
        	return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {
		 	        if($dt->idEstado==1){
	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==2){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==3){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==4){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==5){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==6){
	                	$fechaE = Historial::where('idSolicitud','=',$dt->idSolicitud)->where('idEstado','=',6)->first();
	                	  $f=$fechaE->fechaCreacion;
	                	return '<span class="label label-primary">'.$dt->nombreEstado." <br><br> ".$f.'</span>';
	                	return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==11){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) {

	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								  <li><a  href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-eye"></i>VER DETALLE</a></li>


								  </ul>
								</div> ';




					})
		     ->addColumn('dias', function ($dt) {
		 	  if($dt->idfechaRespuesta!=''){
		 	        	if($dt->idEstado==1 || $dt->idEstado==2){
		 	        	     if($dt->idfechaRespuesta==3){
		 	        	     	    //comparar con fecha actual
	                				$d1= date('Y-m-d',strtotime($dt->fechaDetalle));
	                				$d2=date("Y-m-d");
	                				$datetime1 = date_create($d1);
									$datetime2 = date_create($d2);
	                				/*$datetime1 = date_create('2009-10-11');
									$datetime2 = date_create('2009-10-13');*/
									$interval = date_diff($datetime1, $datetime2);
									$ddias=$interval->format('%d días');
                                    $ddias= $ddias+1;

									if($ddias>=$dt->dias){
									return '<span class="label label-danger">'.$ddias.' días</span>';
									}
						    		return '<span class="label label-success">'.$ddias.' días</span>';



	               		     }else{
	               		     	    //comparar con fecha actual
	                				$d1= date('Y-m-d',strtotime($dt->fechaDetalle));
	                				$d2=date("Y-m-d");
	                				$datetime1 = date_create($d1);
									$datetime2 = date_create($d2);
	                				/*$datetime1 = date_create('2009-10-11');
									$datetime2 = date_create('2009-10-13');*/
									$interval = date_diff($datetime1, $datetime2);
									$ddias=$interval->format('%d días');
								    $ddias= $ddias+1;

									if($ddias>1){
									return '<span class="label label-danger">'.$ddias.' días</span>';
									}
						    		return '<span class="label label-success">'.$ddias.' días</span>';


	               		     }
	               		}else{
	               			//comparar con fecha de creacion
	                		$d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2= date('Y-m-d',strtotime($dt->fechaDetalle));
	                		$datetime1 = date_create($d1);
							$datetime2 = date_create($d2);
							/*$datetime1 = date_create('2009-10-11');
							$datetime2 = date_create('2009-10-13');*/
							$interval = date_diff($datetime1, $datetime2);
							$ddias=$interval->format('%d días');
						    $ddias= $ddias+1;
						    return '<span class="label label-success">'.$ddias.' días</span>';

	               		}
	           }else{

	                		//SE COMPARA CON LA FECHA DE CREACIÓN CON LA ACTUAL
						    $d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=date("Y-m-d");
	                		$datetime1 = date_create($d1);
							$datetime2 = date_create($d2);
							$interval = date_diff($datetime1, $datetime2);
							$ddias=$interval->format('%d días');
						     $ddias= $ddias+1;
						    return '<span class="label label-success">'.$ddias.' días</span>';


	               }//IF PRINCIPAL
	            })

		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaRecepcion','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}
	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}



	        			})


			->make(true);
    }


	 public function   getDataRowsSolicitudes(Request $request){
        $permisos = UserOptions::getAutUserOptions();

        //CONSULTAMOS SOLICITUDES PARA RECEPCIONISTA DE SOLICITUDES
        if(in_array(470, $permisos, true)){
		   $sol = vwSolicitudes::listAdmin();
		   return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {
		 	        if($dt->idEstado==1){
	             return '<s
	             pan class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==2){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==3){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==4){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==5){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==6){
	                	 $fechaE = Historial::where('idSolicitud','=',$dt->idSolicitud)->where('idEstado','=',6)->first();
	                	  $f=$fechaE->fechaCreacion;
	                	return '<span class="label label-primary">'.$dt->nombreEstado." <br><br> ".$f.'</span>';
	                }else if($dt->idEstado==11){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) {
                   if($dt->idEstado==1){
                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">


									<li><a  href="'.route('editarSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'"  ><i class="fa fa-edit"></i>EDITAR</a></li>

								  </ul>
								</div> ';


	               }else{
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								  <li><a  href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>


								  </ul>
								</div> ';

	               }


					})
		   ->addColumn('dias', function ($dt) {
		 	   	 if(!empty($dt->fechaFinalProceso)){
		      	 	        $d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=  $dt->fechaFinalProceso;
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);

		      	 	       return '<span class="label label-info">'.$dt->dias.' días</span>';

		      	 }else{
		      	 			$d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=date("Y-m-d");
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);
						     return '<span class="label label-info">'.$dias.' días</span>';
		      	 }
	           })


			->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaRecepcion','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}
	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}



	        			})

			->make(true);

        }else if(in_array(471, $permisos, true)){
        	$sol = vwSolicitudes::listParticipantes(Auth::user()->idEmpleado)
        	       ->where('T1.idTipo',1)
        	       ->distinct();
        	return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {
		 	        if($dt->idEstado==1){
	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==2){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==3){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==4){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==5){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==6){
	                	$fechaE = Historial::where('idSolicitud','=',$dt->idSolicitud)->where('idEstado','=',6)->first();
	                	  $f=$fechaE->fechaCreacion;
	                	return '<span class="label label-primary">'.$dt->nombreEstado." <br><br> ".$f.'</span>';
	                	return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==11){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								   <li><a  href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-eye"></i>VER DETALLE</a></li>

								  </ul>
								</div> ';




					})
             //-------------------------------------AQUI TRABAJAR FECHA
		      ->addColumn('dias', function ($dt) {
		      	 if(!empty($dt->fechaFinalProceso)){
		      	 	        $d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=  $dt->fechaFinalProceso;
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);

		      	 	       return '<span class="label label-info">'.$dt->dias.' días</span>';

		      	 }else{
		      	 			$d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=date("Y-m-d");
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);
						     return '<span class="label label-info">'.$dias.' días</span>';
		      	 }
	            })

		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}

	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}




	        			})


			->make(true);

        }else if(in_array(497, $permisos, true)){

 					$idJefe = PersonaParticipante::where('idEmpleadoAsistente',Auth::user()->idEmpleado)->select('idEmpleado')->first();
				     $idpartjefe=$idJefe->idEmpleado;
        			$sol = vwSolicitudes::listParticipantes($idpartjefe)
        	       ->where('T1.idTipo',1)
        	       ->distinct();
        	return Datatables::of($sol)
		 ->addColumn('nombreEstado', function ($dt) {
		 	        if($dt->idEstado==1){
	             return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==2){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==3){
	                	return '<span class="label label-warning">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==4){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==5){
	                	return '<span class="label label-success">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==6){
	                	$fechaE = Historial::where('idSolicitud','=',$dt->idSolicitud)->where('idEstado','=',6)->first();
	                	  $f=$fechaE->fechaCreacion;
	                	return '<span class="label label-primary">'.$dt->nombreEstado." <br><br> ".$f.'</span>';
	                	return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }else if($dt->idEstado==11){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								   <li><a  href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-eye"></i>VER DETALLE</a></li>

								  </ul>
								</div> ';




					})
             //-------------------------------------AQUI TRABAJAR FECHA
		      ->addColumn('dias', function ($dt) {
		      	 if(!empty($dt->fechaFinalProceso)){
		      	 	        $d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=  $dt->fechaFinalProceso;
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);

		      	 	       return '<span class="label label-info">'.$dt->dias.' días</span>';

		      	 }else{
		      	 			$d1= date('Y-m-d',strtotime($dt->fechaCreacion));
	                		$d2=date("Y-m-d");
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);
						     return '<span class="label label-info">'.$dias.' días</span>';
		      	 }
	            })

		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}

	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}




	        			})


			->make(true);
        }

	}
	   public function getEmpleadosJefes(){
          $emple = Participantes::getEmpleadosJefe();
          return Datatables::of($emple)
			->make(true);
		}
		public function getComentarios(Request $request){
        $idSoli = $request->id;
	    $comen = Comentarios::listComentarios($idSoli);
		 return response()->json($comen);
		}
		public function getParticipanteFecha(Request $request){
        $idParticipante = $request->id;
	    $comen = Participantes::find($idParticipante);
		 return response()->json($comen);
		}



	public function storeParticipantes(Request $request){

	  	$v = Validator::make($request->all(),[
	        	'idParticipante'=>'required',
	        	'idSoliDetalle'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'idParticipante' => 'empleados',
	   		    'idSoliDetalle'=>'required',
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


		        $listPart = $request->idParticipante;
		    	$soli2 = $request->idSoliDetalle;

		    for($i=0;$i<count($listPart); $i++){

		       if(Participantes::where('idSolicitud',$request->idSoliDetalle)->where('idEmpleado',$listPart[$i])->where('idEstado',1)->count()==0){
		    	$part =  new Participantes();
		    	$part->idSolicitud = $request->idSoliDetalle;
                $part->idEmpleado = $listPart[$i];
                $part->usuarioCreacion = Auth::user()->idUsuario;
				$part->fechaCreacion = Carbon::now();
				$part->idEstado=1;
				$part->caso=0;
		    	$part->save();

		    	 $empCorreo = User::where('idEmpleado',$listPart[$i])->first();
		    	 $correo = $empCorreo->correo;
		    	 $data['soli'] = Solicitudes::find($request->idSoliDetalle);
		    	 if(!empty($correo)){
				                     Mail::send('emails.nuevaSolicitud',$data,function($msj) use ($data,$correo){
		                             $msj->subject('Nueva solicitud de correspondencia');
					                 $msj->to($correo);
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
			     }
			    SolicitudesController::correoAsistenteJefe($listPart[$i],$request->idSoliDetalle,'emails.nuevaSolicitud','Nueva solicitud de correspondencia');

		    	 }else{
		    	 	return "<ul class='text-warning'>¡ERROR! algunos empleados ya estan registrados<ul/>";
		    	 }
		     }



                 //-----------------------NUEVOS PARTICIPANTES--------------------------
				$nomPar='';
				for($i=0;$i<count($listPart); $i++){

		    	 $emp = User::where('idEmpleado',$listPart[$i])->first();
		    	 $nomPar=$nomPar." <b>".$emp->nombresUsuario." ".$emp->apellidosUsuario."</b><br>";

		        }
		           if(strlen($nomPar)>0){
		           $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSoliDetalle;
				   $soliSegui->estadoSolicitud=2;
				   $soliSegui->observaciones = 'Se agregaron nuevos responsables en la solicitud: <br>'.$nomPar;
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();
				}


			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);
		}


   public function verSolicitud($idSolicitud){
   	    $id = Crypt::decrypt($idSolicitud);

		$data = ['title' 			=> 'SOLICITUD DE CORRESPONDENCIA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes de corresponedencia', 'url' => ''],
			 		['nom'	=>	'Comentar Solicitud', 'url' => '#'],
				]];
         $soli = Solicitudes::find($id);
         $nit = $soli->nitSolicitante;
         $data['estado'] = EstadoSolicitud::find($soli->idEstado);
         $data['info'] =$soli;
         $data['pn'] = $datosSolicitante = PersonaNatural::find($nit);
         if(!empty($datosSolicitante)){
         $data['t1']= json_decode($datosSolicitante->telefonosContacto)[0];
         $data['t2']= json_decode($datosSolicitante->telefonosContacto)[1];
     	 }else{
     	 $data['t1']= "";
         $data['t2']= "";
   		  }
         if($soli->idEstado==5 || $soli->idEstado==6){
         	$historia = Historial::where('idSolicitud','=',$id)
         	 ->where('idEstado','=',5)
         	 ->get();

         	 $data['observacion'] = $historia;
           }else{
          $data['observacion'] = null;
          }
        $data['archivos'] = Adjunto::where('idSolicitud','=',$id)->get();
        $data['comentarios'] =  Comentarios::listComentarios($id);
        $data['numCom'] = Comentarios::where('idSolicitud','=',$id)->count();
        $data['comEstado'] = Comentarios::where('idSolicitud','=',$id)->where('idEstado','=',1)->count();
        $data['obsSoli'] = Comentarios::where('idSolicitud','=',$id)->where('idEstado','=',1)->first();

        $listPart = Participantes::where('idSolicitud','=',$id)->pluck('idEmpleado');
        $data['part'] = Participantes::getEmpleadosSolicitud($id);
      //dd($data['part']);
        $listTit = SolicitudesTitular::where('idSolicitud','=',$id)->pluck('idTitular');
        $data['tit'] = Titulares::whereIn('idTitular',$listTit)->get();

        $data['empleados'] =Participantes::getEmpleadosJefe();

        $data['listClasificacion'] = Clasificacion::all();
        $data['listFecha'] = FechaRespuesta::all();
        $data['listAcciones'] = Acciones::all();
        $data['accSolicitud'] = SolicitudAcciones::where('idSolicitud',$id)->select('idAccion')->pluck('idAccion')->toArray();
        $data['usuarioEntregado'] = UsuarioEntrega::where('idSolicitud',$id)->first();
 		return view('solicitudes.verSolicitud',$data);
	}

	  public function verSolicitudLectura($idSolicitud){
   	    $id = Crypt::decrypt($idSolicitud);

		$data = ['title' 			=> 'SOLICITUD DE CORRESPONDENCIA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes de corresponedencia', 'url' => ''],
			 		['nom'	=>	'Comentar Solicitud', 'url' => '#'],
				]];
         $soli = Solicitudes::find($id);
         $nit = $soli->nitSolicitante;
         $data['estado'] = EstadoSolicitud::find($soli->idEstado);
         $data['info'] =$soli;
         $data['pn'] = $datosSolicitante = PersonaNatural::find($nit);
         if(!empty($datosSolicitante)){
         $data['t1']= json_decode($datosSolicitante->telefonosContacto)[0];
         $data['t2']= json_decode($datosSolicitante->telefonosContacto)[1];
     	 }else{
     	 $data['t1']= "";
         $data['t2']= "";
   		  }
         if($soli->idEstado==5 || $soli->idEstado==6){
         	$historia = Historial::where('idSolicitud','=',$id)
         	 ->where('idEstado','=',5)
         	 ->get();

         	 $data['observacion'] = $historia;
           }else{
          $data['observacion'] = null;
          }
        $data['archivos'] = Adjunto::where('idSolicitud','=',$id)->get();
        $data['comentarios'] =  Comentarios::listComentarios($id);
        $data['numCom'] = Comentarios::where('idSolicitud','=',$id)->count();
        $data['comEstado'] = Comentarios::where('idSolicitud','=',$id)->where('idEstado','=',1)->count();
        $data['obsSoli'] = Comentarios::where('idSolicitud','=',$id)->where('idEstado','=',1)->first();

        $listPart = Participantes::where('idSolicitud','=',$id)->pluck('idEmpleado');
        $data['part'] = Participantes::getEmpleadosSolicitud($id);
      //dd($data['part']);
        $listTit = SolicitudesTitular::where('idSolicitud','=',$id)->pluck('idTitular');
        $data['tit'] = Titulares::whereIn('idTitular',$listTit)->get();

        $data['empleados'] =Participantes::getEmpleadosJefe();

        $data['listClasificacion'] = Clasificacion::whereIn('idClasificacion',[1,2])->get();
        $data['listFecha'] = FechaRespuesta::all();
        $data['listAcciones'] = Acciones::all();
        $data['accSolicitud'] = SolicitudAcciones::where('idSolicitud',$id)->select('idAccion')->pluck('idAccion')->toArray();
        $data['usuarioEntregado'] = UsuarioEntrega::where('idSolicitud',$id)->first();
 		return view('solicitudes.verSolicitudLectura',$data);
	}
	public function editarSolicitud($idSolicitud){
   	    $id = Crypt::decrypt($idSolicitud);

		$data = ['title' 			=> 'EDITAR SOLICITUD DE CORRESPONDENCIA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Solicitudes de corresponedencia', 'url' => route('lista.solicitud')],
			 		['nom'	=>	'Editar Solicitud', 'url' => '#'],
				]];
         $soli = Solicitudes::find($id);
         $nit = $soli->nitSolicitante;
         $tit = SolicitudesTitular::infoTitulares($id);
         $persona = PersonaNatural::find($nit);
         $data['info'] =$soli;
         $data['persona'] =$persona;
         $data['titulares'] = $tit;
         $data['archivos'] = Adjunto::where('idSolicitud','=',$id)->get();
        //dd(json_decode($persona->telefonosContacto)[1]);
        //dd(strlen($persona->telefonosContacto));
 		return view('solicitudes.editarSolicitud',$data);
	}
     public function entregarSolicitud(Request $request){


			$sol=Solicitudes::find($request->idSolicitud);
			$sol->idEstado=6;

			if($sol->save()){
				$historia = new Historial();
				$historia->idSolicitud =  $request->idSolicitud;
				$historia->idEstado = 6;
				$historia->usuarioCreacion = Auth::user()->idUsuario;
				$historia->fechaCreacion = Carbon::now();
				$historia->save();

               	$part= Participantes::where('idSolicitud',$request->idSolicitud)->pluck('idEmpleado');
                $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');
               /* $data['soli'] = Solicitudes::find($request->idSolicitud);

                foreach($empCorreo as $a){
                    	if(!empty($a)){
                    		if($a!=env('MAIL_ADMINPARTICIPANTES')){
				                     Mail::send('emails.respuestaUsuario',$data,function($msj) use ($data,$a){
		                             $msj->subject('Solicitud correspondencia entregada');
					                 $msj->to($a);
		                                });
				                 }
			              }
					}
					Mail::send('emails.aprobarComentario',$data,function($msj) use ($data){
		                             $msj->subject('Solicitud correspondencia entregada');
					                 $msj->to(env('MAIL_ADMINPARTICIPANTES'));
		                                });
				*/
				return response()->json(['status' => 200, 'message' => "Su solicitud ha sido procesada"]);
			}
			else{
				return response()->json(['status' => 400, 'message' => "No se ha podido procesar la solicitud"]);
			}



	}
	public function procesarSolicitud(Request $request){
			$sol=Solicitudes::find($request->idSolicitud);
			$sol->idEstado=4;

			if($sol->save()){
				$historia = new Historial();
				$historia->idSolicitud =  $request->idSolicitud;
				$historia->idEstado = 4;
				$historia->usuarioCreacion = Auth::user()->idUsuario;
				$historia->fechaCreacion = Carbon::now();
				$historia->save();
				return response()->json(['status' => 200, 'message' => "Su solicitud ha sido procesada"]);
			}
			else{
				return response()->json(['status' => 400, 'message' => "No se ha podido procesar la solicitud"]);
			}



	}

	public function storeNotificacion(Request $request){
			$v = Validator::make($request->all(),[
	        	'idSoliNotificar'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'idSoliNotificar' => 'id solicitud',

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
		    	$historia = new Historial();
				$historia->idSolicitud =  $request->idSoliNotificar;
				$historia->idEstado = 5;
				$historia->observacion = $request->observacion;
				$historia->usuarioCreacion = Auth::user()->idUsuario;
				$historia->fechaCreacion = Carbon::now();
				$historia->save();

				$usua = new UsuarioEntrega();
				$usua->idSolicitud = $request->idSoliNotificar;
				$usua->nombres = $request->notnombres;
				$usua->apellidos = $request->notapellidos;
				$usua->email = $request->notcorreo;
				$usua->telefono = $request->nottel;
				$usua->nit = $request->notnit;
				$usua->dui =$request->notdui;
				$usua->observacion= $request->observacion;
				$usua->usuarioCreacion = Auth::user()->idUsuario;
				$usua->fechaCreacion = Carbon::now();
				$usua->save();

				$soli=Solicitudes::find($request->idSoliNotificar);
				$soli->idEstado=5;
				$soli->fechaFinalProceso = date('Y-m-d');
				$soli->save();

				 if(!empty($soli->correoNotificar)){
				 $data['soli']='';
				 $empCorreo=$soli->correoNotificar;
                 Mail::send('emails.usuarioEnt',$data,function($msj) use ($data,$empCorreo){
		                             $msj->subject('Correspondencia DNM');
					                 $msj->to($empCorreo);
		                                });
                }

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);
		}

	public function storeFechaParticipante(Request $request){
			$v = Validator::make($request->all(),[
	        	'fechaParticipante'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		    'fechaParticipante' => 'fecha'

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
		    	$part = Participantes::find($request->idmodificarPart);
				$part->fechaRespuesta =  date('Y-m-d',strtotime($request->fechaParticipante));
				$part->usuarioModificacion = Auth::user()->idUsuario;
				$part->fechaModificacion = Carbon::now();
				$part->save();

				 //$part= Participantes::where('idSolicitud',$part->idSolicitud)->pluck('idEmpleado');
                /* $empCorreo = User::where('idEmpleado',$part->idEmpleado)->first();
                 $data['soli'] = Solicitudes::find($part->idSolicitud);
                 if(!empty($empCorreo->correo)){
                 Mail::send('emails.fechaRespuesta',$data,function($msj) use ($data,$empCorreo){
		                             $msj->subject('Modificación de fecha en solicitud de correspondencia');
					                 $msj->to($empCorreo->correo);
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
                }
                   */



			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);
		}
		public function aprobarComentario(Request $request){

			$v = Validator::make($request->all(),[
	        	'txt'=>'required',
	        	'idSolicitud'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'txt'=>'id'

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
		    	$estComen = Comentarios::find($request->txt);
		      	$estComen->idEstado=1;
		      	$estComen->save();

		      	$part= Participantes::where('idSolicitud',$request->idSolicitud)->pluck('idEmpleado');
                $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');

                  /*$data['soli'] = Solicitudes::find($request->idSolicitud);

                  foreach($empCorreo as $a){
                    	if(!empty($a)){
				                     Mail::send('emails.aprobarComentario',$data,function($msj) use ($data,$a){
		                             $msj->subject('Nueva observación en solicitud correspondencia');
					                 $msj->to($a);
		                                });
			              }
					}


	             Mail::send('emails.aprobarComentario',$data,function($msj) use ($data){
		                             $msj->subject('Nueva observación en solicitud correspondencia');
					                 $msj->to(env('MAIL_ASISTENTE'));
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
				*/

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}

	public function destroyTitular(Request $request){

			$v = Validator::make($request->all(),[
	        	'txtTitular'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'txtTitular'=>'id del titular'

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
		    	$infotitular = SolicitudesTitular::find($request->txtTitular);
		    	$idD=$infotitular->idTitular;
		      	$infotitular->delete();

		      	   $nomT = Titulares::find($idD);
		      	   $soli = Solicitudes::find($request->idSolicitud);
                   $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSolicitud;
				   $soliSegui->estadoSolicitud=$soli->idEstado;
				   $soliSegui->observaciones = 'Solicitud Editada, se a quitado el <b>titular:</b> '.$nomT->nombreTitular.' de la solicitud de correspondencia';
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();


			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}
	public function destroyArchivo(Request $request){

			$v = Validator::make($request->all(),[
	        	'txtArchivo'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'txtArchivo'=>'id del archivo'

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
		    	$infoArchivo = Adjunto::find($request->txtArchivo);
		    	$nom=$infoArchivo->nombreArchivo;
		      	$infoArchivo->delete();

		      	   $soli = Solicitudes::find($request->idSolicitud);
                   $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSolicitud;
				   $soliSegui->estadoSolicitud=$soli->idEstado;
				   $soliSegui->observaciones = 'Solicitud Editada, se a quitado el <b>archivo:</b> '.$nom.' de la solicitud de correspondencia';
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}
	public function destroyParticipante(Request $request){

			$v = Validator::make($request->all(),[
	        	'txtParticipante'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'txtParticipante'=>'id del participante'

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


		    	$infoPart = Participantes::find($request->txtParticipante);
		    	if(count($infoPart->comentarios)>0){
		    		$asistentes = PersonaParticipante::where('idEmpleado',$infoPart->idEmpleado)->first();
		    		if(!empty($asistentes->idEmpleadoAsistente)){
		    			$idAsistente=$asistentes->idEmpleadoAsistente;
		    			$partasiste=Participantes::where('idSolicitud',$infoPart->idSolicitud)->where('idEmpleado',$idAsistente)->first();
		    			if(!empty($partasiste)){
		    				$partasiste->comentarios()->delete();
		    				$partasiste->delete();
		    			}
		    		}

		    		$infoPart->comentarios()->delete();
		    		$infoPart->delete();

		    	}else{
		    		$asistentes = PersonaParticipante::where('idEmpleado',$infoPart->idEmpleado)->first();
		    		if(!empty($asistentes->idEmpleadoAsistente)){
		    			$idAsistente=$asistentes->idEmpleadoAsistente;
		    			$partasiste=Participantes::where('idSolicitud',$infoPart->idSolicitud)->where('idEmpleado',$idAsistente)->first();
		    			if(!empty($partasiste)){
		    				$partasiste->comentarios()->delete();
		    				$partasiste->delete();
		    			}
		    		}

		    	    $infoPart->delete();
		    	}
		    	$idEmpleado = $infoPart->idEmpleado;

		    	   $emp = User::where('idEmpleado',$idEmpleado)->first();
		    	   $nomPar=$emp->nombresUsuario." ".$emp->apellidosUsuario;

                   $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSol;
				   $soliSegui->estadoSolicitud=2;
				   $soliSegui->observaciones = 'Se a quitado el siguiente <b>participante</b> de la solicitud: '.$nomPar;
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();


			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}
		public function abrircasoParticipante(Request $request){
			$v = Validator::make($request->all(),[
	        	'txtParticipante'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'txtParticipante'=>'id del participante'

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
		    	$infoPart = Participantes::find($request->txtParticipante);
		        $infoPart->caso=0;
		        $infoPart->save();

		      	$soli =Solicitudes::find($request->idSol);
		      	$soli->idEstado=3;
		      	$soli->save();

			       $iddd = $infoPart->idEmpleado;
                   $emppCorreo = User::where('idEmpleado','=',$iddd)->first();
                   $emailem = $emppCorreo->correo;
                   $data['soli'] = Solicitudes::find($request->idSol);
                    if(!empty($emailem)){
				   				 Mail::send('emails.abrirCaso',$data,function($msj) use ($data,$emailem){
					               $msj->subject('Reapertura de caso - Solicitud de correspondencia');
								   $msj->to($emailem);
					                  });
					}
				SolicitudesController::correoAsistenteJefe($iddd,$request->idSol,'emails.abrirCaso','Reapertura de caso - Solicitud de correspondencia');

		    	  /* $emp = User::where('idEmpleado',$idEmpleado)->first();
		    	   $nomPar=$emp->nombresUsuario." ".$emp->apellidosUsuario;

                   $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSol;
				   $soliSegui->estadoSolicitud=2;
				   $soliSegui->observaciones = 'Se abrio  <b>participante</b> de la solicitud: '.$nomPar;
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();	*/

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}
	public function storeDetalleSolicitud(Request $request){


			$v = Validator::make($request->all(),[
	        	'idClasificacion'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'idClasificacion'=>'clasificacion',
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


		if($request->idClasificacion==1){
				$ant =Solicitudes::find($request->idSoliDetalle);
				$antIdClasificacion = $ant->idClasificacion;
				$antFechaResp = $ant->idfechaRespuesta;
				$antComentario = $ant->comentario;
				$antDias = $ant->dias;


		    	$soliEdit = Solicitudes::find($request->idSoliDetalle);
		    	$soliEdit->idClasificacion = $request->idClasificacion;

		    	if($request->idFechaResp!=''){
		    		if($request->idFechaResp==3){
		    			$soliEdit->idfechaRespuesta = $request->idFechaResp;
		    			$soliEdit->dias = $request->dias;
		            }else{
		        		 $soliEdit->idfechaRespuesta = $request->idFechaResp;
		        		 $soliEdit->dias = 2;
		         	}
		        }else{
		        	return "<ul class='text-warning'><li>Debe de seleccionar una fecha respuesta</li></ul>";
		        }

		    	$soliEdit->comentario = $request->comentario;
		        $soliEdit->usuarioDetalle=Auth::user()->idUsuario;
		        $soliEdit->fechaDetalle=Carbon::now();
		        $soliEdit->save();

		   		if(empty($request->idAccion)){
		   					return "<ul class='text-warning'><li>Debe de seleccionar una o mas acciones</li></ul>";
		   		}
               //guardamos los eliminados para solicitud seguimiento
                $eliminados = SolicitudAcciones::where('idSolicitud',$request->idSoliDetalle)
                    ->whereNotIn('idAccion',$request->idAccion)->select('idAccion')->pluck('idAccion')->toArray();


                //se borran los registros de los tipos que se hallan desmarcado
                  SolicitudAcciones::where('idSolicitud',$request->idSoliDetalle)
                    ->whereNotIn('idAccion',$request->idAccion)->delete();

		        $acc = SolicitudAcciones::where('idSolicitud',$request->idSoliDetalle)->select('idAccion')->pluck('idAccion')->toArray();

                $listAccion = $request->idAccion;
                if(count($listAccion)!=0){
		        for($i=0;$i<count($listAccion);$i++){
		        	 if(in_array($listAccion[$i], $acc, false)){
		        	    // $a[$i]=("if:".$listAccion[$i]);
		        	}else{
		        		//$a[$i]=("else:".$listAccion[$i]);
                        $sa= new SolicitudAcciones();
		        	    $sa->idSolicitud = $request->idSoliDetalle;
		        	 	$sa->idAccion = $listAccion[$i];
		        	 	$sa->usuarioCreacion=Auth::user()->idUsuario;
		                $sa->fechaCreacion=Carbon::now();
		        	 	$sa->save();
		        	}
		        }
		       }else{
		       	return "<ul class='text-warning'><li>Debe de seleccionar una o mas acciones</li></ul>";
		       }


		      //----------------------------GUARDAR PARTICIPANTES-----------------------------------

		        $listPart = $request->idParticipante;
		    	$soli2 = $request->idSoliDetalle;

		    	for($i=0;$i<count($listPart); $i++){

		       if(Participantes::where('idSolicitud',$request->idSoliDetalle)->where('idEmpleado',$listPart[$i])->where('idEstado',1)->count()==0){
		    	$part =  new Participantes();
		    	$part->idSolicitud = $request->idSoliDetalle;
                $part->idEmpleado = $listPart[$i];
                $part->usuarioCreacion = Auth::user()->idUsuario;
				$part->fechaCreacion = Carbon::now();
				$part->idEstado=1;
				$part->caso=0;
		    	$part->save();

		    	 $empCorreo = User::where('idEmpleado',$listPart[$i])->first();
		    	 $correo = $empCorreo->correo;
		    	 $data['soli'] = Solicitudes::find($request->idSoliDetalle);
		    	 if(!empty($correo)){
				                     Mail::send('emails.nuevaSolicitud',$data,function($msj) use ($data,$correo){
		                             $msj->subject('Nueva solicitud de correspondencia');
					                 $msj->to($correo);
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
			     }
			     SolicitudesController::correoAsistenteJefe($listPart[$i],$request->idSoliDetalle,'emails.nuevaSolicitud','Nueva solicitud de correspondencia');


		    	 }else{
		    	 	return "<ul class='text-warning'>¡ERROR! algunos empleados ya estan registrados<ul/>";
		    	 }
		        }

			  //-------------------------INSERT SOLICITUD SEGUIMIENTO-------------------------
		    	 $verf = Solicitudes::find($request->idSoliDetalle);
		    	 if($verf->idEstado==2){

		    	 	if($antDias!=$request->dias){
		    	 		 $a="<br><b>Días en fecha respuesta:</b>".$request->dias;
		    	    }else{
		    	    	$a='<br><b>Días en fecha respuesta:2</b>';
		    	    }
		    	   if($antFechaResp!=$request->idFechaResp){
		    	 		$t2=FechaRespuesta::find($request->idFechaResp);
		    	 		$b="<br><b>Fecha respuesta:</b>".$t2->nombreFecha;
		    	 		/*if($request->idFechaResp==3){
		    	 	    $b="<br><b>Fecha respuesta:</b>".$t2->nombreFecha." <br><b>Días:</b>".$request->dias;
		    	 	    }	*/
		    	 	}else{
		    	 		$b='';
		    	 	}

		    	 	if($antComentario!=$request->comentario){
		    	 		$c="<br><b>Comentario:</b>".$clean = mb_strtoupper($request->comentario, "utf-8");
		    	 	}else{
		    	 		$c='';
		    	 	}

		    	 	if($antIdClasificacion!=$request->idClasificacion){
		    	 		$nomzs=Clasificacion::find($request->idClasificacion);

		    	 		$ll="<br><b>Clasificación:</b>". $nomz->nombreClasificacion;
		    	 	}else{
		    	 		$ll='';
		    	 	}

		    	 	//------------------------CAMPO ACCIONES---------------------------------------
		    	 	$ban1=0;$acc1='';
		    	 	for($i=0;$i<count($eliminados);$i++){
		    	 	   //ACCIONES ELIMINADAS
		        	 	$ban1= $ban1+1;
		        	    $d=Acciones::find($eliminados[$i]);
		        	    $acc1 = $acc1." <b>".$d->nombreAccion."</b>";

		            }

		             if($ban1>0){
		             $accEliminadas = '<br>Se han quitado las siguientes <b>acciones</b> en la solicitud: '.$acc1;
		        		 }else{
		         	 $accEliminadas = '';
		         		}

		            //-----------------------------------------------------

		         if(strlen($a)>0 || strlen($b)>0 || strlen($c)>0 || strlen($accEliminadas)>0 || strlen($ll)>0){

		    	  $soliSegui = new  SolicitudSeguimiento();
				  $soliSegui->idSolicitud=$request->idSoliDetalle;
				  $soliSegui->estadoSolicitud=2;
				  $soliSegui->observaciones = 'Solicitud Editada, los campos: '.$b.''.$a.''.$c.''.$ll.''.$accEliminadas;
				  $soliSegui->fechaCreacion=Carbon::now();
				  $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				  $soliSegui->save();
				 }

                 //-----------------------NUEVOS PARTICIPANTES--------------------------
				$nomPar='';
				for($i=0;$i<count($listPart); $i++){

		    	 $emp = User::where('idEmpleado',$listPart[$i])->first();
		    	 $nomPar=$nomPar." <b>".$emp->nombresUsuario." ".$emp->apellidosUsuario."</b><br>";

		        }
		           if(strlen($nomPar)>0){
		           $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSoliDetalle;
				   $soliSegui->estadoSolicitud=2;
				   $soliSegui->observaciones = 'Se agregaron nuevos responsables en la solicitud: <br>'.$nomPar;
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();
				}


		       }//IF VERF
               //--------------------------------------------------------------------------------



			    $soli = Solicitudes::find($request->idSoliDetalle);
		        $soli->idEstado=2;

		        if($soli->fechaRespuesta==''){

						          $soli->fechaRespuesta=Carbon::now();
				                  $soli->save();

				                 $emNom='';
								for($i=0;$i<count($listPart); $i++){

						    	 $emp = User::where('idEmpleado',$listPart[$i])->first();
						    	 $emNom=$emNom." <b>".$emp->nombresUsuario." ".$emp->apellidosUsuario."</b><br>";

						        }

						         $soliSegui = new  SolicitudSeguimiento();
								 $soliSegui->idSolicitud=$request->idSoliDetalle;
								 $soliSegui->estadoSolicitud=2;
						         $soliSegui->observaciones = 'Solicitud Asignada. Participantes:<br>'.$emNom;
							     $soliSegui->fechaCreacion=Carbon::now();
								 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
								 $soliSegui->save();
		        }//if fechaRespuesta





		         if(Historial::where('idSolicitud',$request->idSoliDetalle)->where('idEstado',2)->count()==0){
			    $historia = new Historial();
				$historia->idSolicitud = $request->idSoliDetalle;
				$historia->idEstado = 2;
				$historia->usuarioCreacion = Auth::user()->idUsuario;
				$historia->fechaCreacion = Carbon::now();
				$historia->save();
			     }


		  }else{
		    	//-------------------------INSERT SOLICITUD INFORMÁTIVA------------------------
		    	 $verf = Solicitudes::find($request->idSoliDetalle);
		    	 if($verf->idClasificacion!= $request->idClasificacion){
		    	   $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSoliDetalle;
				   $soliSegui->estadoSolicitud=17;

				   $nom = Clasificacion::find($request->idClasificacion);
				   $soliSegui->observaciones = 'Solicitud Editada, en el campo <b>Clasificación:</b> '.$nom->nombreClasificacion;
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();
		    	}
                //------------------------------------------------------------------------------
		    	$soliEdit = Solicitudes::find($request->idSoliDetalle);
		    	$soliEdit->idEstado=17;
		    	$soliEdit->idClasificacion = $request->idClasificacion;
		    	$soliEdit->comentario = $request->comentario;
		    	$soliEdit->usuarioDetalle=Auth::user()->idUsuario;
		        $soliEdit->fechaDetalle=Carbon::now();
		    	$soliEdit->save();


		    	  //----------------------------GUARDAR PARTICIPANTES-----------------------------------

		        $listPart = $request->idParticipante;
		    	$soli2 = $request->idSoliDetalle;

		    	for($i=0;$i<count($listPart); $i++){

		       if(Participantes::where('idSolicitud',$request->idSoliDetalle)->where('idEmpleado',$listPart[$i])->where('idEstado',1)->count()==0){
		    	$part =  new Participantes();
		    	$part->idSolicitud = $request->idSoliDetalle;
                $part->idEmpleado = $listPart[$i];
                $part->usuarioCreacion = Auth::user()->idUsuario;
				$part->fechaCreacion = Carbon::now();
				$part->idEstado=1;
				$part->caso=0;
		    	$part->save();

		    	 $empCorreo = User::where('idEmpleado',$listPart[$i])->first();
		    	 $correo = $empCorreo->correo;
		    	 $data['soli'] = Solicitudes::find($request->idSoliDetalle);
		    	 if(!empty($correo)){
				                     Mail::send('emails.nuevaInformativa',$data,function($msj) use ($data,$correo){
		                             $msj->subject('Nueva solicitud de correspondencia informativa');
					                 $msj->to($correo);
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });
			     }
			     SolicitudesController::correoAsistenteJefe($listPart[$i],$request->idSoliDetalle,'emails.nuevaInformativa','Nueva solicitud de correspondencia informativa');
		    	 }else{
		    	 	return "<ul class='text-warning'>¡ERROR! algunos empleados ya estan registrados<ul/>";
		    	 }
		        }

		            //-----------------------NUEVOS PARTICIPANTES--------------------------
				$nomPar='';
				for($i=0;$i<count($listPart); $i++){

		    	 $emp = User::where('idEmpleado',$listPart[$i])->first();
		    	 $nomPar=$nomPar." <b>".$emp->nombresUsuario." ".$emp->apellidosUsuario."</b><br>";

		        }
		          if(strlen($nomPar)>0){
		           $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSoliDetalle;
				   $soliSegui->estadoSolicitud=11;
				   $soliSegui->observaciones = 'Se agregaron nuevos responsables en la solicitud: <br>'.$nomPar;
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();
				}


		       }//IF VERF
               //--------------------------------------------------------------------------------


			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);
		}

     public function listaReportePendiente()
 	{
		$data = ['title' 			=> 'Reporte de solicitudes pendientes'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Reporte de solicitudes pendientess', 'url' => ''],
			 		['nom'	=>	'', 'url' => '#'],
				]];
		$data['empleados'] = Participantes::getEmpleadosJefe();
		return view('solicitudes.reporte.pendientesParticipantes',$data);
	}
	 public function   getDataRowsPendieReporte(Request $request){

        	$sol = vwSolicitudes::listParticipantes($request->idEmpleado)
        		   ->whereIn('T1.idEstado',[2,3,11])
        	       ->where('T1.idTipo',1)
        	       ->distinct();
        	return Datatables::of($sol)


		 ->addColumn('detalle', function ($dt) {

                   	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">

								   <li><a  href="'.route('verSolicitud',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-eye"></i>VER DETALLE</a></li>

								  </ul>
								</div> ';




					})
             //-------------------------------------AQUI TRABAJAR FECHA
		      ->addColumn('dias', function ($dt) {
		      	 if(!empty($dt->dias)){
		      	 	return '<span class="label label-info">'.$dt->dias.' días</span>';

		      	 }else{
		      	 			      return '<span class="label label-info">2 días</span>';
		      	 }
	            })
		        ->addColumn('diasproceso', function ($dt) {
		        	        $d1= date('Y-m-d',strtotime($dt->fechaDetalle));
	                		$d2=date('Y-m-d');
						    $dias	= (strtotime($d1)-strtotime($d2))/86400;
							$dias 	= abs($dias); $dias = floor($dias);
							if(!empty($dt->dias)){
								$diaresolver=$dt->dias;
							}else{
								$diaresolver ='2';
							}
							if($dias <= $diaresolver){
								return '<span class="label label-success">'.$dias.' días</span>';
							}else{
								return '<span class="label label-danger">'.$dias.' días</span>';
							}
	            })

		 	->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".mb_strtoupper((string)$request->get('asunto'))."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T1.fechaDetalle','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				if($request->has('idTitular')){
	        			$query ->join('dnm_correspondencia_si.COR.solicitudTitulares AS T3','T1.idSolicitud ','=','T3.idSolicitud ')->where('T3.idTitular','=',(int)$request->idTitular);
	        				}

	        					if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}




	        			})


			->make(true);



	}

		public function reportarAsignacion(Request $request){

			$v = Validator::make($request->all(),[
	        	'idsol'=>'required',
	        	'comentario'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		  'idsol'=>'id de la solicitud',
	   		  'comentario'=>'comentario',

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
		    	   					 $data['solicitud'] = Solicitudes::find($request->idsol);
		    	   					 $data['usuario'] = Auth::user()->nombresUsuario.' '.Auth::user()->apellidosUsuario;
		    	   					 $data['comentario'] = $request->comentario;
		    	   	                 Mail::send('emails.reporteSolicitud',$data,function($msj) use ($data){
		                             $msj->subject('Alerta de asignación de correspondencia');
					                 $msj->to('aleida.viera@medicamentos.gob.sv');
					                 $msj->bcc('jose.pena@medicamentos.gob.sv');
		                                });
			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}

			public function justificacionProrroga(Request $request){

			$v = Validator::make($request->all(),[
	        	'idsol'=>'required',
	        	'comentario'=>'required',
	        	'dias' => 'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'idsol'=>'id de la solicitud',
	   		  'comentario'=>'comentario',
	   		  'dias' => 'días'

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
		    	   					$sol = Solicitudes::find($request->idsol);
		    	   					if(!empty($sol->dias)){
		    	   					 $sol->dias = $sol->dias + $request->dias;
		    	   					}else{
		    	   					 $sol->dias = 2 + $request->dias;
		    	   					}
		    	   					$sol->fechaModificacion = Carbon::now();
		    	   					$sol->usuarioModificacion = Auth::user()->idUsuario;
		    	   					$sol->save();

		    	   					$jus = new JustificacionProrroga();
		    	   					$jus->idSolicitud = $request->idsol;
		    	   					$jus->dias = $request->dias;
		    	   					$jus->justificacion = $request->comentario;
		    	   					$jus->usuarioCreacion = Auth::user()->idUsuario;
		    	   					$jus->fechaCreacion = Carbon::now();
		    	   					$jus->save();
			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}
		public function historialJustificacionProrroga(Request $request){

		    try {
		    $sol = JustificacionProrroga::where('idSolicitud',$request->idSolicitud);
        	return Datatables::of($sol)
        	->make(true);

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}
	public function correoAsistenteJefe($idJefe,$idSolicitud,$vista,$asunto){
		$idAsistente = PersonaParticipante::where('idEmpleado', $idJefe)->first();
		if(!empty($idAsistente->idEmpleadoAsistente)){
					$empCorreo = User::where('idEmpleado',$idAsistente->idEmpleadoAsistente)->first();
					$correo = $empCorreo->correo;
					$data['soli'] = Solicitudes::find($idSolicitud);
					 if(!empty($correo)){
							                     Mail::send($vista,$data,function($msj) use ($data,$correo,$asunto){
					                             $msj->subject($asunto);
								                 $msj->to($correo);
								                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
					                                });
					}
	   }
	}



}
