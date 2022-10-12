<?php
set_include_path('../../lib/'.PATH_SEPARATOR.'../../conexion/');
//include_once 'seguridadNivel.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once 'phpqrcode/qrlib.php';
//include_once 'ArrayHash.class.php';


function enviarEmail($arreglo) {
      //var_dump($arreglo[0]);die;
      $arr = $arreglo[0];
      $valorParametro = base64_encode($arr['dni']);
      $url = "https://escuela40.net/acreditacion/sistema/buscarPorQr.php?q=" . $valorParametro;
      //$url = "http://127.0.0.1/proyecto/acreditacion/sistema/buscarPorQr.php?q=" . $valorParametro;
      $nombreArchivo = "../../qr/qrInteresado".$arr['dni'].".png";
      //die('<zx<zx<zx'.$nombreArchivo);
      
      QRCode::png($url, $nombreArchivo, 'QR_ECLEVEL_Q', 5, 1);
      $para = $arr['email'];
      $titulo = 'Escuela 40 Mariano Moreno - Registro Exitoso';

      $mensaje = '<html>'.
            '<head><title>Email con HTML</title></head>'.
            '<body><h1>Datos de la Persona que Asistira a las Jornadas</h1>'.
            'Por favor no elimine éste email'.
            '<hr>'.
            'Enviado por mi programa en PHP'.
            '<table>'.
            '<tr><th align="left">Apellido</th><td>'.$arr['apellido'].'</td></tr>'.
            '<tr><th align="left">Nombres</th><td>'.$arr['nombres'].'</td></tr>'.
            '<tr><th align="left">DNI</th><td>'.$arr['dni'].'</td></tr>'.
            '<tr><th align="left">Domiclio</th><td>'.$arr['direccion'].'</td></tr>'.
            '<tr><th align="left">Telefono</th><td>'.$arr['telefono'].'</td></tr>'.
            '<tr><th colspan="2" align="center"><img src="https://escuela40.net/acreditacion/qr/qrInteresado'.$arr['dni'].'.png" width="200"></td></tr>'.
            '</table>'.
            '</body>'.
            '</html>';
      $cabeceras = 'MIME-Version: 1.0' . "\r\n";
      $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
      $cabeceras .= 'From: noreply@escuela40.net';
      mail($para, $titulo, $mensaje, $cabeceras);
};



/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$id = ( isset($_POST['interesado_id']) )?SanitizeVars::INT($_POST['interesado_id']):false;


/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();
if ($id) {
      $sql = "SELECT c.*, l.nombre as localidad_nombre, p.nombre as provincia_nombre
              FROM interesado c, localidad l, provincia p
              WHERE c.id = $id and c.localidad_id=l.id and l.provincia_id=p.id";
      //die($sql);    

      $ok = mysqli_query($conex,$sql);
      //PRENGUNTAMOS SI HUBO ERROR
      if($ok){
            if (mysqli_num_rows($ok)>0) {
                  $filas = mysqli_fetch_all($ok,MYSQLI_ASSOC);
                  $array_resultados['codigo'] = 100;
                  $array_resultados['datos'] = $filas;
                  $array_resultados['mensaje'] = "El Email fuen enviado Exitosamente con el Código QR.";
                  enviarEmail($filas);
                } else {
                  $array_resultados['codigo'] = 12;
                  $array_resultados['mensaje'] = "No existen Interesados con ese ID.";
                }
                
      } else {
            $array_resultados['codigo'] = 11;
            $array_resultados['mensaje'] = "Error en la consulta.";
      };
} else {
      $array_resultados['codigo'] = 10;
      $array_resultados['mensaje'] = "Debe completar todos los datos.";
};

echo json_encode($array_resultados);



?>
