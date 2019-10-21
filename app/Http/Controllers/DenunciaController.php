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
use App\Models\Catalogo\MediosRecepcion;
use App\Models\Catalogo\Establecimientos;
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
use App\Models\Solicitud\Municipios;
use App\Models\Solicitud\Departamento;
use App\Models\Solicitud\SolicitudDenuncia;
use App\Models\Solicitud\DenunciaCiudadana;
use App\Models\Solicitud\DenunciaRegistro;
use App\Models\Solicitud\DenunciaDetalle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use URL;


class DenunciaController extends Controller{

	  public function nuevaDenuncia()
	{
		$data = ['title' 			=> 'Nueva  denuncia'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Nueva denuncia', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]]; 
		return view('denuncia.nueva',$data);
	}


	public function storeDenuncia(Request $request){
			$v = Validator::make($request->all(),[			
	        	'asunto'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'asunto' => 'asunto',

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
		    DB::connection('sqlsrv')->beginTransaction();
		
			try {
				$a = date("Y"); $m=date("m");$d=date("d");
				$lastSoli= Solicitudes::all();
                $dad=$lastSoli->last();
                
                $newId=($dad->idSolicitud)+1;
                $fid =$a.$m.$d.$newId;

		    	$soli = new Solicitudes();	
                $soli->idSolicitud= $newId;
				$soli->fechaRecepcion = Carbon::now();
				$soli->asunto = $request->asunto;
				$soli->idEstado = 1;
				$soli->usuarioCreacion = Auth::user()->idUsuario;
				$soli->fechaCreacion = Carbon::now();
				$soli->idTipo=2;
				$soli->noPresentacion =  $fid;
				$soli->save();

				DB::connection('sqlsrv')->commit();	
				     return response()->json(['state' => 'success','msj'=>'¡Solicitud de denuncia ingresada con exito!',
				'idSolicitud' => Crypt::encrypt($newId)]);

		        } catch (\Exception $e) {
						DB::connection('sqlsrv')->rollback();
						Debugbar::addException($e);
						throw $e;
		    			return $e->getMessage();
			
        	    }

       
		}

    public function verDetalleDenuncia(){
		$data = ['title' 			=> 'DENUNCIA TELÉFONICA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'DETALLE DE DENUNCIA', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]]; 
	    $data['listMunicipios'] = Municipios::where('idDepartamento',1)->get();
	    $data['listDepartamento']=Departamento::where('idPais',222)->get();
	    $data['medios'] = MediosRecepcion::all();
		return view('denuncia.detalle',$data);
	}
	  public function nuevaCiudadana(){
		$data = ['title' 			=> 'DENUNCIA CIUDADANA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'DETALLE DE DENUNCIA', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]]; 
		 $data['medios'] = MediosRecepcion::all();
		return view('denuncia.nuevaCiudadana',$data);
	}


		public function storeDetalleDenuncia(Request $request){
			$v = Validator::make($request->all(),[			
	        	'asunto'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'asunto' => 'asunto',

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
		
		    DB::connection('sqlsrv')->beginTransaction();
			
			try {
                $a = date("Y"); $m=date("m");$d=date("d");
				$lastSoli= Solicitudes::all();
                $dad=$lastSoli->last();
                
                $newId=($dad->idSolicitud)+1;
                $fid =$a.$m.$d.$newId;

                //$nn=Solicitudes::whereIn('idTipo',[2,3])->count();
                $nn=Solicitudes::whereIn('idTipo',[2,3])->whereYear('fechaCreacion','=',$a)->count();
               
                if($nn==0){
                  $nn=1;
                }else{
                  $nn=$nn+1;
                }
              

		    	$soli = new Solicitudes();	
                $soli->idSolicitud= $newId;
				$soli->fechaRecepcion = Carbon::now();
				$soli->asunto = $request->asunto;
				$soli->descripcion = $request->descripcion;
				$soli->idEstado = 1;
				$soli->observaciones = $request->observacion;
				if($request->medio!=''){
				$soli->idMedio=$request->medio;
			    }
				$soli->usuarioCreacion = Auth::user()->idUsuario;
				$soli->fechaCreacion = Carbon::now();
				if($request->tipoForm==1){
					//SOLICITUD DE DENUNCIA TELEFONICA
				 $soli->idTipo=2;
				}else{
					//SOLICITUD DE DENUNCIA CIUDADANA
				 $soli->idTipo=3;
				}
				$soli->noPresentacion = $nn.'-'.$a;
				$soli->save();
             

             //----INGRESAMOS LA SOLICITUD DE DENUNCIA--------
			    $soliDenuncia = new SolicitudDenuncia();
			    $soliDenuncia->idDenuncia = $newId;
			    $soliDenuncia->fechaEvento = $request->fechaEvento;
			    if($request->usuario!=''){
			    		$soliDenuncia->nombreUsuario = $request->usuario;
			    		}else{
			    		$soliDenuncia->nombreUsuario = 'ANÓNIMO';
			    		}

			    if($request->tipoForm==1){
			    	   $soliDenuncia->telLlamada = $request->telLlamada;
			    	   $soliDenuncia->idDepartamento = $request->departamento;
			   		   $soliDenuncia->idMunicipio= $request->municipio;
			   		   
			    }else{
			    
			         $soliDenuncia->edad= $request->edad;
			         $soliDenuncia->profesion=$request->profesion;
			         $soliDenuncia->tipoDoc=$request->tipo;
			         $tipo = $request->tipo;
                     if($tipo=='DUI'){
                     $numDocumento= $request->numDocumentoP;
                      }else{
                      $numDocumento= $request->numDocumento2;
                      }
                      $soliDenuncia->noDocumento= $numDocumento;
				      $soliDenuncia->ofrecePrueba =$request->prueba;
				      $soliDenuncia->pide =$request->pide;
			    }
			    if( $request->tel1!=''){
			    $soliDenuncia->tel1Notificar = $request->tel1;
			    }
			    if( $request->tel2!=''){
			    $soliDenuncia->tel2Notificar = $request->tel2;
				}
			    $soliDenuncia->correo = $request->correo;
			    $soliDenuncia->operador= Auth::user()->idUsuario;
			    $soliDenuncia->usuarioCreacion = Auth::user()->idUsuario;
				$soliDenuncia->fechaCreacion = Carbon::now();
				$soliDenuncia->save();

				//---------INGRESAMOS A LOS ASESESORES JURIDICOS---------------------
				/*$asesoresJuridicos =UserOptions::getAsesorJuridico();
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
		         }*/
		         //---------INGRESAMOS A LOS ASESESORES DENUNCIA---------------------
				$asesoresDenuncia=UserOptions::getAsesorDenuncia();
				  if(count($asesoresDenuncia)>0){
				      for($b=0;$b<count($asesoresDenuncia);$b++){
							   	$part =  new Participantes();
						    	$part->idSolicitud = $newId;
				                $part->idEmpleado = $asesoresDenuncia[$b];
				                $part->usuarioCreacion = Auth::user()->idUsuario;
								$part->fechaCreacion = Carbon::now();
								$part->idEstado=4;
								$part->caso=0;
						    	$part->save();
		             }
		         }
				
			  //---------ENVIAMOS CORREO al perfil de Archivo Oficial de Información ---------------------
		           $archivoCorreos = UserOptions::getcorreosArchivo();
				   $data['soli'] = Solicitudes::find($newId);

		           foreach($archivoCorreos as $a){
		           	if(!empty($a)){
		           	                 Mail::send('emails.seips',$data,function($msj) use ($a){
		                             $msj->subject('Nueva solicitud de denuncia');
					                 $msj->to($a);
					                  $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                               });
			              }
					 }
					

		         //-------INGRESAMOS LOS PRODUCTOS Y ESTABLECIMIENTOS DE LA SOLICITUD------
		         //--ESTABLECIMIENTOS

		         $estanoRegistrado=$request->estaNo;

		         if(count($estanoRegistrado)>0){

		         	for($a=0;$a<count($estanoRegistrado);$a++){
		         	   $conEsta = Establecimientos::select('nombreComercial','direccion','estado')->where('idEstablecimiento',$estanoRegistrado[$a])->first();
		         	    $nomr = $conEsta->nombreComercial;
		         	    $estr = $conEsta->estado; $conEsta->direccion;
		         	    $direr =$conEsta->direccion;
		         if(DenunciaRegistro::where('noRegistro','=',$estanoRegistrado[$a])->count()==0){
		         	  
		         		$reg1= new DenunciaRegistro();
		         		$reg1->nombreComercial=$nomr;
		         		$reg1->noRegistro=$estanoRegistrado[$a];
		         		$reg1->tipoRegistro=1;
		         		$reg1->direccion=$direr;
		         		$reg1->estado=$estr;
		         		$reg1->usuarioCreacion = Auth::user()->idUsuario;
						$reg1->fechaCreacion = Carbon::now();
		         		$reg1->save();

		         		$detalle=new DenunciaDetalle();
		         		$detalle->idDenuncia=$newId;
		         		$detalle->idRegistro=$reg1->idRegistro;
		         		$detalle->save();

		          }else{
		          	
		         	  	  $consul1= DenunciaRegistro::where('noRegistro','=',$estanoRegistrado[$a])->first();
		         	      $detalle=new DenunciaDetalle();
		         		  $detalle->idDenuncia=$newId;
		         		  $detalle->idRegistro=$consul1->idRegistro;
		         		  $detalle->save();
		          }


		         	}
		         }
		         $estadosiRegistrado=$request->estaSi;
		         if(count($estadosiRegistrado)>0){
		         for($i=0;$i<count($estadosiRegistrado);$i++){
		         	    $detalle=new DenunciaDetalle();
		         		$detalle->idDenuncia=$newId;
		         		$detalle->idRegistro=$estadosiRegistrado[$i];
		         		$detalle->save();
		         }
		         }
		         //PRODUCTOS
		         $producto=$request->proNo;
		         if(count($producto)>0){
		         	for($b=0;$b<count($producto);$b++){
		         	    $nomp = 'nombre'.$producto[$b];
		         	    $estp = 'estadopro'.$producto[$b];
		         	    $prop = 'propietario'.$producto[$b];
		         	    $fecha1 ='fecha'.$producto[$b];
		         	    $lote = 'lote'.$producto[$b];

		         		$reg1= new DenunciaRegistro();
		         		$reg1->nombreComercial=$request->$nomp;
		         		$reg1->noRegistro=$producto[$b];
		         		$reg1->tipoRegistro=2;
		         		$reg1->titular=$request->$prop;
		         		$reg1->fechaVencimiento=$request->$fecha1;
		         		$reg1->noLote=$request->$lote;
		         		$reg1->estado=$request->$estp;
		         		$reg1->usuarioCreacion = Auth::user()->idUsuario;
						$reg1->fechaCreacion = Carbon::now();
		         		$reg1->save();

		         		$detalle=new DenunciaDetalle();
		         		$detalle->idDenuncia=$newId;
		         		$detalle->idRegistro=$reg1->idRegistro;
		         		$detalle->save();

		         	}
		         }
		         $prosiRegistrado=$request->proSi;
		         if(count($prosiRegistrado)>0){
		         for($i=0;$i<count($prosiRegistrado);$i++){
		         	    $detalle=new DenunciaDetalle();
		         		$detalle->idDenuncia=$newId;
		         		$detalle->idRegistro=$prosiRegistrado[$i];
		         		$detalle->save();
		         }
		         }


		         //VARIABLE DE SESION PARA PDF
		         Session::put('idDenuncia',$newId);


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
				  //--------------------------FIN DE SUBIR ARCHIVOS-------------------------------------------



				DB::connection('sqlsrv')->commit();	
				if($request->tipoForm==1){
		        return response()->json(['state' => 'success','msj'=>'¡Denuncia teléfonica ingresada con exito!']);
		        }else{
		        return response()->json(['state' => 'success','msj'=>'¡Denuncia ciudadana ingresada con exito!']);	
		        }


		        } catch (\Exception $e) {
						DB::connection('sqlsrv')->rollback();
						Debugbar::addException($e);
						throw $e;
		    			return $e->getMessage();
			
        	    }

       
		}
		public function storeCiudadana(Request $request){
			$v = Validator::make($request->all(),[			
	        	'asunto'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'asunto' => 'asunto',

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
		
		    DB::connection('sqlsrv')->beginTransaction();
		    $newId='';
			try {
                $a = date("Y"); $m=date("m");$d=date("d");
				$lastSoli= Solicitudes::all();
                $dad=$lastSoli->last();
                
                $newId=($dad->idSolicitud)+1;
                $fid =$a.$m.$d.$newId;

		    	$soli = new Solicitudes();	
                $soli->idSolicitud= $newId;
				$soli->fechaRecepcion = Carbon::now();
				$soli->asunto = $request->asunto;
				$soli->descripcion = $request->descripcion;
				$soli->idEstado = 1;
				$soli->observaciones = $request->observacion;
				if($request->medio!=''){
				$soli->idMedio=$request->medio;
			    }
				$soli->usuarioCreacion = Auth::user()->idUsuario;
				$soli->fechaCreacion = Carbon::now();
				$soli->idTipo=3;
				$soli->noPresentacion =  $fid;
				$soli->save();

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
				  //--------------------------FIN DE SUBIR ARCHIVOS-------------------------------------------

			    $soliCiudadana = new DenunciaCiudadana();
			    $soliCiudadana->idDenuncia = $newId;
			    $soliCiudadana->fechaEvento = $request->fechaEvento;
			    if($request->usuario!=''){
			    $soliCiudadana->nombreUsuario = $request->usuario;
			    }else{
			    $soliCiudadana->nombreUsuario = 'ANÓNIMO';
			    }
			    $soliCiudadana->edad= $request->edad;
			    $soliCiudadana->profesion=$request->profesion;
			    $soliCiudadana->tipoDoc=$request->tipo;
			     $tipo = $request->tipo;
                 if($tipo=='DUI'){
                  $numDocumento= $request->numDocumentoP;
                 }else{
                  $numDocumento= $request->numDocumento2;
                 }
                $soliCiudadana->noDocumento= $numDocumento;
                $soliCiudadana->nombreEstablecimiento= mb_strtoupper($request->nomEstablecimiento, 'UTF-8');
                $soliCiudadana->ubicacion= $request->ubicacion;
                $soliCiudadana->nombreProducto =mb_strtoupper($request->nomProducto, 'UTF-8');
                $soliCiudadana->fechaVencimiento = $request->fechaVen;
                $soliCiudadana->noRegistro=$request->noRegistro;
			    $soliCiudadana->noLote = $request->noLote;
			    $soliCiudadana->nombreFabricante= mb_strtoupper($request->nomFabricante, 'UTF-8');
			    $soliCiudadana->propietario= mb_strtoupper($request->propietario, 'UTF-8');
			    $soliCiudadana->edadPro= $request->edadPro;
			    $soliCiudadana->profesionPro=$request->profesionPro;
				$soliCiudadana->notificado =$request->notificado;
				$soliCiudadana->ofrecePrueba =$request->prueba;
				$soliCiudadana->pide =$request->pide;
				$soliCiudadana->tel1Notificar = $request->tel1;
			    $soliCiudadana->tel2Notificar = $request->tel2;
			    $soliCiudadana->correoUsuario = $request->correo;
			    $soliCiudadana->usuarioCreacion = Auth::user()->idUsuario;
				$soliCiudadana->fechaCreacion = Carbon::now();
				$soliCiudadana->save();

				$asesoresJuridicos =UserOptions::getAsesorJuridico();
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

				DB::connection('sqlsrv')->commit();	
				Session::put('idDenuncia',$newId);
		   return response()->json(['state' => 'success','msj'=>'¡Denuncia ciudadana ingresada con exito!']);


		        } catch (\Exception $e) {
						DB::connection('sqlsrv')->rollback();
						Debugbar::addException($e);
						throw $e;
		    			return $e->getMessage();
			
        	    }

       
		}
    
    public function rowsListaArchivadas(Request $request){
    	 $sol = vwSolicitudes::listDenuncia()->whereIn('T1.idEstado',[12,14,16]); 
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
	                }else if($dt->idEstado==14){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})
		 ->addColumn('fechaEvento', function ($dt) {
		 	        if($dt->fechaEvento=='1900-01-01' || $dt->fechaEvento==''){
	                return '<span class="label label-warning">NO HAY FECHA INGRESADA</span>';
	                }else{
	                 return '<span class="label label-primary">'.$dt->fechaEvento.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt){
                 

	               	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }




					})

			->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".(string)$request->get('asunto')."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T3.fechaEvento','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				
	        				if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}

	        					
	        					  	

	        			})
					
			->make(true);
    }

    public function rowsListaAsignadas(Request $request){
    	$sol = vwSolicitudes::listDenunciaParticipante(Auth::user()->idEmpleado); 
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
	                }else if($dt->idEstado==14){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})
		 	 ->addColumn('fechaEvento', function ($dt) {
		 	        if($dt->fechaEvento=='1900-01-01' || $dt->fechaEvento==''){
	                return '<span class="label label-warning">NO HAY FECHA INGRESADA</span>';
	                }else{
	                 return '<span class="label label-primary">'.$dt->fechaEvento.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) {
                
	               	      	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }

	               


					})

			->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".(string)$request->get('asunto')."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T3.fechaEvento','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				
	        				if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}
	        					
	        					  	

	        			})
					
