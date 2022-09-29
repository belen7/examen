<?php
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
include_once 'controlAcceso.php';
include_once 'conexion.php';
include_once 'SanitizeCustom.class.php';
//include_once 'ArrayHash.class.php';


/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/
$id = ( isset($_POST['id']) )?SanitizeVarsCustom::INT($_POST['id']):false;
$apellido = isset($_POST['apellido'])?SanitizeVarsCustom::APELLIDONOMBRES($_POST['apellido'],2,50):false;
$nombres = isset($_POST['nombres'])?SanitizeVarsCustom::APELLIDONOMBRES($_POST['nombres'],2,50):false;
$dni = isset($_POST['dni'])?SanitizeVars::NUMEROS($_POST['dni'],8,8):false;
$domicilio = isset($_POST['domicilio'])?SanitizeVarsCustom::DOMICILIO($_POST['domicilio'],50):false;
$telefono_caracteristica = isset($_POST['telefono_caracteristica'])?SanitizeVarsCustom::NUMEROS($_POST['telefono_caracteristica'],2,4):false;
$telefono_numero = isset($_POST['telefono_numero'])?SanitizeVarsCustom::NUMEROS($_POST['telefono_numero'],6,11):false;
$email = isset($_POST['email'])?SanitizeVarsCustom::EMAIL($_POST['email']):false;
$localidad = isset($_POST['localidad'])?SanitizeVarsCustom::INT($_POST['localidad']):false;
$asistio = isset($_POST['asistio'])?SanitizeVarsCustom::SI_NO($_POST['asistio']):false;
$pago = isset($_POST['pago'])?SanitizeVarsCustom::SI_NO($_POST['pago']):false;
$foto = isset($_POST['foto']) && ($_POST['foto']<>'')?$_POST['foto']:false;
$fecha_nacimiento = isset($_POST['fecha_nacimiento']) && ($_POST['fecha_nacimiento']<>'')?substr($_POST['fecha_nacimiento'],6,4).'-'.substr($_POST['fecha_nacimiento'],3,2).'-'.substr($_POST['fecha_nacimiento'],0,2):false;
$telefono = $telefono_caracteristica.$telefono_numero;

//var_dump($_POST);die;
//die($id.'-'.$apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono.'-'.$telefono_caracteristica.'-'.$telefono_numero.'-'.$email.'-'.$localidad.'-'.$asistio.'-'.$pago.'-'.$fecha_nacimiento);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();
if ($id && $apellido && $nombres && $dni && $domicilio && $telefono && $email && $localidad && $asistio && $pago && $fecha_nacimiento) {
      $entidad = "Interesado";
      /** SE INICIA LA TRANSACCION **/
      db_start_trans($conex);     
      $sqlActualiza = "UPDATE interesado 
                       SET apellido = '$apellido', nombres = '$nombres', dni = '$dni', direccion = '$domicilio',
                           telefono = '$telefono', telefono_caracteristica = '$telefono_caracteristica', telefono_numero = '$telefono_numero',
                           email = '$email', localidad_id = $localidad, asistio = '$asistio',
                           pago = '$pago', fecha_nacimiento = '$fecha_nacimiento'
                       WHERE id = $id";
      
      //die($sqlActualiza);    

      $ok = @mysqli_query($conex,$sqlActualiza);
     
      //PRENGUNTAMOS SI HUBO ERROR
      if(!$ok){
            db_rollback($conex);
            $array_resultados['codigo'] = 11;
            $array_resultados['mensaje'] = "El ".$entidad." no pudo ser actualizado."; 
      } else {
            db_commit($conex);
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = "El ".$entidad." fue actualizado exitosamente.";
      };
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['mensaje'] = "Debe completar todos los datos.";
}

echo json_encode($array_resultados);



?>
