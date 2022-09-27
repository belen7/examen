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

$apellido = isset($_POST['apellido'])?SanitizeVars::APELLIDONOMBRES($_POST['apellido'],2,50):false;
$nombres = isset($_POST['nombres'])?SanitizeVars::APELLIDONOMBRES($_POST['nombres'],2,50):false;
$dni = isset($_POST['dni'])?SanitizeVars::NUMEROS($_POST['dni'],8,8):false;
$domicilio = isset($_POST['domicilio'])?SanitizeVars::DOMICILIO($_POST['domicilio'],50):false;
$telefono = isset($_POST['telefono'])?SanitizeVars::STRING($_POST['telefono'],6,15):false;
$email = isset($_POST['email'])?SanitizeVars::EMAIL($_POST['email']):false;
$localidad = isset($_POST['localidad'])?SanitizeVars::INT($_POST['localidad']):false;
$foto = isset($_POST['foto']) && ($_POST['foto']<>'')?$_POST['foto']:false;
//var_dump($_POST);
//die($apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono.'-'.$email.'-'.$localidad);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
$entidad = "Interesado";
$array_resultados = array();
if ($apellido && $nombres && $dni && $domicilio && $domicilio) {
      $msg = "";
      $password_por_defecto = md5('12345678');
      /** SE INICIA LA TRANSACCION **/
      db_start_trans($conex);     
      $sqlInserta = "INSERT INTO  `interesado` (apellido, nombres, dni, direccion, telefono, email, localidad_id, foto ) values
                                            ('$apellido', '$nombres', '$dni', '$domicilio','$telefono','$email','$localidad', '$foto')";
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
      $array_resultados['mensaje'] = "Debe completar todos los datosSS.";
}

echo json_encode($array_resultados);



?>
