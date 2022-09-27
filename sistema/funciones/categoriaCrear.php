<?php
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
//include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
//include_once 'ArrayHash.class.php';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/


$descripcion = ( isset($_POST['descripcion']) )?SanitizeVars::APELLIDONOMBRES($_POST['descripcion'],2,50):false;
$id = ( isset($_POST['id']) )?SanitizeVars::INT($_POST['id']):false;
//die($descripcion.'-'.$id);
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
$entidad = "Categoría";
$array_resultados = array();
if ($descripcion && $id) {
      $msg = "";
      

      /** SE INICIA LA TRANSACCION **/
      db_start_trans($conex);     
      //die('entro2');
      $sqlInserta = "INSERT INTO categoria (descripcion, categoria_id) values
                                            ('$descripcion',$id)";
      $ok = @mysqli_query($conex,$sqlInserta);

      
      
      //PRENGUNTAMOS SI HUBO ERROR
      if(!$ok){
            db_rollback($conex);
            $msg = "La ".$entidad." no pudo ser creada.";
            $array_resultados['codigo'] = 10;
            $array_resultados['mensaje'] = $msg; 
      } else {
            db_commit($conex);
            $msg = "La ".$entidad." fue creada exitosamente.";
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = $msg;
      };
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['mensaje'] = "Debe completar todos los datos.";
}

echo json_encode($array_resultados);



?>
