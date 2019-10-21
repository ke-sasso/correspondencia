<?php namespace App\models\Catalogo;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\Collection;
class Establecimientos extends Model {

	protected $table = 'dnm_establecimientos_si.est_establecimientos';
	public $incrementing = false;
    protected $primaryKey = 'idEstablecimiento';
    public $timestamps = false;
	const CREATED_AT = 'fechaCreacion';
	const UPDATED_AT = 'fechaModificacion';


	public static function getProductos(){
        $num1=DB::table('cssp.cssp_productos AS T1')
                ->join('cssp.cssp_propietarios AS T2', 'T1.ID_PROPIETARIO','=','T2.ID_PROPIETARIO')
                 ->select(DB::raw('UPPER(T1.NOMBRE_COMERCIAL) as nom,UPPER(T2.NOMBRE_PROPIETARIO) as prop,REPLACE(T1.ID_PRODUCTO," ","") as ID_PRODUCTO,T1.ACTIVO'))->count();
        $num2=DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaRegistro')
         ->select('nombreComercial as nom','direccion as direc','idRegistro as id','estado')
          ->where('tipoRegistro',2)->count();

        $registradosNo = DB::table('cssp.cssp_productos AS T1')
                ->join('cssp.cssp_propietarios AS T2', 'T1.ID_PROPIETARIO','=','T2.ID_PROPIETARIO')
                 ->select(DB::raw('UPPER(T1.NOMBRE_COMERCIAL) as nom,UPPER(T2.NOMBRE_PROPIETARIO) as prop,REPLACE(T1.ID_PRODUCTO," ","") as ID_PRODUCTO,T1.ACTIVO'))
                 ->distinct()
                 ->get();
        $registrados=DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaRegistro')
          ->select('nombreComercial','titular','idRegistro','fechaVencimiento','noLote','estado')
          ->where('tipoRegistro',2)
          ->get();

            //base 1.MYSQL 2.SQLSERVER

         $productos = new Collection;
         for($i=0;$i<$num1;$i++){
             $productos->push([
                            'id'         => $registradosNo[$i]->ID_PRODUCTO,
                            'nom' =>    $registradosNo[$i]->nom,
                            'prop' => $registradosNo[$i]->prop,
                            'fecha'=>  '-',
                            'lote' =>  '-',
                            'estado' => $registradosNo[$i]->ACTIVO,
                            'base' => 1
                         
            ]);
         }
          for($j=0;$j<$num2;$j++){
             $productos->push([
                           'id'    => $registrados[$j]->idRegistro,
                            'nom' =>    $registrados[$j]->nombreComercial,
                            'prop' => $registrados[$j]->titular,
                            'fecha'=>  $registrados[$j]->fechaVencimiento,
                            'lote' =>  $registrados[$j]->noLote,
                            'estado' => $registrados[$j]->estado,
                            'base' => 2
                         
            ]);
         }

         return $productos;
    }
    public static function getEstablecimientos(){
 $num1=Establecimientos::select('nombreComercial as nom','direccion as direc','idEstablecimiento as id','estado')->count();
 $num2=DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaRegistro')
         ->select('nombreComercial as nom','direccion as direc','idRegistro as id','estado')
          ->where('tipoRegistro',1)->count();

     $registradosNo=Establecimientos::select(DB::raw('UPPER(nombreComercial) as nombreComercial,UPPER(direccion) as direccion,idEstablecimiento,estado'))->distinct()->get();

     $registrados=DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaRegistro')
         ->select('nombreComercial','direccion','idRegistro','estado')
          ->where('tipoRegistro',1)
          ->get();

          //base 1.MYSQL 2.SQLSERVER

         $establecimientos = new Collection;
         for($i=0;$i<$num1;$i++){
             $establecimientos->push([
                            'id'         => $registradosNo[$i]->idEstablecimiento,
                            'nom' =>    $registradosNo[$i]->nombreComercial,
                            'direc' => $registradosNo[$i]->direccion,
                            'estado' => $registradosNo[$i]->estado,
                            'base' => 1
                         
            ]);
         }
          for($j=0;$j<$num2;$j++){
             $establecimientos->push([
                            'id'         => $registrados[$j]->idRegistro,
                            'nom' =>    $registrados[$j]->nombreComercial,
                            'direc' => $registrados[$j]->direccion,
                            'estado' => $registrados[$j]->estado,
                            'base' => 2
                         
            ]);
         }

         return $establecimientos;

      }

       public static function getEstablecimientosBusqueda($valor){
 $num1=Establecimientos::select(DB::raw('UPPER(nombreComercial) as nombreComercial,UPPER(direccion) as direccion,idEstablecimiento,estado'))->where('nombreComercial','like','%'.$valor.'%')->orWhere('direccion','like','%'.$valor.'%')->orWhere('idEstablecimiento','like','%'.$valor.'%')->count();
 $num2=DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaRegistro')
         ->select('nombreComercial','direccion','idRegistro','estado')->where('tipoRegistro',1)->orWhere('nombreComercial','like','%'.$valor.'%')->orWhere('direccion','like','%'.$valor.'%')->count();

     $registradosNo=Establecimientos::select(DB::raw('UPPER(nombreComercial) as nombreComercial,UPPER(direccion) as direccion,idEstablecimiento,estado'))->where('nombreComercial','like','%'.$valor.'%')->orWhere('direccion','like','%'.$valor.'%')->orWhere('idEstablecimiento','like','%'.$valor.'%')->distinct()->get();

     $registrados=DB::connection('sqlsrv')->table('dnm_correspondencia_si.COR.denunciaRegistro')
         ->select('nombreComercial','direccion','idRegistro','estado')
          ->where('tipoRegistro',1)->orWhere('nombreComercial','like','%'.$valor.'%')->orWhere('direccion','like','%'.$valor.'%')
          ->get();

          //base 1.MYSQL 2.SQLSERVER
        
        
         $establecimientos = new Collection;
         for($i=0;$i<$num1;$i++){
             $establecimientos->push([
                            'id'    => $registradosNo[$i]->idEstablecimiento,
                            'nom' =>    $registradosNo[$i]->nombreComercial,
                            'direc' => $registradosNo[$i]->direccion,
                            'estado' => $registradosNo[$i]->estado,
                            'base' => 1
                         
            ]);
         }
          for($j=0;$j<$num2;$j++){
             $establecimientos->push([
                            'id'         => $registrados[$j]->idRegistro,
                            'nom' =>    $registrados[$j]->nombreComercial,
                            'direc' => $registrados[$j]->direccion,
                            'estado' => $registrados[$j]->estado,
                            'base' => 2
                         
            ]);
         }

         return $establecimientos;

      }


}
