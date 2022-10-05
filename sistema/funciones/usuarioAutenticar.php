<?php
session_start();
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
include_once 'conexion.php';
include_once 'Sanitize.class.php';
//include_once 'ArrayHash.class.php';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$email = isset($_POST['usuario'])?SanitizeVars::STRING($_POST['usuario'],2,50):false;
$password = isset($_POST['password'])?SanitizeVars::STRING_LETRAS_NUMEROS($_POST['password'],8,15):false;

//var_dump($_POST);
//die($apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono.'-'.$email.'-'.$localidad);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
$entidad = "Interesado";
$array_resultados = array();


if ($email && $password) {
      $msg = "";
      $passwordMD5 = md5($password);

      // SE INICIA LA TRANSACCION 
      $sql = "SELECT u.*, ur.tipo 
              FROM usuario u, usuario_rol ur
              WHERE u.email='$email' and u.password='$passwordMD5' and u.rol_id = ur.id";
      $res = @mysqli_query($conex,$sql);

      //die($sql.'**'.mysqli_num_rows($res));

      if (mysqli_num_rows($res)==1) {
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = 'Entro'; 
            $fila = mysqli_fetch_assoc($res);
            $rol = $fila['tipo'];
            $_SESSION['user_usuario'] = $fila['usuario'];
            $_SESSION['user_email'] = $fila['email'];
            $_SESSION['user_rol'] = unserialize($rol)[0];
      } else {
            $array_resultados['codigo'] = 10;
            $array_resultados['mensaje'] = 'No coincide.'; 
      }
} else {
      $array_resultados['codigo'] = 11;
      $array_resultados['mensaje'] = 'Faltan datos obligatorios.'; 
};

echo json_encode($array_resultados);


?>
