<?php
session_start();
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
include_once 'conexion.php';
include_once 'Sanitize.class.php';
//include_once 'ArrayHash.class.php';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$usuario = isset($_POST['usuario'])?SanitizeVars::STRING($_POST['usuario'],2,50):false;
$password = isset($_POST['password'])?SanitizeVars::STRING_LETRAS_NUMEROS($_POST['password'],8,15):false;

//var_dump($_POST);
//die($apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono.'-'.$email.'-'.$localidad);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
$entidad = "Interesado";
$array_resultados = array();


$SYS_USERS = json_decode(SYS_USERS,true);
//var_dump(array_keys($SYS_USERS));die;

if ($usuario && $password) {

      if (in_array($usuario,array_keys($SYS_USERS))) {
            foreach($SYS_USERS as $indice => $valor) {
                  if ($indice==$usuario && $valor==$password) {
                        $_SESSION['user_usuario'] = $usuario;
                        $_SESSION['user_email'] = "";
                        $_SESSION['user_rol'] = 'SYSTEM';
                        $_SESSION['user_providers'] = 'PROVIDER_SYSTEM';
                        $array_resultados['codigo'] = 100;
                        $array_resultados['mensaje'] = 'Entro'; 
                        break;
                  };
            }
      } else {
            $msg = "";
            $passwordMD5 = md5($password);

            // SE INICIA LA TRANSACCION 
            $sql = "SELECT u.*, ur.tipo 
                  FROM usuario u, usuario_rol ur
                  WHERE u.email='$usuario' and u.password='$passwordMD5' and u.rol_id = ur.id";
            $res = @mysqli_query($conex,$sql);

            if (mysqli_num_rows($res)==1) {
                  $array_resultados['codigo'] = 100;
                  $array_resultados['mensaje'] = 'Entro'; 
                  $fila = mysqli_fetch_assoc($res);
                  $rol = $fila['tipo'];
                  $_SESSION['user_usuario'] = $fila['usuario'];
                  $_SESSION['user_email'] = $fila['email'];
                  $_SESSION['user_rol'] = unserialize($rol)[0];
                  $_SESSION['user_providers'] = 'PROVIDER_DB';
            } else {
                  $array_resultados['codigo'] = 10;
                  $array_resultados['mensaje'] = 'No coincide.'; 
            }
      };
} else {
      $array_resultados['codigo'] = 11;
      $array_resultados['mensaje'] = 'Faltan datos obligatorios.'; 
};

echo json_encode($array_resultados);


?>
