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
$usuario = ( isset($_POST['usuario']) )?SanitizeVars::STRING_LETRAS_NUMEROS($_POST['usuario'],6,15):false;
$email = ( isset($_POST['email']) )?SanitizeVars::EMAIL($_POST['email']):false;
$idRol = ( isset($_POST['rol']) )?SanitizeVars::INT($_POST['rol']):false;


//die($id.'-'.$usuario.'-'.$email.'-'.$idRol);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();
if ($id && $usuario && $email && $idRol) {
      $entidad = "Usuario";
      /** SE INICIA LA TRANSACCION **/
      db_start_trans($conex);     
      $sqlActualiza = "UPDATE usuario 
                       SET usuario = '$usuario', email = '$email', rol_id = $idRol
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
