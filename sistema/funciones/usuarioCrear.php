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


$usuario = ( isset($_POST['usuario']) )?SanitizeVars::STRING_LETRAS_NUMEROS($_POST['usuario'],8,15):false;
$email = ( isset($_POST['email']) )?SanitizeVars::EMAIL($_POST['email']):false;
$idRol = ( isset($_POST['rol']) )?SanitizeVars::INT($_POST['rol']):false;
//die($usuario.'-'.$email.'-'.$idRol);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
$entidad = "Usuario";
$array_resultados = array();
if ($usuario && $email && $idRol) {
      $msg = "";
      $password_por_defecto = md5('12345678');
      /** SE INICIA LA TRANSACCION **/
      db_start_trans($conex);     
      $sqlInserta = "INSERT INTO  `usuario` (`usuario`, `password`, `email`, `rol_id`) values
                                            ('$usuario', '$password_por_defecto', '$email', $idRol)";
      //die($sqlInserta);
      $ok = @mysqli_query($conex,$sqlInserta);

      
      
      //PRENGUNTAMOS SI HUBO ERROR
      if(!$ok){
            db_rollback($conex);
            $msg = "El ".$entidad." no pudo ser creado.";
            $array_resultados['codigo'] = 10;
            $array_resultados['mensaje'] = $msg; 
      } else {
            db_commit($conex);
            $msg = "El ".$entidad." fue creado exitosamente.";
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = $msg;
      };
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['mensaje'] = "Debe completar todos los datos.";
}

echo json_encode($array_resultados);



?>
