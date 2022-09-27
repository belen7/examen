<?php
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
//include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
//include_once 'ArrayHash.class.php';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$id = ( isset($_POST['id']) )?SanitizeVars::INT($_POST['id']):false;
$padre = ( isset($_POST['padre']) )?SanitizeVars::INT($_POST['padre']):false;
$descripcion = ( isset($_POST['descripcion']) )?SanitizeVars::APELLIDONOMBRES($_POST['descripcion'],2,50):false;


//die($idAlumno.'-'.$idPersona.'-'.$apellido.'-'.$nombres.'-'.$domicilio.'-'.$dni.'-'.$telefono.'-'.$debe_titulo.'-'.$habilitado.'-'.$email);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();
if ($id && $padre && $descripcion) {
      $entidad = "Categoría";
      /** SE INICIA LA TRANSACCION **/
      db_start_trans($conex);     
      $sqlActualiza = "UPDATE categoria 
                       SET descripcion = '$descripcion', categoria_id = $padre
                       WHERE id = $id";
                     
      
      //die($sqlActualiza);    

      $ok = @mysqli_query($conex,$sqlActualiza);
     
      //PRENGUNTAMOS SI HUBO ERROR
      if(!$ok){
            db_rollback($conex);
            $array_resultados['codigo'] = 11;
            $array_resultados['mensaje'] = "La ".$entidad." no pudo ser actualizado."; 
      } else {
            db_commit($conex);
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = "La ".$entidad." fue actualizada exitosamente.";
      };
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['mensaje'] = "Debe completar todos los datos.";
}

echo json_encode($array_resultados);



?>
