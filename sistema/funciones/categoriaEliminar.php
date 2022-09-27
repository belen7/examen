<?php
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');
//include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'pagination.php';
include_once 'categoriaTieneSubcategorias.php';
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
      $arreglo_entidades = explode(',',$entidades_a_eliminar);
      $cantidad_entidades = count($arreglo_entidades);
      $errorNro = 0;  
      $msg = "";
      /** SE INICIA LA TRANSACCION **/
      db_start_trans($conex);     
      foreach($arreglo_entidades as $idEntidad) {
            if ($idEntidad!=100) {
                  if (!hasSubcategorias($conex,$idEntidad)) {
                        $sql = "DELETE FROM categoria
                              WHERE id = $idEntidad";
                        //die($sql);   
                        $ok = @mysqli_query($conex,$sql);
                        //PRENGUNTAMOS SI HUBO ERROR
                        if(!$ok){
                              $errorNro =  mysqli_errno($conex);
                              db_rollback($conex);
                              break;
                        };
                  };
            }; 
      } // END FOR

      if ($errorNro) {
            if ($cantidad_entidades>1) {
                  $msg = "Hubo un Error en la Eliminación de las Categorías. ";
            } else {
                  $msg = "Hubo un Error en la Eliminación de la Categoría. ";
            }
            $array_resultados['codigo'] = 10;
            $array_resultados['mensaje'] = $msg;  
      } else {
            if ($cantidad_entidades>1) {
                  $msg = "La Eliminación de las Categorías se ha realizado. Si alguna categoría no se elimino, es porque debe tener una subcategoría asociada.";
            } else {
                  $msg = "La Eliminación de la Categoría se ha realizado. Si alguna categoría no se elimino, es porque debe tener una subcategoría asociada.";
            }
            db_commit($conex);
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = $msg;
      };
} else {
      $array_resultados['codigo'] = 100;
      $array_resultados['mensaje'] = "No se pudo Eliminar ninguna Categoría.";
}

echo json_encode($array_resultados);



?>
