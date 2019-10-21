<?php namespace App\Http\Controllers;

use App;
use Datatables;
use GuzzleHttp\Client;
use Config;
use File;
use App\Models\Catalogo\MediosRecepcion;
use App\Models\Solicitud\Solicitudes;
use App\Models\Catalogo\PersonaNatural;
use App\Models\Solicitud\Adjunto;
use App\Models\Solicitud\Municipios;
use App\Models\Solicitud\Departamento;
use App\Models\Solicitud\DenunciaRegistro;
use App\Models\Solicitud\DenunciaDetalle;
use App\Models\Solicitud\SolicitudDenuncia;
use App\Models\Solicitud\Empleados;
use Session;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Encryption;
use Crypt;
use Response;
use Auth;
use App\Models\Solicitud\Acciones;
use App\Models\Solicitud\FechaRespuesta;
use App\Models\Solicitud\Participantes;
use NumeroALetras;
use DB;
use Date;
use App\User;
class PdfController extends Controller {



public static function mostrarDatosPdf(){
                   $idSolicitud = session::get('idSolicitudPdf');
	                 $datosGenerales = Solicitudes::find($idSolicitud);
                   $nit = $datosGenerales->nitSolicitante;
                   $archivos = Adjunto::where('idSolicitud','=',$idSolicitud)->get();
                   $datosSolicitante = PersonaNatural::find($nit);
                   $usuarioIngreso = Auth::user()->nombresUsuario ." ".Auth::user()->apellidosUsuario;
                  
                  $listFecha = FechaRespuesta::all();
                  $listAcciones = Acciones::all();
                  $emple = Participantes::getEmpleadosJefe(); 

                  $numeroEmpleados=count($emple);
//dd($numeroEmpleados);
                  if ($numeroEmpleados%2==0){
                      // echo "el $numero es par";
                          $a1 = $numeroEmpleados/2;
             
                          for($b=0;$b<$a1;$b++){
                               $primero[$b]=$emple[$b];
                          }

                          for($c=$a1;$c<count($emple);$c++){
                              $segundo[$c]=$emple[$c];
                          }

                    }else{
                      //echo "el $numero es impar";
                      $a2 = $numeroEmpleados/2;
                      $a2 =$a2+0.5;
                     // dd($a2);
                        for($b=0;$b<$a2;$b++){
                              $primero[$b]=$emple[$b];
                          }
                        for($c=$a2;$c<count($emple);$c++){
                          $segundo[$c]=$emple[$c];
                          }
                     }
                 
                 /* for($b=0;$b<10;$b++){
                    $primero[$b]=$emple[$b];
                  }
                  for($c=10;$c<count($emple);$c++){
                    $segundo[$c]=$emple[$c];
                  }*/

                   $data = ['general' => $datosGenerales,
                          'solicitante' => $datosSolicitante,
                          'usu'=>$usuarioIngreso,
                          'arc'=>$archivos,
                          'listFecha'=>$listFecha,
                          'listAcciones'=>$listAcciones,
                          'emple1'=>$primero,
                          'emple2'=>$segundo];	

                 
                 $view =  \View::make('pdf.boletaPresentacion',$data)->render();
                 $pdf = \App::make('dompdf.wrapper');
                 $pdf->loadHTML($view);
                 return $pdf->stream("BOLETA - ".trim($datosGenerales->noPresentacion).".pdf");
                 Session::forget('idSolicitudPdf');
                 
}

public static function pdfDenuncia(){
  
                   $idSolicitud = Session::get('idDenuncia');
                   $datosGenerales = Solicitudes::find($idSolicitud);
                   $detalle = SolicitudDenuncia::find($idSolicitud);
                   $idMun =$detalle->idMunicipio;
                   $idDep =$detalle->idDepartamento;
                   $idMedio= $datosGenerales->idMedio;
                   if($idMun==0 || $idMun==''){
                    $nomMun='';
                    
                   }else{
                    $nomMun = Municipios::where('idMunicipio','=',$idMun)->select('nombreMunicipio')->first();
                    $nomMun = $nomMun->nombreMunicipio;
                   }
                  
                   if($idDep==0 || $idDep==''){
                    $nomDep='';
                   }else{
                    
                    $nomDep = Departamento::where('idDepartamento','=',$idDep)->select('nombreDepartamento')->first();
                    $nomDep=$nomDep->nombreDepartamento;
                   }
                   if($idMedio==0 || $idMedio==''){
                    $idMedio='';
                   }else{
                    $idMedio = MediosRecepcion::find($idMedio);
                    $idMedio=$idMedio->nombreMedio;
                   }
                   $archivos = Adjunto::where('idSolicitud','=',$idSolicitud)->get();

                   $lista=DenunciaDetalle::where('idDenuncia','=',$idSolicitud)->select('idRegistro')->pluck('idRegistro');
                   $establecimientos=DenunciaRegistro::whereIn('idRegistro',$lista)->where('tipoRegistro','=',1)->get();
                   $productos=DenunciaRegistro::whereIn('idRegistro',$lista)->where('tipoRegistro','=',2)->get();
       
                   $data = ['general' => $datosGenerales,
                            'detalle' => $detalle,
                            'arc'=>$archivos,
                            'municipio'=>$nomMun,
                            'departamento'=>$nomDep,
                            'establecimientos' => $establecimientos,
                            'productos'=>$productos,
                            'medio'=>$idMedio

                             ];  

              
                 $view =  \View::make('pdf.boletaDenuncia',$data)->render();
                 $pdf = \App::make('dompdf.wrapper');
                 $pdf->loadHTML($view);
                 return $pdf->stream("DENUNCIA - ".trim($datosGenerales->noPresentacion).".pdf");
                 Session::forget('idDenuncia');
                 
}
public static function pdfActa(Request $request){
                 $idSolicitud=Crypt::decrypt($request->idDenuncia);
                 $datosGenerales = Solicitudes::find($idSolicitud);
                 $detalle = SolicitudDenuncia::find($idSolicitud);

                 $yy= date('Y-m-d',strtotime($datosGenerales->fechaCreacion));
                 $infoTecnico = User::find($datosGenerales->usuarioCreacion);
                 $nombreTec = $infoTecnico->nombresUsuario." ".$infoTecnico->apellidosUsuario;
                 $plaza = Empleados::plazaEmpleado($infoTecnico->idEmpleado);
                
                   $data = ['general' => $datosGenerales,
                            'detalle' => $detalle,
                             'fecha'=>PdfController::horaFechaAutoTexto2(),
                             'fechaCreacion'=>PdfController::textoFecha($yy),
                             'nombreTec'=>$nombreTec,
                             'plaza'=>$plaza->nombrePlaza
                             ];  
                 $view =  \View::make('pdf.boletaActa',$data)->render();
                 $pdf = \App::make('dompdf.wrapper');
                 $pdf->loadHTML($view);
                 return $pdf->stream("DENUNCIA ACTA- ".trim($datosGenerales->noPresentacion).".pdf");

                 $view =  \View::make('pdf.Sim.resoluobservadasim',$data)->render();
        

               
}

