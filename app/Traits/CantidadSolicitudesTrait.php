<?php
namespace App\Traits;

use DB;
use Auth;
use Carbon\Carbon;
use  App\Models\Solicitud\vwSolicitudes;

trait CantidadSolicitudesTrait{

      public static function countSolPerfilAsesor($permisos){
            $data=[];
            //SE ENVIO ARREGLO PARA MOSTRAR EN EL MENÚ LA CANTIDAD DE SOLICITUDES PENDIENTES, ESTA INFORMACIÓN SOLO SE MUESTRA AL USUARIO DIRECTOR EJECUTIVO
            //432 Dra. Monica Guadalupe Ayala Guerrero
            $idEmpleado=Auth::user()->idEmpleado;
            if($idEmpleado==432){
                 //para revisión
                $count1=vwSolicitudes::listAdmin()->where('idEstado',7)->count();
                //para firma
                $count2=vwSolicitudes::listAdmin()->where('idEstado',8)->count();
                //pendientes asignadas
                $count3=vwSolicitudes::listParticipantes(Auth::user()->idEmpleado)
                ->where('T2.idEstado',1)
                ->whereIn('T1.idEstado',[2,3])
                ->distinct()->count();

                $count4=vwSolicitudes::listDenunciaParticipante(Auth::user()->idEmpleado)->count();

                $data['revision']=$count1;
                $data['firma']=$count2;
                $data['asignada']=$count3;
                $data['total']=$count1+$count2+$count3;
                $data['denuncia']=$count4;
                return $data;
            }else{
              return $data;
            }


      }



}