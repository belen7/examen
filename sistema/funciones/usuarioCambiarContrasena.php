<?php
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
include_once 'controlAcceso.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
//include_once 'ArrayHash.class.php';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$password = isset($_POST['password'])?SanitizeVars::STRING_LETRAS_NUMEROS($_POST['password'],8,15):false;
$repassword = isset($_POST['repassword'])?SanitizeVars::STRING_LETRAS_NUMEROS($_POST['repassword'],8,15):false;

//die($id.'-'.$usuario.'-'.$email.'-'.$idRol);

//$password = $repassword = 'turk1178';

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();

if ($password && $repassword) {
      if ($password===$repassword) {     
            // SE INICIA LA TRANSACCION 
            db_start_trans($conex);     
            $pwd_encriptada = md5($password);
            $sqlActualiza = "UPDATE usuario 
                        SET `password` = '$pwd_encriptada'
                        WHERE email = '".$_SESSION['user_email']."'";            
            //die($sqlActualiza);    
            $ok = @mysqli_query($conex,$sqlActualiza);
            //PREGUNTAMOS SI HUBO ERROR
            if(!$ok){
                  db_rollback($conex);
                  $array_resultados['codigo'] = 11;
                  $array_resultados['mensaje'] = "La Contraseña no ha podido ser actualizada."; 
            } else {
                  db_commit($conex);
                  $array_resultados['codigo'] = 100;
                  $array_resultados['mensaje'] = "La Contraseña se ha actualizado exitosamente."; 
            };
      } else {
            $array_resultados['codigo'] = 12;
            $array_resultados['mensaje'] = "No coincide la Nueva Contraseña con su confirmación."; 
      }      
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['mensaje'] = "Debe completar todos los datos.";
}

echo json_encode($array_resultados);



?>
