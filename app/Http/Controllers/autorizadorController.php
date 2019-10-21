<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Carbon\Carbon;
use Crypt;
use  App\Models\Solicitud\Solicitudes;
use App\Models\Solicitud\Comentarios;
use Illuminate\Contracts\Encryption\DecryptException;
use Config;
use DB;
use Debugbar;
use App\User;
use Log;
use App\Models\Solicitud\Participantes;
use App\Models\Solicitud\Historial;
use Mail;
class autorizadorController extends Controller
{
  public function autorizarComentario($idSolicitud,$idComentario)
    {

        $come = Comentarios::find($idComentario);
        $validar = Comentarios::where('idSolicitud','=',$idSolicitud)->where('idEstado','=',1)->count();

        if($validar==0){
             try {   
                $come->idEstado=1;
                $come->save();

                $part= Participantes::where('idSolicitud','=',$idSolicitud)->pluck('idEmpleado');
                $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');
        
               /* $data['soli'] = Solicitudes::find($idSolicitud);
                foreach($empCorreo as $a){
                        if(!empty($a)){
                                     Mail::send('emails.aprobarComentario',$data,function($msj) use ($data,$a){
                                     $msj->subject('Nueva observación en solicitud correspondencia');
                                     $msj->to($a);
                                     $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
                                        });
                          }
                    }
                    
                 Mail::send('emails.aprobarComentario',$data,function($msj) use ($data){
                                     $msj->subject('Nueva observación en solicitud correspondencia');
                                     $msj->to(env('MAIL_ASISTENTE'));
                                     $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
                                        });*/
             return view('errors.exito');
           
              } catch(Exception $e){
     
                echo 'Problemas con la consulta solicitada';
             }
        }else{
                 return view('errors.denegar');
        }

         
    }
 public function enviarComentario(Request $request)
    {
        
        DB::connection('sqlsrv')->beginTransaction();
        $comentario = Crypt::decrypt($request->comentario);

        try {        
                    $idPar = Participantes::where('idEmpleado','=',7)->where('idSolicitud','=',$request->idSolicitud)->first();
                    $cod = $idPar->idParticipante;

                    $comen = new Comentarios();
                    $comen->comentario = $comentario;
                    $comen->idSolicitud = $request->idSolicitud;
                    $comen->idParticipante = $cod;
                    $comen->idEstado=0;
                    $comen->fechaCreacion = Carbon::now();
                    $comen->save();
           
                    $part= Participantes::where('idSolicitud',$request->idSolicitud)->pluck('idEmpleado');
                    $empCorreo = User::whereIn('idEmpleado',$part)->pluck('correo');
                
                    $soli = Solicitudes::Find($request->idSolicitud);
                    $es = $soli->idEstado;
                    if($es==2){
                        $soli->idEstado=3;
                        $soli->save();

                        $historia = new Historial();
                        $historia->idSolicitud =  $request->idSolicitud;
                        $historia->idEstado = 3;
                        $historia->usuarioCreacion = Auth::user()->idUsuario;
                        $historia->fechaCreacion = Carbon::now();
                        $historia->save();

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
                    
               /* Mail::send('emails.comentarioAdminParticipante',$data,function($msj) use ($data){
                                     $msj->subject('Nueva comentario en solicitud correspondencia');
                                     if($data['comentario']->archivo!='' || $data['comentario']->archivo!=NULL){
                                         $msj->attach($data['comentario']->archivo);
                                     }
                                     $msj->to(env('MAIL_ADMINPARTICIPANTES'));
                                    $msj->bcc('rogelio.menjivar@medicamentos.gob.sv');
                                        });*/

          
          DB::connection('sqlsrv')->commit();   
          return view('errors.exitoComentario');
            

            

        } catch (\Exception $e) {
            Debugbar::addException($e);
           return view('errors.errorComentario');
        }
         
    }//fin metodo

}
