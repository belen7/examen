<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../lib');
//include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$dni = ( isset($_POST['dni']) )?SanitizeVars::INT($_POST['dni']):false;

$array_resultados = array();
if ($dni) {
    $sql = "SELECT c.*, UPPER(l.nombre) as localidad_nombre, UPPER(p.nombre) as provincia_nombre
            FROM interesado c, localidad l, provincia p
            WHERE c.dni = $dni and c.localidad_id=l.id and l.provincia_id=p.id";

    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
      $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
      $array_resultados['codigo'] = 100;
      $array_resultados['datos'] = $filas;
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['datos'] = "No existen Clientes.";
    }
} else {
  $array_resultados['codigo'] = 10;
  $array_resultados['datos'] = "El DNI de Cliente es Incorrecto.";
}

echo json_encode($array_resultados);

?>
