<?php namespace App\Http\Controllers;

use Auth;
use Validator;
use DB;
use Session;
use Redirect;
use App\User;
use File;
use Crypt;
use Response;
use DateTime;
use Config;
use Debugbar;
use Mail;
use Exception;
use App\Http\Requests;
use App\Http\Requests\solicitudRequest;	
use App\Http\Requests\titularRequest;	
use App\Http\Requests\personaRequest;
use App\Http\Requests\comentarioRequest;	
use App\Http\Controllers\PdfController;
use App\Models\Catalogo\PersonaNatural;
use App\Models\Catalogo\TipoDocumento;
use App\Models\Catalogo\DepartamentosCat;
use App\Models\Catalogo\TipoTratamiento;
use App\Models\Catalogo\Establecimientos;
use App\Models\Catalogo\MunicipiosCat;
use App\Models\Catalogo\MediosRecepcion;
use App\Models\Solicitud\Solicitudes;
use App\Models\Solicitud\Comentarios;
use App\Models\Solicitud\Participantes;
use App\Models\Solicitud\vwSolicitudes;
use App\Models\Solicitud\Adjunto;
use App\Models\Solicitud\Titulares;
use App\Models\Solicitud\Historial;
use App\Models\Solicitud\SolicitudesTitular;
use App\Models\Solicitud\DenunciaRegistro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
class CatalogoController extends Controller{
    public function storeTitular(Request $request){

			$v = Validator::make($request->all(),[			
	        	'nombreTitular' => 'required',
		         'telefono1' => 'required|min:9'
				    ]);

	   		$v->setAttributeNames([
	   		  'nombreTitular' 			=> 'Nombre',
			   'telefono1' 			=> 'Telefono'
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
		        $telefonosContacto=[];
		        if($request->telefono1!=null){
		            $telefonosContacto[0]=$request->telefono1;
		        }
		        else{
		            $telefonosContacto[0]="";   
		        }
		        if($request->telefono2!=null){
		            $telefonosContacto[1]=$request->telefono2;    
		        }
		        else{
		            $telefonosContacto[1]="";       
		        }

            $tit = new Titulares();
            $tit->nombreTitular = mb_strtoupper($request->nombreTitular, 'UTF-8');
            $tit->telefonosContacto = json_encode($telefonosContacto);
            $tit->emailContacto = $request->emailTitular;
            $tit->usuarioCreacion = Auth::user()->idUsuario;
			$tit->fechaCreacion = Carbon::now();
			$tit->save();
			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}
  
	public function storePersonaNatural(personaRequest $request){
         DB::beginTransaction();

		try {		 

			  $telefonosContacto=[];
		        if($request->tel1!=null){
		            $telefonosContacto[0]=$request->tel1;
		        }
		        else{
		            $telefonosContacto[0]="";   
		        }
		        if($request->tel2!=null){
		            $telefonosContacto[1]=$request->tel2;    
		        }
		        else{
		            $telefonosContacto[1]="";       
		        }
            
            $per = new PersonaNatural();
            $per->nitNatural = $request->nit;
            $per->idTipoDocumento = $request->tipoDoc;
            if($request->tipoDoc==1){
            	if($request->numDoc1 != ''){
            		if(strlen($request->numDoc1)== 10){
		            	$per->numeroDocumento = $request->numDoc1;
		            }else{
		            	$response = ['status' => 422, 'message' => 'Formato de DUI incorrecto', "redirect" => ''];
		                		return response()->json($response);
		            }
                }else{
              $response = ['status' => 422, 'message' => 'Debe de ingrear el numero de DUI', "redirect" => ''];
                		return response()->json($response);
                }
            }else{
            	if($request->numDoc2 != ''){
            	$per->numeroDocumento = $request->numDoc2;
            	 }else{
              $response = ['status' => 422, 'message' => 'Debe de ingrear el numero del documento', "redirect" => ''];
                		return response()->json($response);
                }
            }
            $per->nombres = mb_strtoupper($request->nombres, 'UTF-8');
            $per->apellidos = mb_strtoupper($request->apellidos, 'UTF-8');
            $per->conocidoPor = '';
            $per->fechaNacimiento =  Carbon::now();
            $per->sexo = $request->sexo;
            $per->telefonosContacto = json_encode($telefonosContacto);
            $per->emailsContacto = $request->email;
            $per->direccion = 'San Salvador';
            $per->idMunicipio = 3;
            $per->idTipoTratamiento = $request->tratamiento;
            $per->estado = 'A';
            $per->idUsuarioCrea = Auth::user()->idUsuario;
			$per->fechaCreacion = Carbon::now();
			$per->save();

			  
	      $response = ['status' => 200, 'message' => '¡Se registro con exito la persona!', "redirect" => ''];
		  DB::commit();	

			

		} catch (\Exception $e) {
			Debugbar::addException($e);
			$data['error'] = $e;
			$response = ['status' => 422, 'message' => 'Se produjo una excepción en el servidor', "redirect" => ''];	
			if(!empty($e->errorInfo))
			{
				$code = $e->errorInfo;
				if($code[1] = 1062)
				{
					$response = ['status' => 422, 'message' => 'Los datos de la persona ya existen en la base de datos!', "redirect" => ''];		
				}
			}
			/*Mail::send('errors.generic', $data, function ($message) {			    
			    $message->to('soporte.tecnico@medicamentos.gob.sv', 'ROG3RB0T - Correspondencia - Nuevo Visitante');
			
			    $message->cc('rogelio.menjivar@medicamentos.gob.sv', 'ROG3RB0T - Correspondencia - Nuevo Visitante');
			    
			    $message->subject('Excepción en sistema de correspondencia');
			

			});*/
			
			DB::rollback();
		}
		
		return response()->json($response);

	}
	public function getTipoDocumento(Request $request){
	    $tipoDoc = TipoDocumento::all();
		 return response()->json($tipoDoc);
      }
     public function getDepartamentos(Request $request){
	    $dep = DepartamentosCat::where('idPais',222)->get();
		 return response()->json($dep);
      }
       public function getMunicipios(Request $request){
	    $mun = MunicipiosCat::whereBetween('idDepartamento', array(1, 14))->get();;
		 return response()->json($mun);
      }
      public function getComboboxMunicipiosAJAX(Request $request)
	{
		$result = "";
		foreach (MunicipiosCat::where('idDepartamento',$request->deparamento)->pluck('nombreMunicipio','idMunicipio') as $key => $value) {
			$result .= "<option value='$key'>$value</option>";
		}
		return $result;
	}
	 public function getTratamiento(Request $request){
	    $tra = TipoTratamiento::all();
		 return response()->json($tra);
      }

      public function pnBusqueda(Request $request){
		    $id = $request->param;
		    $pn = PersonaNatural::find($id);
		    return response()->json($pn);
		}
		 public function solicitudBusqueda(Request $request){
		    $id = $request->param;
		    $listTit = SolicitudesTitular::where('idSolicitud','=',$id)->pluck('idTitular');
             $lista= Titulares::whereIn('idTitular',$listTit)->get();
             return response()->json($lista);
		}
	 public function participantesBusqueda(Request $request){
		    $id = $request->param;
		    $partBusq = Participantes::getEmpleadosSolicitud($id);
		    return response()->json($partBusq);
		}

   public function   getDataRowsEstablecimientosNo(Request $request){
   	  //---SELECT DE ESTABLECIMIENTOS EN MYSQL
		$registradosNo=Establecimientos::select(DB::raw('UPPER(nombreComercial) as nombreComercial,UPPER(direccion) as direccion,idEstablecimiento,estado'))
		 ->where(function ($query) use ($request){
	    			if($request->has('buscar'))
					{
		        		$query->where('nombreComercial','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('direccion','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('idEstablecimiento','like','%'.mb_strtoupper($request->get('buscar')).'%');
		        	}})
		 ->distinct()->get();
		 
		//---SELECT DE ESTABLECIMIENTOS EN SQL SERVER
		 $registrados=DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaRegistro')
         ->select('nombreComercial','direccion','idRegistro','estado','noRegistro')
          ->where('tipoRegistro',1)
         ->where(function ($query) use ($request){
	    			if($request->has('buscar'))
					{
		        		$query->where('nombreComercial','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('direccion','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('noRegistro','like','%'.mb_strtoupper($request->get('buscar')).'%');
		        	}})
          ->get();


		 $establecimientos = new Collection;
         for($i=0;$i<count($registradosNo);$i++){
             $establecimientos->push([
                            'id'         => $registradosNo[$i]->idEstablecimiento,
                            'nom' =>    $registradosNo[$i]->nombreComercial,
                            'direc' => $registradosNo[$i]->direccion,
                            'estado' => $registradosNo[$i]->estado,
                            'noregistro' => 'N/A',
                            'base' => 1
                         
            ]);
         }
         for($j=0;$j<count($registrados);$j++){
             $establecimientos->push([
                            'id'         => $registrados[$j]->idRegistro,
                            'nom' =>    $registrados[$j]->nombreComercial,
                            'direc' => $registrados[$j]->direccion,
                            'estado' => $registrados[$j]->estado,
                            'noregistro' => $registrados[$j]->noRegistro,
                            'base' => 2
                         
            ]);
         }

		return Datatables::of($establecimientos)
	    ->make(true);	
		
	}



	 public function   getDataRowsProductosNo(Request $request){
	 	//--SELECT DE PRODUCTOS NO REGISTRADOS
		 $registradosNo = DB::table('cssp.cssp_productos AS T1')
                ->join('cssp.cssp_propietarios AS T2', 'T1.ID_PROPIETARIO','=','T2.ID_PROPIETARIO')
                 ->select(DB::raw('UPPER(T1.NOMBRE_COMERCIAL) as NOMBRE_COMERCIAL,UPPER(T2.NOMBRE_PROPIETARIO) as NOMBRE_PROPIETARIO,REPLACE(T1.ID_PRODUCTO," ","") as ID_PRODUCTO,T1.ACTIVO'))
                  ->where(function ($query) use ($request){
	    			if($request->has('buscar'))
					{
		        		$query->where('NOMBRE_COMERCIAL','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('NOMBRE_PROPIETARIO','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('ID_PRODUCTO','like','%'.mb_strtoupper($request->get('buscar')).'%');
		        	}})
                 ->distinct()
                 ->get();
          //--SELECT PRODUCTOS REGISTRADOS
        $registrados=DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaRegistro')
          ->select('nombreComercial','titular','idRegistro','fechaVencimiento','noLote','estado','noRegistro')
          ->where('tipoRegistro',2)
          ->where(function ($query) use ($request){
	    			if($request->has('buscar'))
					{
		        		$query->where('nombreComercial','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('titular','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('noRegistro','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('fechaVencimiento','like','%'.mb_strtoupper($request->get('buscar')).'%')->orWhere('noLote','like','%'.mb_strtoupper($request->get('buscar')).'%');
		        	}})
          ->get();

         $productos = new Collection;
         for($i=0;$i<count($registradosNo);$i++){
             $productos->push([
                            'id'         => $registradosNo[$i]->ID_PRODUCTO,
                            'nom' =>    $registradosNo[$i]->NOMBRE_COMERCIAL,
                            'prop' => $registradosNo[$i]->NOMBRE_PROPIETARIO,
                            'fecha'=>  '-',
                            'lote' =>  '-',
                            'estado' => $registradosNo[$i]->ACTIVO,
                            'noregistro' => 'N/A',
                            'base' => 1
                         
            ]);
         }
          for($j=0;$j<count($registrados);$j++){
             $productos->push([
                           'id'    => $registrados[$j]->idRegistro,
                            'nom' =>    $registrados[$j]->nombreComercial,
                            'prop' => $registrados[$j]->titular,
                            'fecha'=>  $registrados[$j]->fechaVencimiento,
                            'lote' =>  $registrados[$j]->noLote,
                            'estado' => $registrados[$j]->estado,
                            'noregistro' => $registrados[$j]->noRegistro,
                            'base' => 2
                         
            ]);
         }

		return Datatables::of($productos)
		->make(true);		
	}



	 public function storeEstablecimiento(Request $request){

			$v = Validator::make($request->all(),[			
	        	'nombreComercial' => 'required',
	        	'direccion' => 'required',
				    ]);

	   		$v->setAttributeNames([
	   		  'nombreComercial' 			=> 'Nombre del establecimiento',
	   		   'direccion' 			=> 'Direccion',
			   
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
		       $reg = new DenunciaRegistro();
		       $reg->nombreComercial=mb_strtoupper($request->nombreComercial, 'UTF-8');
		       $reg->titular=mb_strtoupper($request->propietario, 'UTF-8');
		       $reg->direccion= mb_strtoupper($request->direccion, 'UTF-8');
		       $reg->tipoRegistro=1;
		       $reg->observacion= $request->observacion;
		       $reg->usuarioCreacion = Auth::user()->idUsuario;
			   $reg->fechaCreacion = Carbon::now();
			   $reg->save();

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}

	 public function storeProducto(Request $request){

			$v = Validator::make($request->all(),[			
	        	'nombreComercial' => 'required'
				    ]);

	   		$v->setAttributeNames([
	   		  'nombreComercial' 			=> 'Nombre del producto',
			   
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
		       $reg = new DenunciaRegistro();
		       $reg->nombreComercial=mb_strtoupper($request->nombreComercial, 'UTF-8');
		       $reg->titular=mb_strtoupper($request->propietario, 'UTF-8');
		       $reg->noLote= $request->nolote;
		       $reg->fechaVencimiento = $request->fecha;
		       $reg->tipoRegistro=2;
		       $reg->observacion= $request->observacion;
		       $reg->usuarioCreacion = Auth::user()->idUsuario;
			   $reg->fechaCreacion = Carbon::now();
			   $reg->save();

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);

	}

    public function indexMedios()
	{
		$data = ['title' 			=> 'Catálogo de medios de recepción'
				,'subtitle'			=> ''
				,'breadcrumb' 		=> [
					['nom'	=>	'Catálogo de medios de recepción', 'url' => '#'],
			 		['nom'	=>	'', 'url' => '#'],
				]]; 
		return view('denuncia.catalogo.mediosRecepcion.index',$data);
	}
	public function  getDataRowsMedios(Request $request){
			$medios=MediosRecepcion::all();
			return Datatables::of($medios)

				   ->addColumn('detalle', function ($dt) {
		            	    
		       return '<a class="btn btn-xs btn-success btn-perspective" onclick="editarInfo(\''.$dt->idMedio.'\');" ><i class="fa fa-pencil"></i></a>';
		             
						})
	                 
			->make(true);		
	}
	public function getMedioRecepcion(Request $request){
		    $id = $request->id;
		    $medios =MediosRecepcion::find($id);
		    return response()->json($medios);
	}

		public function storeMedios(Request $request){
	
			$v = Validator::make($request->all(),[			
	        	'manera'=>'required|max:80',
				    ]);

	   		$v->setAttributeNames([
	   		    'manera' => 'manera',

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
		    	if($request->accion==1){
		    	$medio = new MediosRecepcion();
		    	$medio->usuarioCreacion = Auth::user()->idUsuario;
			    $medio->fechaCreacion = Carbon::now();
		    	}else{
				$medio = MediosRecepcion::find($request->id);
				$medio->usuarioModificacion= Auth::user()->idUsuario;
			    $medio->fechaModificacion = Carbon::now();
		    	}
		    	
		    	$medio->nombreMedio=$request->manera;
		    	$medio->save();

			} catch(Exception $e){
			    DB::rollback();
			    throw $e;
			    return $e->getMessage();
			}
			return response()->json(['state' => 'success']);
		}



	

}