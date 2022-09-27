<?php
require_once('tcpdf/tcpdf.php');

class MyPdf extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = './public/img/escudo.jpg';
        //$image_file = '';
        $this->Image($image_file, 10, 5, 37, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
				//          (arch       ,  coord_x, coord_y, tamanio
        // Set font
        // Title
				$this->SetFont('helvetica', 'B', 13);
				$this->SetXY(52,8);
        $this->Cell(100, 18, 'Caja de Jubilaciones y Pensiones', 0, false, 'L', 0, '', 0, false, 'M', 'M');
				$this->SetXY(52,14);
				$this->SetFont('helvetica', '', 11);
				$this->Cell(100, 18, 'Provincia de Santa Fe', 0, false, 'L', 0, '', 0, false, 'M', 'M');
    }

}



?>
