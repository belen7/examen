<?php
//require_once('./controlAcceso.php');
require('../lib/fpdf/mc_table.php');
require_once('../lib/phpqrcode/qrlib.php');

$fechaActual = date('Y-m-d');


function sacaFechaExamen($idMateria, $idCalendario, $conex) {
    $arregloFechaExamenes = array();
    $sql = "SELECT fechaExamen,llamado
		             FROM materia_tiene_fechaexamen
		             WHERE idCalendarioAcademico={$idCalendario} and idMateria={$idMateria}";
    $resultado = mysqli_query($conex, $sql);
    if (mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $arreglo = array();
            array_push($arreglo, $fila['fechaExamen'], $fila['llamado']);
            array_push($arregloFechaExamenes, $arreglo);
        }
    };
    if (count($arregloFechaExamenes) == 0)
        $fecha = "No tiene Fecha";
    else if (count($arregloFechaExamenes) == 1)
        $fecha = $arregloFechaExamenes[0][0];
    else if (count($arregloFechaExamenes) == 2) {
        $fecha = "1er  Llamado: " . $arregloFechaExamenes[0][0] . " 2do Llamado: " . $arregloFechaExamenes[1][0];
    }
    return $fecha;
}

;

function GenerateWord() {
    //Get a random word
    $nb = rand(3, 10);
    $w = '';
    for ($i = 1; $i <= $nb; $i++)
        $w .= chr(rand(ord('a'), ord('z')));
    return $w;
}

function GenerateSentence() {
    //Get a random sentence
    $nb = rand(1, 10);
    $s = '';
    for ($i = 1; $i <= $nb; $i++)
        $s .= GenerateWord() . ' ';
    return substr($s, 0, -1);
}


$sqlUltimoEventoActivo = "SELECT max(c.id) as maximoId
                          FROM calendarioacademico c, evento e
                          WHERE  (c.idEvento=e.id) and
                                 (e.descripcion='Inscripcion 1er Turno de Examenes' or
                                  e.descripcion='Inscripcion 2do Turno de Examenes' or
                                  e.descripcion='Inscripcion 3er Turno de Examenes' or
                                  e.descripcion='Inscripcion Mesa Especial')";
$resultadoUltimoEventoActivo = mysqli_query($conex, $sqlUltimoEventoActivo);
$filaUltimoEventoActivo = mysqli_fetch_assoc($resultadoUltimoEventoActivo);
$idTurno = $filaUltimoEventoActivo['maximoId'];
$idAlumno = $_SESSION['idAlumno'];
//$idTurno = 32;
//$idAlumno = 78;

$sqlMateriasInscriptas = "SELECT distinct a.idMateria, b.nombre as nombreMateria, b.anio, a.idCalendario, a.nota
                                FROM alumno_rinde_materia a, materia b
                                WHERE a.idAlumno={$idAlumno} and
                                      a.idCalendario={$idTurno} and
                                      a.idMateria=b.id and
                                      (a.condicion='Regular' or a.condicion='Libre')";
//echo  $sqlMateriasInscriptas;
$resultadoMateriasInscriptas = mysqli_query($conex, $sqlMateriasInscriptas);
if (mysqli_num_rows($resultadoMateriasInscriptas) == 0) {
    header('location: examenesConsultarInscripcion.php');
}

$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 14);
//Table with 20 rows and 4 columns
$pdf->SetWidths(array(10, 60, 10, 20, 80));


if (mysqli_num_rows($resultadoMateriasInscriptas) > 0) {
    $pdf->SetFont('Times', '', 9);
    //$pdf->SetFillColor(102,102,102);
    $pdf->SetTextColor(255, 255, 255);
    $anio = utf8_decode('A単o');
    $pdf->Cell(10, 5, $anio, 1, 0, 'C', true);
    $pdf->Cell(60, 5, 'Materia', 1, 0, 'C', true);
    $pdf->Cell(10, 5, 'Nota', 1, 0, 'C', true);
    $pdf->Cell(20, 5, 'Fecha', 1, 0, 'C', true);
    $pdf->Cell(80, 5, 'Carrera', 1, 1, 'C', true);
    $pdf->SetTextColor(0, 0, 0);
    while ($fila = mysqli_fetch_assoc($resultadoMateriasInscriptas)) {
        
        if ($fila['nota']==0) $nota = "Pendiente";
        else if ($fila['nota']==-1) $nota = "Ausente";
        else $nota = $fila['nota'];
        $nombreCarrera = generaArregloCarrerasPorMateriaGeneral($fila['idMateria'], $conex);
        $fechaExamen = sacaFechaExamen($fila['idMateria'], $idTurno, $conex);
        $pdf->Row(array($fila['anio'], utf8_decode($fila['nombreMateria']), $nota, $fechaExamen, $nombreCarrera));
    };
};

$pdf->FooterMio($idTurno);

$valorParametro = base64_encode($idAlumno . '-' . $idTurno);
$url = "http://www.escuela40.net/WS/inscripcion/" . $valorParametro;
$nombreArchivo = "./QR/qrAlumno" . $idAlumno . ".png";
QRCode::png($url, $nombreArchivo, 'QR_ECLEVEL_Q', 5, 1);
$pdf->Image($nombreArchivo, 80, 122, 45, 45, 'png', $url);
$pdf->Output();
?>
