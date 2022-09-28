<?php
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
include_once 'controlAcceso.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
//include_once 'ArrayHash.class.php';

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$entidades_a_eliminar = ( isset($_POST['id']) && $_POST['id']!="" )?$_POST['id']:false;

/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();

if ($entidades_a_eliminar) {
      //die('entrooo');
      $arreglo_entidades = explode(',',$entidades_a_eliminar);
      $cantidad_entidades = count($arreglo_entidades);
      $errorNro = 0;  
      $msg = "";
      db_start_trans($conex);     
      foreach($arreglo_entidades as $idEntidad) {
            $sql = "DELETE FROM usuario
                    WHERE id = $idEntidad";
            //die($sql);   
            /** SE INICIA LA TRANSACCION **/
            
            $ok = @mysqli_query($conex,$sql);
            //PRENGUNTAMOS SI HUBO ERROR
            if(!$ok){
                  $errorNro =  mysqli_errno($conex);
                  db_rollback($conex);
                  break;
            }; 
      } // END FOR

      if ($errorNro) {
            if ($cantidad_entidades>1) {
                  $msg = "Hubo un Error en la Eliminaci&oacute;n de los Usuarios. ";
            } else {
                  $msg = "Hubo un Error en la Eliminaci&oacute;n de el Usuario. ";
            }
            $array_resultados['codigo'] = 10;
            $array_resultados['mensaje'] = $msg;  
      } else {
            db_commit($conex);
            if ($cantidad_entidades>1) {
                  $msg = "La Eliminaci&oacute;n de los usuarios fue exitosa.";
            } else {
                  $msg = "La Eliminaci&oacute;n del usuario fue exitosa.";
            }
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = $msg;
      };
};

echo json_encode($array_resultados);



?>
