<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../lib');
include_once 'controlAcceso.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$array_resultados = array();

$sql = "SELECT *
        FROM usuario_rol ur
        ORDER BY ur.id";

$resultado = mysqli_query($conex,$sql);
if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 100;
   $array_resultados['data'] = $filas;
} else {
  $array_resultados['codigo'] = 11;
  $array_resultados['data'] = "No existen Roles.";
}

echo json_encode($array_resultados);

?>
