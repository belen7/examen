<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../lib');
//include_once 'seguridadNivel2.php';
include_once 'conexion.php';

function hasSubcategorias($conex,$idCategoria) {
    $sql = "SELECT c.*
            FROM categoria c
            WHERE c.categoria_id = $idCategoria
            ORDER BY c.id";

    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
        return true;
    } else {
        return false;
    }
};
  
//$idCategoria = $_POST["id"];
//echo hasSubcategorias($conex,$idCategoria);

?>