			->make(true);	
    }
	public function rowslistaDenuncia(Request $request){
		    $permisos = UserOptions::getAutUserOptions();

		if(in_array(487, $permisos, true)){
			 $sol = vwSolicitudes::listDenuncia()->where('T1.idEstado',16); 
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
	                }else if($dt->idEstado==14){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})
		 ->addColumn('fechaEvento', function ($dt) {
		 	        if($dt->fechaEvento=='1900-01-01' || $dt->fechaEvento==''){
	                return '<span class="label label-warning">NO HAY FECHA INGRESADA</span>';
	                }else{
	                 return '<span class="label label-primary">'.$dt->fechaEvento.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) use($permisos){
                 

	               	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }




					})

			->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".(string)$request->get('asunto')."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T3.fechaEvento','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				
	        				if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}

	        					
	        					  	

	        			})
					
			->make(true);
		}else{
			$sol = vwSolicitudes::listDenuncia(); 
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
	                }else if($dt->idEstado==14){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})
		 ->addColumn('fechaEvento', function ($dt) {
		 	        if($dt->fechaEvento=='1900-01-01' || $dt->fechaEvento==''){
	                return '<span class="label label-warning">NO HAY FECHA INGRESADA</span>';
	                }else{
	                 return '<span class="label label-primary">'.$dt->fechaEvento.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) use($permisos){
                  if($dt->idEstado==1){
                   	 if(in_array(478, $permisos, true) || in_array(486, $permisos, true)){
                    	  	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }
					  }else if(in_array(483, $permisos, true)){

					  	if($dt->idTipo==2){
					  	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								  	
						
									<li><a  href="'.route('editarDenuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'"  ><i class="fa fa-edit"></i>EDITAR</a></li>

								  </ul>
								</div> '; 
				         }else{
						  	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								  	
						
									<li><a  href="'.route('editarCiudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'"  ><i class="fa fa-edit"></i>EDITAR</a></li>

								  </ul>
								</div> '; 

						  }
					  	}

					
	
	               }else{

	               	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }

	               }


					})

			->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".(string)$request->get('asunto')."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T3.fechaEvento','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				
	        				if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}

	        					
	        					  	

	        			})
					
			->make(true);
		}

	}
	public function rowslistaNuevasDenuncia(Request $request){
		   
		   $permisos = UserOptions::getAutUserOptions();
		   if(in_array(490, $permisos, true)){
		   	$sol = vwSolicitudes::listDenuncia()->where('T1.idEstado',1); 
		   }else{
		   	$sol = vwSolicitudes::listDenuncia()->where('T1.idEstado',3); 
		   }
		   

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
	                }else if($dt->idEstado==14){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})
		 ->addColumn('fechaEvento', function ($dt) {
		 	        if($dt->fechaEvento=='1900-01-01' || $dt->fechaEvento==''){
	                return '<span class="label label-warning">NO HAY FECHA INGRESADA</span>';
	                }else{
	                 return '<span class="label label-primary">'.$dt->fechaEvento.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) use($permisos){
                  if($dt->idEstado==1){
                   	 if(in_array(478, $permisos, true) || in_array(486, $permisos, true) || in_array(490, $permisos, true)){
                    	  	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }
					  }else if(in_array(483, $permisos, true)){

					  	if($dt->idTipo==2){
					  	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								  	
						
									<li><a  href="'.route('editarDenuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'"  ><i class="fa fa-edit"></i>EDITAR</a></li>

								  </ul>
								</div> '; 
				         }else{
						  	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								  	
						
									<li><a  href="'.route('editarCiudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'"  ><i class="fa fa-edit"></i>EDITAR</a></li>

								  </ul>
								</div> '; 

						  }
					  	}

					
	
	               }else{

	               	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }

	               }


					})

			->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".(string)$request->get('asunto')."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T3.fechaEvento','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				
	        				if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}
	        					
	        					  	

	        			})
					
			->make(true);	
		

	}
		public function rowslistaRevisionDenuncia(Request $request){
		    $permisos = UserOptions::getAutUserOptions();
		   $sol = vwSolicitudes::listDenuncia()->where('T1.idEstado',15); 
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
	                }else if($dt->idEstado==14){
	                	return '<span class="label label-danger">'.$dt->nombreEstado.'</span>';
	                }else{
	                return '<span class="label label-primary">'.$dt->nombreEstado.'</span>';
	                }
					})
		 ->addColumn('fechaEvento', function ($dt) {
		 	        if($dt->fechaEvento=='1900-01-01' || $dt->fechaEvento==''){
	                return '<span class="label label-warning">NO HAY FECHA INGRESADA</span>';
	                }else{
	                 return '<span class="label label-primary">'.$dt->fechaEvento.'</span>';
	                }
					})

		 ->addColumn('detalle', function ($dt) use($permisos){
                  if($dt->idEstado==1){
                   	 if(in_array(478, $permisos, true) || in_array(486, $permisos, true)){
                    	  	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }
					  }else if(in_array(483, $permisos, true)){

					  	if($dt->idTipo==2){
					  	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								  	
						
									<li><a  href="'.route('editarDenuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'"  ><i class="fa fa-edit"></i>EDITAR</a></li>

								  </ul>
								</div> '; 
				         }else{
						  	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								  	
						
									<li><a  href="'.route('editarCiudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'"  ><i class="fa fa-edit"></i>EDITAR</a></li>

								  </ul>
								</div> '; 

						  }
					  	}

					
	
	               }else{

	               	if($dt->idTipo==2){
	               	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 
					 }else{
					 	 return	'<div class="btn-group">
								 <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-cog"></i><span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu success" role="menu">
								
								  <li><a  href="'.route('ver.denuncia.ciudadana',['idSolicitud'=>Crypt::encrypt($dt->idSolicitud)]).'" ><i class="fa  fa-edit"></i>VER DETALLE</a></li>
						

								  </ul>
								</div> '; 

					  }

	               }


					})

			->filter(function($query) use ($request){
	        				if($request->has('asunto')){
	        					$query->where('T1.asunto','like',"%".(string)$request->get('asunto')."%");
	        				}
	        				if($request->has('idEstado')){
	        					$query->where('T1.idEstado','=',(int)$request->get('idEstado'));
	        				}
	        				if($request->has('fechaRecepcion')){
	        					$query->where('T3.fechaEvento','like',"%". date('Y-m-d',strtotime($request->fechaRecepcion))."%");
	        				}
	        				
	        				if($request->has('noPresentacion')){
	        			     $aa = $request->noPresentacion;
	        			     $query->where('T1.noPresentacion','like',"%".(string)$aa."%");
	        				}
	        					
	        					  	

	        			})
					
			->make(true);	
		

	}
 public function listaDenuncia()
	{
		$data = ['title' 			=> 'Denuncias'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]];  
		return view('denuncia.listaDenuncia',$data);
	}
	 public function listaAsignadas()
	{
		$data = ['title' 			=> 'Denuncias'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]];  
		return view('denuncia.listaAsignadas',$data);
	}
	 public function listaArchivadas()
	{
		$data = ['title' 			=> 'Denuncias'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]];  
		return view('denuncia.listaArchivadas',$data);
	}
	 public function listanuevasDenuncia()
	{
		$data = ['title' 			=> 'Denuncias nuevas'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]];  
		return view('denuncia.listaNuevas',$data);
	}
	 public function listarevisionDenuncia()
	{
		$data = ['title' 			=> 'Denuncias en revisión'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]];  
		return view('denuncia.listaRevision',$data);
	}

