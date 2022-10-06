<?php
/**
 *  Archivo: config.php
 *	Utilidad: Este archivo define constantes necesarias para la conexion con la base de datos.
*/

///////////////////// BASE DE DATOS local ///////////////////////////////////////////
define('DB_TYPE', "mysql");
define('DB_HOST', "localhost");
define('DB_PORT', "3306");
define('DB_NAME', "");
define('DB_USER', "");
define('DB_PASS', "");
//////////////////////////////////////////////////////////////////////////////////////
///////////////////// DEFINIMOS UN SECRETO ///////////////////////////////////////////
define('SECRETO', "mi_secreto");
$SYSTEM_ARRAY = json_encode(["SYSADMIN"=>"","SYSUSER"=>""]);
define('SYS_USERS',$SYSTEM_ARRAY);
?>