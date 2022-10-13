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

//var_dump($_POST);die;
//die($id.'-'.$apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono.'-'.$telefono_caracteristica.'-'.$telefono_numero.'-'.$email.'-'.$localidad.'-'.$asistio.'-'.$pago.'-'.$fecha_nacimiento);

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();
if ($id) {
      $entidad = "Interesado";
      /** SE INICIA LA TRANSACCION **/
      db_start_trans($conex);     
      $sqlActualiza = "UPDATE interesado 
                       SET asistio = 'Si'
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