public function editarDenuncia($idSolicitud){
   	    $id = Crypt::decrypt($idSolicitud);
   	   
		$data = ['title' 			=> 'EDITAR DENUNCIA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' => route('lista.solicitud.denuncia')],
			 		['nom'	=>	'Editar denuncia', 'url' => '#'],
				]]; 
         $soli = Solicitudes::find($id);
         $detalle = SolicitudDenuncia::find($id);

         $data['info'] =$soli;
         $data['detalle'] =$detalle;
         $data['archivos'] = Adjunto::where('idSolicitud','=',$id)->get();
         $data['listMunicipios'] = Municipios::where('idDepartamento',$detalle->idDepartamento)->get();
	     $data['listDepartamento']=Departamento::where('idPais',222)->get();
	     $data['establecimientos']=DenunciaRegistro::listRegistroEstablecimientos($id);
         $data['productos']=DenunciaRegistro::listRegistroProductos($id);
         $data['medios']=MediosRecepcion::all();
 		return view('denuncia.editar',$data);
	}
	public function editarDenunciaCiudadana($idSolicitud){
   	    $id = Crypt::decrypt($idSolicitud);
   	   
		$data = ['title' 			=> 'EDITAR DENUNCIA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' => route('lista.solicitud.denuncia')],
			 		['nom'	=>	'Editar denuncia', 'url' => '#'],
				]]; 
         $soli = Solicitudes::find($id);
         $detalle =  SolicitudDenuncia::find($id);

         $data['info'] =$soli;
         $data['detalle'] =$detalle;
         $data['archivos'] = Adjunto::where('idSolicitud','=',$id)->get();
         $data['establecimientos']=DenunciaRegistro::listRegistroEstablecimientos($id);
         $data['productos']=DenunciaRegistro::listRegistroProductos($id);
         $data['medios']=MediosRecepcion::all();
 		return view('denuncia.editarCiudadana',$data);
	}
	public function storeEditarDenuncia(Request $request){
		//dd($request->all());
		$v = Validator::make($request->all(),[			
	        	'asunto'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'asunto' => 'asunto',

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
		    DB::connection('sqlsrv')->beginTransaction();
		
			try {

		    	$soli = Solicitudes::find($request->idSolicitud);	
				$soli->descripcion = $request->descripcion;
				$soli->asunto = $request->asunto;
				$soli->observaciones = $request->observacion;
				if($request->medio!=''){
				$soli->idMedio=$request->medio;
			    }
				$soli->save();
				$newId = $request->idSolicitud;

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
				  //--------------------------FIN DE SUBIR ARCHIVOS-------------------------------------------

		
			    $soliDenuncia =SolicitudDenuncia::find($request->idSolicitud);
			    $soliDenuncia->fechaEvento = $request->fechaEvento;
			    if($request->usuario!=''){
			    		$soliDenuncia->nombreUsuario = $request->usuario;
			    		}else{
			    		$soliDenuncia->nombreUsuario = 'ANÓNIMO';
			    		}

			    if($request->tipoForm==1){
			    	   $soliDenuncia->telLlamada = $request->telLlamada;
			    	   $soliDenuncia->idDepartamento = $request->departamento;
			   		   $soliDenuncia->idMunicipio= $request->municipio;
			   		   
			    }else{
			    
			         $soliDenuncia->edad= $request->edad;
			         $soliDenuncia->profesion=$request->profesion;
			         $soliDenuncia->tipoDoc=$request->tipo;
			         $tipo = $request->tipo;
                     if($tipo=='DUI'){
                     $numDocumento= $request->numDocumentoP;
                      }else{
                      $numDocumento= $request->numDocumento2;
                      }
                      $soliDenuncia->noDocumento= $numDocumento;
				      $soliDenuncia->ofrecePrueba =$request->prueba;
				      $soliDenuncia->pide =$request->pide;
			    }

			    $soliDenuncia->tel1Notificar = $request->tel1;
			    $soliDenuncia->tel2Notificar = $request->tel2;
			    $soliDenuncia->correo = $request->correo;
			    $soliDenuncia->operador= Auth::user()->idUsuario;
			    $soliDenuncia->usuarioCreacion = Auth::user()->idUsuario;
				$soliDenuncia->fechaCreacion = Carbon::now();
				$soliDenuncia->save();

 				//-------INGRESAMOS LOS PRODUCTOS Y ESTABLECIMIENTOS DE LA SOLICITUD------
		         //--ESTABLECIMIENTOS
				 $msg1= "<ul class='text-warning'>";
				 $ban=0;
		         $estanoRegistrado=$request->estaNo;
		         if(count($estanoRegistrado)>0){
		         	for($a=0;$a<count($estanoRegistrado);$a++){
		         	   $conEsta = Establecimientos::select('nombreComercial','direccion','estado')->where('idEstablecimiento',$estanoRegistrado[$a])->first();
		         	    $nomr = $conEsta->nombreComercial;
		         	    $estr = $conEsta->estado;
		         	    $direr =$conEsta->direccion;
		         	  if(DenunciaRegistro::where('noRegistro','=',$estanoRegistrado[$a])->count()==0){
		         		$reg1= new DenunciaRegistro();
		         		$reg1->nombreComercial=$nomr;
		         		$reg1->noRegistro=$estanoRegistrado[$a];
		         		$reg1->tipoRegistro=1;
		         		$reg1->direccion=$direr;
		         		$reg1->estado=$estr;
		         		$reg1->usuarioCreacion = Auth::user()->idUsuario;
						$reg1->fechaCreacion = Carbon::now();
		         		$reg1->save();
		         		$detalle=new DenunciaDetalle();
		         		$detalle->idDenuncia=$request->idSolicitud;
		         		$detalle->idRegistro=$reg1->idRegistro;
		         		$detalle->save();
		         	  }else{
		         	  	$consul1= DenunciaRegistro::where('noRegistro','=',$estanoRegistrado[$a])->first();
		         	  	if(DenunciaDetalle::where('idDenuncia','=',$request->idSolicitud)->where('idRegistro','=',$consul1->idRegistro)->count()==0){
		         	      $detalle=new DenunciaDetalle();
		         		  $detalle->idDenuncia=$request->idSolicitud;
		         		  $detalle->idRegistro=$consul1->idRegistro;
		         		  $detalle->save();

		         	    }else{
		         	    		$msg1 .= "<li>Establecimiento: ".$consul1->nombreComercial." no registrado</li>";
		         	  	        $ban=$ban+1;
		         	    }
		         	  
		         	  }
		         	}
		         }
		         $estadosiRegistrado=$request->estaSi;
		         if(count($estadosiRegistrado)>0){
		         for($i=0;$i<count($estadosiRegistrado);$i++){
		         	    if(DenunciaDetalle::where('idDenuncia','=',$request->idSolicitud)->where('idRegistro','=',$estadosiRegistrado[$i])->count()==0){
		         	    $detalle=new DenunciaDetalle();
		         		$detalle->idDenuncia=$request->idSolicitud;
		         		$detalle->idRegistro=$estadosiRegistrado[$i];
		         		$detalle->save();
		         	   }else{
		         	   	$consul = DenunciaRegistro::find($estadosiRegistrado[$i]);
		         	   	$msg1 .= "<li>Establecimiento: ".$consul->nombreComercial." no registrado</li>";
		         	  	$ban=$ban+1;
		         	  }
		         }
		         }
		         
		         //PRODUCTOS
		         $producto=$request->proNo;
		         if(count($producto)>0){
		         	for($b=0;$b<count($producto);$b++){
		         	    $nomp = 'nombre'.$producto[$b];
		         	    $estp = 'estadopro'.$producto[$b];
		         	    $prop = 'propietario'.$producto[$b];
		         	    $fecha1 ='fecha'.$producto[$b];
		         	    $lote = 'lote'.$producto[$b];

		         	  //if(DenunciaRegistro::where('noRegistro','=',$producto[$b])->count()==0){
		         		$reg1= new DenunciaRegistro();
		         		$reg1->nombreComercial=$request->$nomp;
		         		$reg1->noRegistro=$producto[$b];
		         		$reg1->tipoRegistro=2;
		         		$reg1->titular=$request->$prop;
		         		$reg1->fechaVencimiento=$request->$fecha1;
		         		$reg1->noLote=$request->$lote;
		         		$reg1->estado=$request->$estp;
		         		$reg1->usuarioCreacion = Auth::user()->idUsuario;
						$reg1->fechaCreacion = Carbon::now();
		         		$reg1->save();

		         		$detalle=new DenunciaDetalle();
		         		$detalle->idDenuncia=$request->idSolicitud;
		         		$detalle->idRegistro=$reg1->idRegistro;
		         		$detalle->save();
		         	// }else{
 	                   /* $consul1= DenunciaRegistro::where('noRegistro','=',$producto[$b])->first();
		         	  	if(DenunciaDetalle::where('idDenuncia','=',$request->idSolicitud)->where('idRegistro','=',$consul1->idRegistro)->count()==0){
		         	      $detalle=new DenunciaDetalle();
		         		  $detalle->idDenuncia=$request->idSolicitud;
		         		  $detalle->idRegistro=$consul1->idRegistro;
		         		  $detalle->save();

		         	    }else{
		         	    		$msg1 .= "<li>Producto: ".$consul1->nombreComercial." no registrado</li>";
		         	  	        $ban=$ban+1;
		         	    }*/
		         	 //}

		         	}
		         }
		         $prosiRegistrado=$request->proSi;
		         if(count($prosiRegistrado)>0){
		         for($i=0;$i<count($prosiRegistrado);$i++){
		         	    if(DenunciaDetalle::where('idDenuncia','=',$request->idSolicitud)->where('idRegistro','=',$prosiRegistrado[$i])->count()==0){
		         	    $detalle=new DenunciaDetalle();
		         		$detalle->idDenuncia=$request->idSolicitud;
		         		$detalle->idRegistro=$prosiRegistrado[$i];
		         		$detalle->save();
		         	    }else{
		         	   	$consul = DenunciaRegistro::find($prosiRegistrado[$i]);
		         	   	$msg1 .= "<li>Producto: ".$consul->nombreComercial."</li> no registrado";
		         	  	$ban=$ban+1;
		         	  }
		         }
		         }
		         $msg1 .= "</ul>";
		             //VARIABLE DE SESION PARA PDF
		         Session::put('idDenuncia',$request->idSolicitud);
				DB::connection('sqlsrv')->commit();	
				if($request->tipoForm==1){
		        return response()->json(['state' => 'success','msj'=>'¡Denuncia teléfonica ingresada con exito!','no'=>$ban,'list'=>$msg1]);
		        }else{
		        return response()->json(['state' => 'success','msj'=>'¡Denuncia ciudadana ingresada con exito!','no'=>$ban,'list'=>$msg1]);	
		        }



		        } catch (\Exception $e) {
						DB::connection('sqlsrv')->rollback();
						Debugbar::addException($e);
						throw $e;
		    			return $e->getMessage();
			
        	    }
        	}
       public function storeEditarCiudadana(Request $request){
	    	$v = Validator::make($request->all(),[			
	        	'asunto'=>'required',
				    ]);

	   		$v->setAttributeNames([
	   		    'asunto' => 'asunto',

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
		    DB::connection('sqlsrv')->beginTransaction();
		
			try {
               	$soli = Solicitudes::find($request->idSolicitud);	
				$soli->descripcion = $request->descripcion;
				$soli->asunto = $request->asunto;
				$soli->observaciones = $request->observacion;
				if($request->medio!=''){
				$soli->idMedio=$request->medio;
			    }
				$soli->save();
				$newId = $request->idSolicitud;
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
				  //--------------------------FIN DE SUBIR ARCHIVOS-------------------------------------------

			    $soliCiudadana = DenunciaCiudadana::find($newId);
			    $soliCiudadana->fechaEvento = $request->fechaEvento;
			    if($request->usuario!=''){
			    $soliCiudadana->nombreUsuario = $request->usuario;
			    }else{
			    $soliCiudadana->nombreUsuario = 'ANÓNIMO';
			    }
			    $soliCiudadana->edad= $request->edad;
			    $soliCiudadana->profesion=$request->profesion;
			    $soliCiudadana->tipoDoc=$request->tipo;
			     $tipo = $request->tipo;
                 if($tipo=='DUI'){
                  $numDocumento= $request->numDocumentoP;
                 }else{
                  $numDocumento= $request->numDocumento2;
                 }
                $soliCiudadana->noDocumento= $numDocumento;
                $soliCiudadana->nombreEstablecimiento= mb_strtoupper($request->nomEstablecimiento, 'UTF-8');
                $soliCiudadana->ubicacion= $request->ubicacion;
                $soliCiudadana->nombreProducto =mb_strtoupper($request->nomProducto, 'UTF-8');
                $soliCiudadana->fechaVencimiento = $request->fechaVen;
                $soliCiudadana->noRegistro=$request->noRegistro;
			    $soliCiudadana->noLote = $request->noLote;
			    $soliCiudadana->nombreFabricante= mb_strtoupper($request->nomFabricante, 'UTF-8');
			    $soliCiudadana->propietario= mb_strtoupper($request->propietario, 'UTF-8');
			    $soliCiudadana->edadPro= $request->edadPro;
			    $soliCiudadana->profesionPro=$request->profesionPro;
				$soliCiudadana->notificado =$request->notificado;
				$soliCiudadana->ofrecePrueba =$request->prueba;
				$soliCiudadana->pide =$request->pide;
				$soliCiudadana->tel1Notificar = $request->tel1;
			    $soliCiudadana->tel2Notificar = $request->tel2;
			    $soliCiudadana->correoUsuario = $request->correo;
			    $soliCiudadana->usuarioCreacion = Auth::user()->idUsuario;
				$soliCiudadana->fechaCreacion = Carbon::now();
				$soliCiudadana->save();

				DB::connection('sqlsrv')->commit();	
		        return response()->json(['state' => 'success','msj'=>'¡Denuncia ciudadana editada con exito!']);


		        } catch (\Exception $e) {
						DB::connection('sqlsrv')->rollback();
						Debugbar::addException($e);
						throw $e;
		    			return $e->getMessage();
			
        	    }

       
		}

     public function verDenuncia($idSolicitud){
	    $id = Crypt::decrypt($idSolicitud);
   	   
		$data = ['title' 			=> 'DENUNCIA TELEFÓNICA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' => URL::previous() ],
			 		['nom'	=>	'Ver denuncia', 'url' => '#'],
				]]; 
         $soli = Solicitudes::find($id);
         $detalle = SolicitudDenuncia::find($id);

         $data['info'] =$soli;
         $data['detalle'] =$detalle;
         $data['archivos'] = Adjunto::where('idSolicitud','=',$id)->get();
         $data['listMunicipios'] = Municipios::where('idDepartamento',$detalle->idDepartamento)->get();
	     $data['listDepartamento']=Departamento::where('idPais',222)->get();
	     $data['comentarios'] =  Comentarios::listComentarios($id);
         $data['numCom'] = Comentarios::where('idSolicitud','=',$id)->count();
         $data['establecimientos']=DenunciaRegistro::listRegistroEstablecimientos($id);
         $data['productos']=DenunciaRegistro::listRegistroProductos($id);
         $data['formulario'] = 1;
         if($soli->idMedio!='' || $soli->idMedio!=0){
         $nomMedio =MediosRecepcion::where('idMedio',$soli->idMedio)->select('nombreMedio')->first();
         $data['medio']=$nomMedio->nombreMedio;
         }else{
         $data['medio']='';
         }
		return view('denuncia.verDenuncia',$data);
	}

	public function verCiudadana($idSolicitud){
	    $id = Crypt::decrypt($idSolicitud);
   	   
		$data = ['title' 			=> 'DENUNCIA CIUDADANA'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Lista de denuncias', 'url' =>  URL::previous() ],
			 		['nom'	=>	'Ver denuncia', 'url' => '#'],
				]]; 
         $soli = Solicitudes::find($id);
         $detalle = SolicitudDenuncia::find($id);

         $data['info'] =$soli;
         $data['detalle'] =$detalle;
         $data['archivos'] = Adjunto::where('idSolicitud','=',$id)->get();
	     $data['comentarios'] =  Comentarios::listComentarios($id);
         $data['numCom'] = Comentarios::where('idSolicitud','=',$id)->count();
         $data['establecimientos']=DenunciaRegistro::listRegistroEstablecimientos($id);
         $data['productos']=DenunciaRegistro::listRegistroProductos($id);
         $data['formulario'] = 2;
         if($soli->idMedio!='' || $soli->idMedio!=0){
         $nomMedio =MediosRecepcion::where('idMedio',$soli->idMedio)->select('nombreMedio')->first();
         $data['medio']=$nomMedio->nombreMedio;
         }else{
         $data['medio']='';
         }
		return view('denuncia.verDenunciaCiudadana',$data);
	}

	public function storeComentarioDenuncia(Request $request){

        /*idTipo 1.CERRAR CASO 2.SEIPS 3.NO APLICA*/
        DB::connection('sqlsrv')->beginTransaction();
		try {		 

		    if($request->idTipo==1 || $request->idTipo==2){
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

                	//------------------CERRAR CASO-----------------------------
                	if($request->idTipo==1){
                		$comen->comentario = $request->comentarioAsesor;
                		$comen->idSolicitud = $request->idSoli;
                		$comen->idParticipante = $cod;
                		$comen->tipoComentario=0;
                		$comen->usuarioCreacion = Auth::user()->idUsuario;
				    	$comen->fechaCreacion = Carbon::now();
				    	$comen->save();
   
				        $soli = Solicitudes::Find($request->idSoli);
				        $soli->idEstado=12;
				        $soli->save();

				        $historia = new Historial();
					    $historia->idSolicitud =  $request->idSoli;
						$historia->idEstado =12;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();
					   

                 		$soliSegui = new  SolicitudSeguimiento();
				 		$soliSegui->idSolicitud=$request->idSoli;
				 		$soliSegui->estadoSolicitud=12;
		         		$soliSegui->observaciones = 'Nuevo comentario:'.$request->comentarioAsesor;
			     		$soliSegui->fechaCreacion=Carbon::now();
				 		$soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 		$soliSegui->save();
		  
                  }elseif($request->idTipo==2){
                  	//--------------------------SEIPS----------------------------
                  		$comen->comentario = $request->comentarioAsesor;
                		$comen->idSolicitud = $request->idSoli;
                		$comen->idParticipante = $cod;
                		$comen->tipoComentario=0;
                		$comen->usuarioCreacion = Auth::user()->idUsuario;
				    	$comen->fechaCreacion = Carbon::now();
				    	$comen->save();
   
				        $soli = Solicitudes::Find($request->idSoli);
				        $soli->idEstado=16;
				        $soli->save();

				        $historia = new Historial();
					    $historia->idSolicitud =  $request->idSoli;
						$historia->idEstado =16;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();
					   

                 		$soliSegui = new  SolicitudSeguimiento();
				 		$soliSegui->idSolicitud=$request->idSoli;
				 		$soliSegui->estadoSolicitud=12;
		         		$soliSegui->observaciones = 'Nuevo comentario:'.$request->comentarioAsesor;
			     		$soliSegui->fechaCreacion=Carbon::now();
				 		$soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 		$soliSegui->save();

				   $empCorreo = UserOptions::getcorreosSEIPS();
				   $data['soli'] = Solicitudes::find($request->idSoli);
		           foreach($empCorreo as $a){
		           	if(!empty($a)){
		           	                 Mail::send('emails.seips',$data,function($msj) use ($a){
		                             $msj->subject('Nueva solicitud de denuncia');
					                 $msj->to($a);
					                  $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                               });
			              }
					 }


                  }else{
                     	//-----------------NO APLICA---------------------
                     	 $idEmpleado = Auth::user()->idEmpleado;
                     	 $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->first();
                    $idPar->caso=1;
                    $idPar->save();

                    $cod = $idPar->idParticipante;
                    $estado = $idPar->idEstado;

                    $soliEstado= Solicitudes::find($request->idSoli);
				    $soliEstado->idEstado=14;
				    $soliEstado->save();

		    		$comen = new Comentarios();
		    		if($request->comentarioAsesor!=''){
		    		   $comen->comentario=$request->comentarioAsesor;

		    		     $soliSegui = new  SolicitudSeguimiento();
				 		 $soliSegui->idSolicitud=$request->idSoli;
				 		 $soliSegui->estadoSolicitud=14;
		         		 $soliSegui->observaciones = 'Nuevo comentario:'. $request->comentarioAsesor;
			     		 $soliSegui->fechaCreacion=Carbon::now();
				 		 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 		 $soliSegui->save();
                    }else{
		    		
                			
                		$comen->comentario = 'Asesor Jurídico registro como NO APLICA en está solicitud';
				    	$b='Asesor Jurídico registro como NO APLICA en está solicitud';
                    			

                         $soliSegui = new  SolicitudSeguimiento();
				 		 $soliSegui->idSolicitud=$request->idSoli;
				 		 $soliSegui->estadoSolicitud=14;
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
				}

               //CERRAMOS CASO A LOS RESPONSABLE, COLABORADORES Y ASESOR
	            $casoRes = Participantes::where('idSolicitud','=',$request->idSoli)->whereIn('idEstado',[1,2,4])->pluck('idParticipante');
		                   if(!empty($casoRes)){
		                   for($j=0;$j<count($casoRes);$j++){
		                   $casoCo = Participantes::find($casoRes[$j]);
		                   $casoCo->caso=0;
		                   $casoCo->save();
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
		  //ENVIAMOS CORREO AL PERFIL DE Archivo Oficial de Información
		  $archivoCorreos = UserOptions::getcorreosArchivo();	
		  $data['soli'] = Solicitudes::find($request->idSoli);
		  foreach($archivoCorreos as $aa){
		           	if(!empty($aa)){
		           	                 Mail::send('emails.denunciaArchivada',$data,function($msj) use ($aa){
		                             $msj->subject('Nueva denuncia archivada');
					                 $msj->to($aa);
					                  $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                               });
			              }
		   }
		  $response = ['status' => 200, 'message' => '¡Se guardo con éxito la información en la solicitud!', "redirect" => ''];

		} catch (\Exception $e) {
			Debugbar::addException($e);
			$response = ['status' => 500, 'message' => 'Se produjo una excepción en el servidor', "redirect" => ''];
			DB::connection('sqlsrv')->rollback();
		}
		return response()->json($response);
     
   
	}

	public function storeColaboradorDenuncia(Request $request){

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
				    $soli->idEstado=13;
				    $soli->save();
				    if($es==13){
				    	$historia = new Historial();
					    $historia->idSolicitud =  $request->idSoli;
						$historia->idEstado = 13;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();
				    }

				
			                $com= Comentarios::find($request->idPadre);
			                $bc = $com->idParticipante;
                          
			                $partt = Participantes::find($bc);
			                $iddd = $partt->idEmpleado;


                            $emppCorreo = User::where('idEmpleado','=',$iddd)->first();
                            $emailem = $emppCorreo->correo;

                            $data['soli'] = Solicitudes::find($request->idSoli);
                            if(!empty($emailem)){
							                     Mail::send('emails.respuestaDenuncia',$data,function($msj) use ($data,$emailem){
					                             $msj->subject('Respuesta en solicitud de denuncia');
								                 $msj->to($emailem);
					                                });
						              }

			     $soliSegui = new  SolicitudSeguimiento();
				 $soliSegui->idSolicitud=$request->idSoli;
				 $soliSegui->estadoSolicitud=13;
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
			}else{
				Session::put('msnError1', 'No estas registrado como participante');
		          return redirect(redirect()->getUrlGenerator()->previous());
			}    

		  DB::connection('sqlsrv')->commit();	
		   Session::put('msnExito1', 'Se a registrado con exito el comentario');
		  return redirect(redirect()->getUrlGenerator()->previous());

		} catch (\Exception $e) {
			Debugbar::addException($e);
			DB::connection('sqlsrv')->rollback();
			Session::put('msnError1', 'Problemas en el servidor');
		  return redirect(redirect()->getUrlGenerator()->previous());
		}
		return response()->json($response);
     
   
	}

	 public function procedeDenuncia($idSolicitud,$formulario,$tipoProcede)
	{
		$data = ['title' 			=> 'Procede denuncia'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
			 		['nom'	=>	'', 'url' => '#'],
				]]; 
        $id = Crypt::decrypt($idSolicitud);
   	   
	   $data['empleados']=Participantes::getEmpleadosJefe();
	   $data['responsablesAsesores']=Participantes::getAsesores($id);
	   
	   $data['idSolicitud'] = $id;
	   $data['tipo'] = 2; //SOLO PUEDE COMENTAR
	   $data['formulario'] = $formulario; //SOLO PUEDE COMENTAR
	   $data['tipoProcede']=$tipoProcede;//1.PRIMER COLABORADOR (ASISTENCIAS Y CERRAR CASO) 2.COLABORADOR SOLO COMENTA

		return view('denuncia.procede',$data);
	}
	 public function storeAsistenciaDenuncia(Request $request){
      
   	   
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
                    $cod = $idPar->idParticipante;

                	$comen = new Comentarios();
                	$comen->comentario = $request->comentario;
                	$comen->idSolicitud = $request->idSoliAsistencia;
                	$comen->idParticipante = $cod;
                	$comen->tipoComentario=1;
                	$comen->usuarioCreacion = Auth::user()->idUsuario;
				    $comen->fechaCreacion = Carbon::now();
				    $comen->save();

				    $soli = Solicitudes::Find($request->idSoliAsistencia);
				    $soli->idEstado=13;
				    $soli->save();

				 $soliSegui = new  SolicitudSeguimiento();
				 $soliSegui->idSolicitud=$request->idSoliAsistencia;
				 $soliSegui->estadoSolicitud=13;
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
	           if($request->tipoProcede==1){
	           	for($i=0;$i<count($listPart); $i++){
		        if(Participantes::where('idSolicitud',$request->idSoliAsistencia)->where('idEmpleado',$listPart[$i])->where('idEstado',2)->count()==0){
		    	$part =  new Participantes();
		    	$part->idSolicitud = $request->idSoliAsistencia;
                $part->idEmpleado = $listPart[$i];
                $part->usuarioCreacion = Auth::user()->idUsuario;
				$part->fechaCreacion = Carbon::now();
				$part->idEstado=1;
				$part->caso=0;
				$part->permiso=0;
		    	$part->save();
		        }else{
                $part =  Participantes::where('idSolicitud',$request->idSoliAsistencia)->where('idEmpleado',$listPart[$i])->where('idEstado',2)->first();
				$part->idEstado=1;
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
		    
  								    Mail::send('emails.denuncia',$data,function($msj) use ($data,$correo){
		                             $msj->subject('Nueva asistencia en solicitud de denuncia');
					                 $msj->to($correo);
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });

			     }
			     }

			}else{
		    	for($i=0;$i<count($listPart); $i++){
		        if(Participantes::where('idSolicitud',$request->idSoliAsistencia)->where('idEmpleado',$listPart[$i])->where('idEstado',2)->count()==0){
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
                $part =  Participantes::where('idSolicitud',$request->idSoliAsistencia)->where('idEmpleado',$listPart[$i])->where('idEstado',2)->first();
				$part->idEstado=2;
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
		    
  								    Mail::send('emails.denuncia',$data,function($msj) use ($data,$correo){
		                             $msj->subject('Nueva asistencia en solicitud de denuncia');
					                 $msj->to($correo);
					                 $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                                });

			     }
			     }

			 }
			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
		    return response()->json(['state' => 'success']);

	}



	public function destroyDetalle(Request $request){

			$v = Validator::make($request->all(),[			
	        	'txtDetalle'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'txtDetalle'=>'id del archivo'
	   		  
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
		    	$info = DenunciaDetalle::find($request->txtDetalle);
		    	//$nom=$infoArchivo->nombreArchivo;
		      	$info->delete();

		      	   /*$soli = Solicitudes::find($request->txtDetalle);
                   $soliSegui = new  SolicitudSeguimiento();
				   $soliSegui->idSolicitud=$request->idSolicitud;
				   $soliSegui->estadoSolicitud=$soli->idEstado;
				   $soliSegui->observaciones = 'Denuncia Editada, se a quitado el <b>archivo:</b> '.$nom.' de la solicitud de correspondencia';
				   $soliSegui->fechaCreacion=Carbon::now();
				   $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				   $soliSegui->save();*/
		   
			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}

		public function stroreFinalizarColaborador(Request $request){

			$v = Validator::make($request->all(),[			
	        	'comentarioFinalizar'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'comentarioFinalizar'=>'comentario'
	   		  
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
		    try {	

		    //------------------CERRAR CASO COLABORADOR-----------------------------
		    	 $idEmpleado = Auth::user()->idEmpleado;
			    $verficar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->count();

			    if($verficar!=0){
                  
                  $idPar = Participantes::where('idEmpleado','=',$idEmpleado)->where('idSolicitud','=',$request->idSoli)->first();
                  $cod = $idPar->idParticipante;
                   
                   
                	$comen = new Comentarios();
    
                		$comen->comentario = $request->comentarioFinalizar;
                		$comen->idSolicitud = $request->idSoli;
                		$comen->idParticipante = $cod;
                		$comen->tipoComentario=0;
                		$comen->usuarioCreacion = Auth::user()->idUsuario;
				    	$comen->fechaCreacion = Carbon::now();
				    	$comen->save();
   
				        $soli = Solicitudes::Find($request->idSoli);
				        $soli->idEstado=15;
				        $soli->save();

				        $historia = new Historial();
					    $historia->idSolicitud =  $request->idSoli;
						$historia->idEstado =15;
						$historia->usuarioCreacion = Auth::user()->idUsuario;
						$historia->fechaCreacion = Carbon::now();
						$historia->save();
					   

                 $soliSegui = new  SolicitudSeguimiento();
				 $soliSegui->idSolicitud=$request->idSoli;
				 $soliSegui->estadoSolicitud=15;
		         $soliSegui->observaciones = 'Nuevo comentario:'.$request->comentarioFinalizar;
			     $soliSegui->fechaCreacion=Carbon::now();
				 $soliSegui->idUsuarioCreacion= Auth::user()->idUsuario;
				 $soliSegui->save();

				 	//CERRAMOS CASO A LOS COLABORADORES Y RESPONSABLES
	               $casoRes = Participantes::where('idSolicitud','=',$request->idSoli)->whereIn('idEstado',[1,2])->pluck('idParticipante');
		                   if(!empty($casoRes)){
		                   for($j=0;$j<count($casoRes);$j++){
		                   $casoCo = Participantes::find($casoRes[$j]);
		                   $casoCo->caso=0;
		                   $casoCo->save();
		                   }
		                   }

		              //ENVIAMOS CORREO A LOS ASESORES DE DENUNCIA 

				   $empCorreo = UserOptions::getcorreosAsesorDenuncia();
				   $data['soli'] = Solicitudes::find($request->idSoli);
		           foreach($empCorreo as $a){
		           	if(!empty($a)){
		           	                 Mail::send('emails.revisionAsesorDenuncia',$data,function($msj) use ($a){
		                             $msj->subject('Nueva revisión en solicitud de denuncia');
					                 $msj->to($a);
					                  $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                               });
			              }
					 }
		    	
		         }else{
				return $response = ['status' => 500, 'message' => '¡No estás registrado como participante!', "redirect" => ''];
			     }    


			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return $response = ['status' => 200, 'message' => "¡Se finalizó con exito la denuncia!"];

	}

public function enviarJuntaDirectiva(Request $request){
             
			$v = Validator::make($request->all(),[			
	        	'idd'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'idd'=>'id de la denuncia'
	   		  
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

		           $soli = Solicitudes::find($request->idd);
                   $soli->idEstado=3;
                   if($request->comentario!=''){
                  	$soli->comentario=$request->comentario;
                   }
                   $soli->usuarioEnviarJunta =  Auth::user()->nombresUsuario ." ".Auth::user()->apellidosUsuario;
				   $soli->save();

				   //ENVIAMOS CORREO A LOS ASESORES DE DENUNCIA
				   $asesorCorreos=UserOptions::getcorreosAsesorDenuncia();
				   $data['soli'] = Solicitudes::find($request->idd);
				   foreach($asesorCorreos as $aa){
		           	if(!empty($aa)){
		           	                 Mail::send('emails.seips',$data,function($msj) use ($aa){
		                             $msj->subject('Nueva solicitud de denuncia');
					                 $msj->to($aa);
					                  $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
		                               });
			              }
					 }
		   
			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}

	public function enviarComentarioActa(Request $request){
             
			$v = Validator::make($request->all(),[			
	        	'idd2'=>'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'idd2'=>'id de la denuncia'
	   		  
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

		           $soli = SolicitudDenuncia::find($request->idd2);
                   if($request->comentario!=''){
                  	$soli->comentarioPDF=$request->comentario;
                   }
				   $soli->save();
				
			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}
	

}