 public static function horaFechaAutoTexto2(){//la diferencia con la anterior es que sta no devuelve el dia de la semana
     $fecha = DB::select('select date_add(curdate() , INTERVAL 0 DAY) as fecha');     
     $diaNumero = new Date($fecha[0]->fecha);
     $diaNumero = NumeroALetras::convertir($diaNumero->format('j'));
     $mes = new Date($fecha[0]->fecha);
     $mes = $mes ->format('F');
     $año = new Date($fecha[0]->fecha);
     $año = NumeroALetras::convertir($año->format('Y'));
     //$dia = explode(",",$dia);
     $hoy = getdate();
     $hora =(int)($hoy['hours']);
     $hora = NumeroALetras::convertir($hora);
     $minutos = $hoy['minutes'];
     $minutos = NumeroALetras::convertir($minutos);

     return strtolower($hora.' horas '.$minutos.' minutos del dia '.$diaNumero.' de '.$mes.' de '.$año) ;
    }
 public static function textoFecha($fecha){//la diferencia con la anterior es que sta no devuelve el dia de la semana
    
     $diaNumero = new Date($fecha);
     $diaNumero = NumeroALetras::convertir($diaNumero->format('j'));
     $mes = new Date($fecha);
     $mes = $mes ->format('F');
     $año = new Date($fecha);
     $año = NumeroALetras::convertir($año->format('Y'));

    

     return strtolower($diaNumero.' de '.$mes.' de '.$año) ;
    }
public function download($urlDocumento,$tipoDoc){
    //dd($tipoDocumento);
       
     if($urlDocumento!=""){

    $urlDocumento=Crypt::decrypt($urlDocumento);   
    $td=Crypt::decrypt($tipoDoc);
    $tipoArchivo = trim($td);
    
    $info = pathinfo($urlDocumento);
    $filename =  basename($urlDocumento,'.'.$info['extension']);
    //dd($file_name);
    if($tipoArchivo=='application/pdf'){    
                if (File::isFile($urlDocumento))
                {

                  try {
                          $file = File::get($urlDocumento);
                          $response = Response::make($file, 200);         
                          $response->header('Content-Type', 'application/pdf');
                          $response->header('Content-Disposition', 'inline; filename="Documento - '.$filename.'.pdf"');
                          return $response;
                         
                          /*return Response::make(file_get_contents($urlDocumento), 200, [
                          'Content-Type' => 'application/pdf',
                          'Content-Disposition' => 'inline; filename="Documento - '.$filename.'"'
                            ]);*/
                     } catch (Exception $e) {
                                            
                        return Response::download(trim($urlDocumento));
                     }
                }else{
                  return back();
                }
    }else if($tipoArchivo=='image/png' or $tipoArchivo==='image/jpeg'){
                  if (File::isFile($urlDocumento))
                  {
                      $file = File::get($urlDocumento);
                      $response = Response::make($file, 200);
                      // using this will allow you to do some checks on it (if pdf/docx/doc/xls/xlsx)
                       $content_types = [
                            'image/png', // png etc
                            'image/jpeg', // jpeg
                              ];
                      $response->header('Content-Type', $content_types);
                      $response->header('Content-Disposition', 'inline; filename="Documento - '.$filename.'.jpeg"');
                      return $response;
                       
                         /* return Response::make(file_get_contents($urlDocumento), 200, [
                          'Content-Type' => ['image/png','image/jpeg'],
                          'Content-Disposition' => 'inline; filename="Documento -'.$filename.'"'
                            ]);*/
                  }else{
                    //REToRNA A LA VISTA SI NO EXISTE ESE ARCHIVO
                  return back();
                  }
      }else{

              if (File::isFile($urlDocumento))
              { 
          
                  return Response::download(trim($urlDocumento));
              }
    }

  }

  }//cierre del metodo











}